<?php

namespace App\Exports;

use App\Exports\Concerns\ReportHeaderLayout;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DailySalesReportExport implements FromArray, ShouldAutoSize, WithColumnWidths, WithCustomStartCell, WithEvents
{
    use ReportHeaderLayout;

    private const TABLE_START_ROW = 10;

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

    private string $reportDate;

    public function __construct(
        private readonly Collection $itemsByDate,
        ?string $reportDate = null,
    ) {
        $this->reportDate = $reportDate ?? now()->format('F j, Y');
    }

    protected function titlePeriod(): string
    {
        return 'daily';
    }

    public function startCell(): string
    {
        return 'A'.self::TABLE_START_ROW;
    }

    /**
     * @return array{0: string, 1: string}
     */
    protected function titlePeriodValues(): array
    {
        $parsed = CarbonImmutable::parse($this->reportDate);

        return [
            $parsed->format('F j, Y'),
            '',
        ];
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function array(): array
    {
        $rows = [];

        $currentRow = self::TABLE_START_ROW;

        $rows[] = ['Ref No.', 'Product Name', 'Subcategory', 'Quantity', 'Unit', 'Class', 'Size', 'Inventory', '', 'Price', 'Subtotal', 'Remarks', 'Associate'];
        $currentRow++;

        $rows[] = ['', '', '', '', '', '', '', 'Start', 'End', '', '', ''];
        $currentRow++;

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
                    $rows[] = ['', '', '', '', '', '', '', '', '', "{$category} LGU Support", $categoryItems->sum('subtotal'), ''];
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
                $rows[] = ['', '', '', '', '', '', '', '', '', 'Subtotal LGU Support', $dispersals->sum('subtotal'), ''];
                $currentRow++;
                $this->totalRows[] = $currentRow;
                $rows[] = ['', '', '', '', '', '', '', '', '', 'Subtotal Sales', $sales->sum('subtotal'), ''];
                $currentRow++;
            } elseif ($hasDispersals) {
                $rows[] = ['', '', '', '', '', '', '', '', '', 'Total LGU Support', $dispersals->sum('subtotal'), ''];
                $currentRow++;
            } else {
                $rows[] = ['', '', '', '', '', '', '', '', '', 'Total Sales', $sales->sum('subtotal'), ''];
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
            'J' => 20,
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
                $this->applyHeader($sheet, 'M');

                $highestRow = $sheet->getHighestRow();
                $tableStart = self::TABLE_START_ROW;
                $tableHeaderEnd = $tableStart + 1;

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

                $sheet->mergeCells("A{$tableHeaderEnd}:B{$tableHeaderEnd}");
                $sheet->mergeCells('H10:I10');
                //                $sheet->mergeCells("A{$tableStart}:M{$tableStart}");

                $sheet->getStyle("A{$tableStart}:M{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    //                    'alignment' => [
                    //                        'vertical' => Alignment::VERTICAL_CENTER,
                    //                    ],
                ]);
                $sheet->getStyle("H{$tableStart}:H{$highestRow}")->getAlignment()->setWrapText(true);

                $sheet->getStyle("A{$tableStart}:M{$tableHeaderEnd}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    //                    'alignment' => [
                    //                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    //                    ],
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

                    $sheet->getStyle("H{$dateRow}:I{$highestRow}")->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    $sheet->getStyle("D{$dateRow}:D{$highestRow}")->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    $sheet->getStyle("J{$dateRow}:K{$highestRow}")->getNumberFormat()->setFormatCode('#,##0.00');
                }

                foreach ($this->categoryTotalRows as $categoryTotalRow) {
                    $sheet->getStyle("A{$categoryTotalRow}:M{$categoryTotalRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                    ]);
                }

                foreach ($this->totalRows as $totalRow) {
                    $sheet->getStyle("A{$totalRow}:M{$totalRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                    ]);
                }

            },
        ];
    }
}
