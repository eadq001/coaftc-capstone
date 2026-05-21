<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DailySalesReportExport implements FromArray, ShouldAutoSize, WithColumnWidths, WithEvents
{
    /**
     * @var array<int, int>
     */
    private array $dateRows = [];

    /**
     * @var array<int, int>
     */
    private array $totalRows = [];

    /**
     * @var array<int, int>
     */
    private array $categoryTotalRows = [];

    public function __construct(private readonly Collection $itemsByDate) {}

    /**
     * @return array<int, array<int, mixed>>
     */
    public function array(): array
    {
        $rows = [
            ['PRF No.', 'Product Name', 'Subcategory', 'Quantity', 'Unit', 'Inventory', '', 'Price', 'Subtotal', 'Associate'],
            ['', '', '', '', '', 'Start', 'End', '', '', ''],
        ];

        $currentRow = 3;

        foreach ($this->itemsByDate as $date => $items) {
            $this->dateRows[] = $currentRow;
            $rows[] = [date_format(date_create($date), 'F j, Y'), '', '', '', '', '', '', '', '', ''];
            $currentRow++;

            foreach ($items as $item) {
                $rows[] = [
                    $item->sale->prf_number,
                    $item->product->name,
                    $item->product->category?->category_name ?? 'Uncategorized',
                    $item->quantity,
                    $item->product->unit?->unit_name ?? 'N/A',
                    $item->inventory_start,
                    $item->inventory_end,
                    $item->unit_price,
                    $item->subtotal,
                    $item->sale->user?->name ?? 'N/A',
                ];
                $currentRow++;
            }

            $this->totalRows[] = $currentRow;
            $rows[] = ['', '', '', '', '', '', '', 'Total', $items->sum('subtotal'), ''];
            $currentRow++;

            foreach ($items->groupBy(fn ($item) => $item->product->category?->category_name ?? 'Uncategorized') as $category => $categoryItems) {
                $this->categoryTotalRows[] = $currentRow;
                $rows[] = ['', '', '', '', '', '', '', "{$category}", $categoryItems->sum('subtotal'), ''];
                $currentRow++;
            }
        }

        return $rows;
    }

    /**
     * @return array<string, float>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 13,
            'B' => 22,
            'C' => 18,
            'D' => 10,
            'E' => 14,
            'F' => 10,
            'G' => 10,
            'H' => 18,
            'I' => 14,
            'J' => 20,
        ];
    }

    /**
     * @return array<class-string, callable>
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $sheet->mergeCells('F1:G1');
                $sheet->getStyle("A1:J{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getStyle("H1:H{$highestRow}")->getAlignment()->setWrapText(true);

                $sheet->getStyle('A1:J2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                foreach ($this->dateRows as $dateRow) {
                    $sheet->mergeCells("A{$dateRow}:J{$dateRow}");
                    $sheet->getStyle("A{$dateRow}:J{$dateRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                }

                foreach ($this->totalRows as $totalRow) {
                    $sheet->getStyle("A{$totalRow}:J{$totalRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                    ]);
                }

                foreach ($this->categoryTotalRows as $categoryTotalRow) {
                    $sheet->getStyle("A{$categoryTotalRow}:J{$categoryTotalRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                    ]);
                }
            },
        ];
    }
}
