<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TestResultSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    public function __construct(
        private readonly array $rows,
        private readonly string $title,
    ) {}

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return ['Status', 'Test Name', 'File', 'Assertions'];
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E5E7EB'],
                    ],
                ]);

                for ($row = 2; $row <= $highestRow; $row++) {
                    $status = $sheet->getCell("A{$row}")->getValue();

                    if ($status === 'PASS') {
                        $sheet->getStyle("A{$row}")->applyFromArray([
                            'font' => ['color' => ['rgb' => '059669']],
                        ]);
                    } elseif ($status === 'FAIL') {
                        $sheet->getStyle("A{$row}")->applyFromArray([
                            'font' => ['color' => ['rgb' => 'DC2626'], 'bold' => true],
                        ]);
                    }
                }
            },
        ];
    }
}
