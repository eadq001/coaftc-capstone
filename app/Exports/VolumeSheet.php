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
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class VolumeSheet implements FromArray, ShouldAutoSize, WithColumnWidths, WithCustomStartCell, WithEvents, WithTitle
{
    use ReportHeaderLayout;

    private const START_ROW = 10;

    private array $dateRows = [];

    private string $reportDate;

    public function __construct(
        private readonly Collection $volumeProduced,
        private readonly Collection $volumeSold,
        private readonly Collection $itemsByDate,
        ?string $reportDate = null,
    ) {
        $this->reportDate = $reportDate ?? now()->format('F j, Y');
    }

    public function title(): string
    {
        return 'Volume Report';
    }

    protected function titlePeriod(): string
    {
        return 'daily';
    }

    public function startCell(): string
    {
        return 'A'.self::START_ROW;
    }

    protected function titlePeriodValues(): array
    {
        $parsed = CarbonImmutable::parse($this->reportDate);

        return [
            $parsed->format('F j, Y'),
            '',
        ];
    }

    public function array(): array
    {
        $rows = [];
        $currentRow = self::START_ROW;

        $rows[] = ['Date', 'Product Name', 'Volume Produced', 'Volume Sold', 'Unit', 'Sales'];
        $currentRow++;

        $dates = $this->volumeProduced
            ->keys()
            ->merge($this->volumeSold->keys())
            ->merge($this->itemsByDate->keys())
            ->unique()
            ->sort();

        foreach ($dates as $date) {
            $produced = $this->volumeProduced->get($date, collect());
            $sold = $this->volumeSold->get($date, collect());

            $producedByProduct = $produced->isNotEmpty()
                ? $produced->keyBy('product_name')->map(fn ($item) => [
                    'quantity_added' => $item['quantity_added'],
                    'unit_name' => $item['unit_name'] ?? '',
                    'total_sales' => 0,
                ])
                : collect();

            $soldByProduct = $sold->isNotEmpty()
                ? $sold->keyBy('product_name')->map(fn ($item) => [
                    'quantity_sold' => $item['quantity_sold'],
                    'unit_name' => $item['unit_name'] ?? '',
                    'total_sales' => $item['total_sales'] ?? 0,
                ])
                : collect();

            $productNames = $producedByProduct
                ->keys()
                ->merge($soldByProduct->keys())
                ->unique()
                ->sort()
                ->values();

            if ($productNames->isNotEmpty()) {
                $this->dateRows[] = $currentRow;
                $formattedDate = date_format(date_create($date), 'F j, Y');
                $rows[] = [$formattedDate, '', '', '', '', ''];
                $currentRow++;

                foreach ($productNames as $productName) {
                    $rows[] = [
                        '',
                        $productName,
                        $producedByProduct->get($productName)['quantity_added'] ?? 0,
                        $soldByProduct->get($productName)['quantity_sold'] ?? 0,
                        $soldByProduct->get($productName)['unit_name']
                            ?? $producedByProduct->get($productName)['unit_name']
                            ?? '',
                        $soldByProduct->get($productName)['total_sales'] ?? 0,
                    ];
                    $currentRow++;
                }
            }
        }

        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 16,
            'B' => 28,
            'C' => 18,
            'D' => 14,
            'E' => 14,
            'F' => 16,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $this->applyHeader($sheet, 'F');

                $highestRow = $sheet->getHighestRow();
                $start = self::START_ROW;

                $sheet->getPageMargins()->setTop(0.5511);
                $sheet->getPageMargins()->setHeader(0.3149);
                $sheet->getPageMargins()->setLeft(0.1181);
                $sheet->getPageMargins()->setRight(0);
                $sheet->getPageMargins()->setBottom(0.3543);
                $sheet->getPageMargins()->setFooter(0.3149);

                $sheet->getPageSetup()->setPaperSize(18);
                $sheet->getPageSetup()->setOrientation('landscape');
                $sheet->getPageSetup()->setFitToPage(true);

                $sheet->getStyle("F12:F{$highestRow}")->getNumberFormat()->setFormatCode('#,##0.00');

                $sheet->getStyle("A{$start}:F{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->getStyle("A{$start}:F{$start}")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);

                foreach ($this->dateRows as $dateRow) {
                    $sheet->mergeCells("A{$dateRow}:F{$dateRow}");
                    $sheet->getStyle("A{$dateRow}:F{$dateRow}")->applyFromArray([
                        'font' => ['bold' => true],
                    ]);
                }
            },
        ];
    }
}
