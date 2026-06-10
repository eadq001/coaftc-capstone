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
            ['Ref No.', 'Product Name', 'Subcategory', 'Quantity', 'Unit', 'Class', 'Size', 'Inventory', '', 'Price', 'Subtotal', 'Remarks', 'Associate'],
            ['', '', '', '', '', '', '', 'Start', 'End', '', '', ''],
        ];

        $currentRow = 3;

        foreach ($this->itemsByDate as $date => $items) {
            $this->dateRows[] = $currentRow;
            $rows[] = [date_format(date_create($date), 'F j, Y'), '', '', '', '', '', '', '', '', ''];
            $currentRow++;

            $sales = $items->filter(fn ($item) => ! str_starts_with($item['transaction_number'], 'LGU'));
            $dispersals = $items->filter(fn ($item) => str_starts_with($item['transaction_number'], 'LGU'));

            foreach ($items as $item) {
                $rows[] = [
                    $item['transaction_number'],
                    $item['product_name'],
                    $item['category_name'],
                    $item['quantity'],
                    $item['unit_name'],
                    $item['class'],
                    $item['size'],
                    $item['inventory_start'],
                    $item['inventory_end'],
                    number_format((float) $item['unit_price'], 2, '.', ','),
                    $item['subtotal'],
                    $item['remarks'],
                    $item['user_name'],
                ];
                $currentRow++;
            }

            if ($dispersals->isNotEmpty()) {
                foreach ($dispersals->groupBy(fn ($item) => $item['category_name'] ?? 'Uncategorized') as $category => $categoryItems) {
                    $this->categoryTotalRows[] = $currentRow;
                    $rows[] = ['', '', '', '', '', '', '', '', '', "{$category} Dispersal", $categoryItems->sum('subtotal'), ''];
                    $currentRow++;
                }
            }

            if ($sales->isNotEmpty()) {
                foreach ($sales->groupBy(fn ($item) => $item['category_name'] ?? 'Uncategorized') as $category => $categoryItems) {
                    $this->categoryTotalRows[] = $currentRow;
                    $rows[] = ['', '', '', '', '', '', '', '', '', "{$category}", $categoryItems->sum('subtotal'), ''];
                    $currentRow++;
                }
            }

            $this->totalRows[] = $currentRow;

            $hasSales = $sales->isNotEmpty();
            $hasDispersals = $dispersals->isNotEmpty();

            if ($hasSales && $hasDispersals) {
                $rows[] = ['', '', '', '', '', '', '', '', '', 'Total Dispersal', $dispersals->sum('subtotal'), ''];
                $currentRow++;
                $this->totalRows[] = $currentRow;
                $rows[] = ['', '', '', '', '', '', '', '', '', 'Total', $sales->sum('subtotal'), ''];
                $currentRow++;
            } elseif ($hasDispersals) {
                $rows[] = ['', '', '', '', '', '', '', '', '', 'Total Dispersal', $dispersals->sum('subtotal'), ''];
                $currentRow++;
            } else {
                $rows[] = ['', '', '', '', '', '', '', '', '', 'Total', $sales->sum('subtotal'), ''];
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
            'H' => 10,
            'I' => 10,
            'J' => 24,
            'K' => 14,
            'L' => 20,
            'M' => 14,

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

                // adjust the page margins
                $sheet->getPageMargins()->setTop(0.5511);
                $sheet->getPageMargins()->setHeader(0.3149);
                $sheet->getPageMargins()->setLeft(0.1181);
                $sheet->getPageMargins()->setRight(0);
                $sheet->getPageMargins()->setBottom(0.3543);
                $sheet->getPageMargins()->setFooter(0.3149);

                // long paper in landscape orientation and already fit to page scaling for printing
                $sheet->getPageSetup()->setPaperSize(18);
                $sheet->getPageSetup()->setOrientation('landscape');
                $sheet->getPageSetup()->setFitToPage(true);

                //                $sheet->getSheetView()->setZoomScale(85);

                $sheet->mergeCells('H1:I1');
                $sheet->getStyle("A1:M{$highestRow}")->applyFromArray([
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
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ],
                ]);

                foreach ($this->dateRows as $dateRow) {
                    $sheet->mergeCells("A{$dateRow}:M{$dateRow}");
                    $sheet->getStyle("A{$dateRow}:M{$dateRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                        ],
                    ]);

                    $sheet->getStyle("H4:I{$highestRow}")->applyFromArray([
                        //                    'font' => [
                        //                        'bold' => true,
                        //                    ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    $sheet->getStyle("D4:D{$highestRow}")->applyFromArray([
                        //                    'font' => [
                        //                        'bold' => true,
                        //                    ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    $sheet->getStyle("J4:K{$highestRow}")->getNumberFormat()->setFormatCode('#,##0.00');
                }

                foreach ($this->categoryTotalRows as $categoryTotalRow) {
                    $sheet->getStyle("A{$categoryTotalRow}:J{$categoryTotalRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                    ]);
                }

                foreach ($this->totalRows as $totalRow) {
                    $sheet->getStyle("A{$totalRow}:L{$totalRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                    ]);
                }

            },
        ];
    }
}
