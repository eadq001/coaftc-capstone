<?php

namespace App\Exports;

use App\Models\SalesItem;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesSummaryReportExport implements FromArray, ShouldAutoSize, WithColumnWidths, WithEvents
{
    private const CATEGORIES = [
        'North Vegetables',
        'Admin Vegetables',
        'South Vegetables',
        'Hydroponics',
        'Fruits / High Value Crops',
        'Fisheries',
        'Organic Inputs',
        'Agricultural Inputs',
        'Ornamentals',
        'Coconut',
        'Processed Products',
        'Poultry',
        'Livestock',
        'Rootcrops & Cereals',
        'Others',
        'Training Center Fee',
    ];

    /**
     * @var array<int, int>
     */
    private array $headerRows = [];

    /**
     * @var array<int, array{0: int, 1: int}>
     */
    private array $monthMergeRows = [];

    /**
     * @var array<int, int>
     */
    private array $totalRows = [];

    /**
     * @var array<int, array{0: int, 1: int}>
     */
    private array $tableRanges = [];

    private ?int $grandTotalRow = null;

    public function __construct(
        private readonly Collection $itemsByMonth,
        private readonly bool $includeGrandTotal = false,
    ) {}

    /**
     * @return array<int, array<int, mixed>>
     */
    public function array(): array
    {
        $this->headerRows = [];
        $this->monthMergeRows = [];
        $this->totalRows = [];
        $this->tableRanges = [];
        $this->grandTotalRow = null;

        $rows = [];
        $currentRow = 1;
        $allItems = collect();

        foreach ($this->itemsByMonth as $month => $items) {
            if ($items->isEmpty()) {
                continue;
            }

            $rows[] = $this->blankRow();
            $currentRow++;

            $headerRow = $currentRow;
            $this->headerRows[] = $headerRow;
            $rows[] = $this->headerRow();
            $currentRow++;

            $monthStartRow = $currentRow;
            $itemsByDate = $items
                ->groupBy(fn (SalesItem $item): string => $item->sale->created_at->format('Y-m-d'))
                ->sortKeys();

            foreach ($itemsByDate as $date => $dateItems) {
                $rows[] = $this->dateRow($month, $date, $dateItems, $currentRow === $monthStartRow);
                $currentRow++;
            }

            $this->totalRows[] = $currentRow;
            $rows[] = $this->totalRow($items);
            $this->monthMergeRows[] = [$monthStartRow, $currentRow];
            $this->tableRanges[] = [$headerRow, $currentRow];
            $currentRow++;

            $allItems = $allItems->merge($items);
        }

        if ($this->includeGrandTotal && $allItems->isNotEmpty()) {
            $rows[] = $this->blankRow();
            $currentRow++;

            $this->grandTotalRow = $currentRow;
            $rows[] = $this->grandTotalRow($allItems);
        }

        return $rows === [] ? [$this->blankRow(), $this->headerRow()] : $rows;
    }

    /**
     * @return array<string, float|int>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 16,
            'B' => 10,
            'C' => 24,
            'D' => 18,
            'E' => 18,
            'F' => 18,
            'G' => 16,
            'H' => 26,
            'I' => 14,
            'J' => 18,
            'K' => 22,
            'L' => 16,
            'M' => 14,
            'N' => 20,
            'O' => 14,
            'P' => 14,
            'Q' => 22,
            'R' => 14,
            'S' => 22,
            'T' => 15,
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

                $sheet->getStyle("A1:T{$highestRow}")->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                $sheet->getStyle("D1:T{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                foreach ($this->tableRanges as [$startRow, $endRow]) {
                    $sheet->getStyle("A{$startRow}:T{$endRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }

                foreach ($this->headerRows as $headerRow) {
                    $sheet->getStyle("A{$headerRow}:T{$headerRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'D9EAD3',
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                }

                foreach ($this->monthMergeRows as [$startRow, $endRow]) {
                    $sheet->mergeCells("A{$startRow}:A{$endRow}");
                    $sheet->getStyle("A{$startRow}:A{$endRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }

                foreach ($this->totalRows as $totalRow) {
                    $sheet->getStyle("A{$totalRow}:T{$totalRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FCE5CD',
                            ],
                        ],
                    ]);
                }

                if ($this->grandTotalRow !== null) {
                    $sheet->mergeCells("A{$this->grandTotalRow}:C{$this->grandTotalRow}");
                    $sheet->getStyle("A{$this->grandTotalRow}:T{$this->grandTotalRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'B7E1CD',
                            ],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }
            },
        ];
    }

    /**
     * @return array<int, string>
     */
    private function blankRow(): array
    {
        return array_fill(0, 20, '');
    }

    /**
     * @return array<int, string>
     */
    private function headerRow(): array
    {
        return [
            '',
            'Date',
            'OR Number',
            ...self::CATEGORIES,
            'TOTAL',
        ];
    }

    /**
     * @param  Collection<int, SalesItem>  $items
     * @return array<int, mixed>
     */
    private function dateRow(string $month, string $date, Collection $items, bool $showMonth): array
    {
        $categoryTotals = $this->categoryTotals($items);
        $row = [
            $showMonth ? $this->spacedMonthName($month) : '',
            CarbonImmutable::parse($date)->day,
            $items->pluck('sale.prf_number')->filter()->unique()->values()->implode(', '),
        ];

        foreach (self::CATEGORIES as $category) {
            $row[] = $this->amountOrBlank($categoryTotals[$category]);
        }

        $row[] = $this->amountOrBlank((float) $items->sum('subtotal'));

        return $row;
    }

    /**
     * @param  Collection<int, SalesItem>  $items
     * @return array<int, mixed>
     */
    private function totalRow(Collection $items): array
    {
        $categoryTotals = $this->categoryTotals($items);
        $row = ['', 'TOTAL', ''];

        foreach (self::CATEGORIES as $category) {
            $row[] = $this->amountOrBlank($categoryTotals[$category]);
        }

        $row[] = $this->amountOrBlank((float) $items->sum('subtotal'));

        return $row;
    }

    /**
     * @param  Collection<int, SalesItem>  $items
     * @return array<int, mixed>
     */
    private function grandTotalRow(Collection $items): array
    {
        $categoryTotals = $this->categoryTotals($items);
        $row = ['GRAND TOTAL', '', ''];

        foreach (self::CATEGORIES as $category) {
            $row[] = $this->amountOrBlank($categoryTotals[$category]);
        }

        $row[] = $this->amountOrBlank((float) $items->sum('subtotal'));

        return $row;
    }

    /**
     * @param  Collection<int, SalesItem>  $items
     * @return array<string, float>
     */
    private function categoryTotals(Collection $items): array
    {
        $totals = array_fill_keys(self::CATEGORIES, 0.0);

        foreach ($items as $item) {
            $totals[$this->reportCategory($item)] += (float) $item->subtotal;
        }

        return $totals;
    }

    private function reportCategory(SalesItem $item): string
    {
        $categoryName = trim((string) ($item->product?->category?->category_name ?? ''));

        if ($categoryName === '') {
            return 'Others';
        }

        $normalizedCategoryName = $this->normalizedCategoryName($categoryName);

        foreach (self::CATEGORIES as $category) {
            if ($normalizedCategoryName === $this->normalizedCategoryName($category)) {
                return $category;
            }
        }

        return 'Others';
    }

    private function normalizedCategoryName(string $categoryName): string
    {
        return Str::of($categoryName)
            ->lower()
            ->replace('&', 'and')
            ->replace('/', ' ')
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->squish()
            ->toString();
    }

    private function spacedMonthName(string $month): string
    {
        $monthName = CarbonImmutable::createFromFormat('Y-m', $month)->format('F');

        return implode(' ', str_split(strtoupper($monthName)));
    }

    private function amountOrBlank(float $amount): float|string
    {
        return $amount === 0.0 ? '' : $amount;
    }
}
