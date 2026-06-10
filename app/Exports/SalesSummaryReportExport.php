<?php

namespace App\Exports;

use App\Exports\Concerns\ReportHeaderLayout;
use App\Models\SalesItem;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesSummaryReportExport implements FromArray, WithColumnWidths, WithCustomStartCell, WithEvents
{
    use ReportHeaderLayout;

    private const HEADER_HEIGHT_ROWS = 8;

    private const TABLE_START_ROW = 10;

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

    private string $periodType;

    private string $periodValue;

    public function __construct(
        private readonly Collection $itemsByMonth,
        private readonly bool $includeGrandTotal = false,
        private readonly Collection $dispersalsByMonth = new Collection,
    ) {
        $months = $this->itemsByMonth->keys();
        $this->periodType = $this->includeGrandTotal ? 'yearly' : 'monthly';

        if ($this->periodType === 'yearly' && $months->isNotEmpty()) {
            $firstMonth = $months->first();
            $this->periodValue = CarbonImmutable::createFromFormat('Y-m', $firstMonth)->format('Y');
        } elseif ($months->isNotEmpty()) {
            $firstMonth = $months->first();
            $this->periodValue = CarbonImmutable::createFromFormat('Y-m', $firstMonth)->format('F Y');
        } else {
            $this->periodValue = now()->format('F Y');
        }
    }

    protected function titlePeriod(): string
    {
        return $this->periodType;
    }

    protected function headerEndColumn(): string
    {
        return 'U';
    }

    /**
     * @return array{0: string, 1: string}
     */
    protected function titlePeriodValues(): array
    {
        return [$this->periodValue, ''];
    }

    public function startCell(): string
    {
        return 'A'.self::TABLE_START_ROW;
    }

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
        $currentRow = self::TABLE_START_ROW;
        $allItems = collect();
        $allDispersals = collect();

        foreach ($this->itemsByMonth as $month => $items) {
            if ($items->isEmpty()) {
                continue;
            }

            $monthDispersals = $this->dispersalsByMonth->get($month, collect());

            $currentRow++;
            $headerRow = $currentRow;
            $this->headerRows[] = $headerRow;
            $rows[] = $this->headerRow();
            $currentRow++;

            $monthStartRow = $currentRow;
            $itemsByDate = $items
                ->groupBy(fn (SalesItem $item): string => $item->sale->created_at->format('Y-m-d'))
                ->sortKeys();

            $dispersalsByDate = $monthDispersals
                ->groupBy(fn ($item): string => $item->dispersal->created_at->format('Y-m-d'))
                ->sortKeys();

            $allDates = $itemsByDate->keys()->merge($dispersalsByDate->keys())->unique()->sort();

            foreach ($allDates as $date) {
                $dateItems = $itemsByDate->get($date, collect());
                $dateDispersals = $dispersalsByDate->get($date, collect());
                $rows[] = $this->dateRow($month, $date, $dateItems, $dateDispersals, $currentRow === $monthStartRow);
                $currentRow++;
            }

            $this->totalRows[] = $currentRow;
            $rows[] = $this->totalRow($items, $monthDispersals);
            $this->monthMergeRows[] = [$monthStartRow, $currentRow];
            $this->tableRanges[] = [$headerRow, $currentRow];
            $currentRow++;

            $allItems = $allItems->merge($items);
            $allDispersals = $allDispersals->merge($monthDispersals);
        }

        if ($this->includeGrandTotal && ($allItems->isNotEmpty() || $allDispersals->isNotEmpty())) {
            $currentRow++;
            $this->grandTotalRow = $currentRow;
            $rows[] = $this->grandTotalRow($allItems, $allDispersals);
        }

        return $rows === [] ? ['', $this->headerRow()] : $rows;
    }

    /**
     * @return array<string, float|int>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 3.77734375,
            'B' => 7.77734375,
            'C' => 10,
            'D' => 12.77734375,
            'E' => 12.77734375,
            'F' => 12.5546875,
            'G' => 11.21875,
            'H' => 10.77734375,
            'I' => 10.77734375,
            'J' => 10.77734375,
            'K' => 10.77734375,
            'L' => 11.77734375,
            'M' => 10.77734375,
            'N' => 10.77734375,
            'O' => 12.6640625,
            'P' => 11.77734375,
            'Q' => 11.77734375,
            'R' => 10.77734375,
            'S' => 9,
            'T' => 12.77734375,
            'U' => 12.77734375,
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
                $this->applyHeader($sheet);

                $highestRow = $sheet->getHighestRow();

                $sheet->getStyle("A1:U{$highestRow}")->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // default font
                $sheet->getStyle("A1:U{$highestRow}")
                    ->getFont()
                    ->setName('Arial Narrow')
                    ->setSize(11);

                // rotate the month to 90 degree
                $sheet->getStyle("A1:A{$highestRow}")->applyFromArray([
                    'alignment' => [
                        'textRotation' => 90,
                    ],
                ]);

                // prices are with .00
                $sheet->getStyle("D1:U{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                // adjust the page margins
                $sheet->getPageMargins()->setTop(0.5511);
                $sheet->getPageMargins()->setHeader(0.3149);
                $sheet->getPageMargins()->setLeft(0.1181);
                $sheet->getPageMargins()->setRight(0);
                $sheet->getPageMargins()->setBottom(0.3543);
                $sheet->getPageMargins()->setFooter(0.3149);

                // long paper in landscape orientation and already fit to page scaling for printing
                $sheet->getPageSetup()->setPaperSize(14);
                $sheet->getPageSetup()->setOrientation('landscape');
                $sheet->getPageSetup()->setFitToPage(true);

                $sheet->getSheetView()->setZoomScale(85);

                foreach ($this->tableRanges as [$startRow, $endRow]) {
                    $sheet->getStyle("A{$startRow}:U{$endRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }

                foreach ($this->headerRows as $headerRow) {
                    $sheet->getStyle("B{$headerRow}:U{$headerRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
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
                    $sheet->getStyle("A{$totalRow}:U{$totalRow}")->applyFromArray([
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
                    $sheet->getStyle("A{$this->grandTotalRow}:U{$this->grandTotalRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],

                        'alignment' => [
                            'textRotation' => 0,
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
    private function headerRow(): array
    {
        return [
            '',
            'Date',
            'OR Number',
            ...self::CATEGORIES,
            'TOTAL',
            'Dispersal',
        ];
    }

    /**
     * @param  Collection<int, SalesItem>  $items
     * @return array<int, mixed>
     */
    private function dateRow(string $month, string $date, Collection $items, Collection $dispersals, bool $showMonth): array
    {
        $categoryTotals = $this->categoryTotals($items);
        $row = [
            $showMonth ? $this->spacedMonthName($month) : '',
            CarbonImmutable::parse($date)->day, ' '];

        // shows the amount values in the rows
        foreach (self::CATEGORIES as $category) {
            $row[] = $this->amountOrBlank($categoryTotals[$category]);
        }

        $row[] = $this->amountOrBlank((float) $items->sum('subtotal'));
        $row[] = $this->amountOrBlank((float) $dispersals->sum('subtotal'));

        return $row;
    }

    /**
     * @param  Collection<int, SalesItem>  $items
     * @return array<int, mixed>
     */
    private function totalRow(Collection $items, Collection $dispersals = new Collection): array
    {
        $categoryTotals = $this->categoryTotals($items);
        $row = ['', 'TOTAL', ''];

        foreach (self::CATEGORIES as $category) {
            $row[] = $this->amountOrBlank($categoryTotals[$category]);
        }

        $row[] = $this->amountOrBlank((float) $items->sum('subtotal'));
        $row[] = $this->amountOrBlank((float) $dispersals->sum('subtotal'));

        return $row;
    }

    /**
     * @param  Collection<int, SalesItem>  $items
     * @return array<int, mixed>
     */
    private function grandTotalRow(Collection $items, Collection $dispersals = new Collection): array
    {
        $categoryTotals = $this->categoryTotals($items);
        $row = ['GRAND TOTAL', '', ''];

        foreach (self::CATEGORIES as $category) {
            $row[] = $this->amountOrBlank($categoryTotals[$category]);
        }

        $row[] = $this->amountOrBlank((float) $items->sum('subtotal'));
        $row[] = $this->amountOrBlank((float) $dispersals->sum('subtotal'));

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
