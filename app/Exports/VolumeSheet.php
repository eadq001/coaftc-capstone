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

        $rows[] = ['Date', 'Product Name', 'Volume Produced', 'Volume Sold'];
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
                ? $produced->keyBy('product_name')->map(fn ($item) => $item['quantity_added'])
                : collect();

            $soldByProduct = $sold->isNotEmpty()
                ? $sold->keyBy('product_name')->map(fn ($item) => $item['quantity_sold'])
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
                $rows[] = [$formattedDate, '', '', ''];
                $currentRow++;

                foreach ($productNames as $productName) {
                    $rows[] = [
                        '',
                        $productName,
                        $producedByProduct->get($productName, 0),
                        $soldByProduct->get($productName, 0),
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
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $this->applyHeader($sheet, 'D');

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

                $sheet->getStyle("A{$start}:D{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->getStyle("A{$start}:D{$start}")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                foreach ($this->dateRows as $dateRow) {
                    $sheet->mergeCells("A{$dateRow}:D{$dateRow}");
                    $sheet->getStyle("A{$dateRow}:D{$dateRow}")->applyFromArray([
                        'font' => ['bold' => true],
                    ]);
                }
            },
        ];
    }
}
