<?php

namespace App\Exports\Concerns;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait ReportHeaderLayout
{
    abstract protected function titlePeriod(): string;

    /**
     * @return array{0: string, 1: string}
     */
    abstract protected function titlePeriodValues(): array;

    protected function applyHeader(Worksheet $sheet, string $end = 'U'): void
    {
        [$year, $period] = $this->titlePeriodValues();

        $title = match ($this->titlePeriod()) {
            'daily' => "COAFTC TRUST FUND MONITORING DAILY {$year}",
            'monthly' => "COAFTC TRUST FUND MONITORING MONTHLY {$period}",
            'yearly' => "COAFTC TRUST FUND MONITORING YEARLY {$year}",
            default => "COAFTC TRUST FUND MONITORING {$year}",
        };

        $sheet->mergeCells("A1:{$end}1");
        $sheet->mergeCells("A2:{$end}2");
        $sheet->mergeCells("A3:{$end}3");
        $sheet->mergeCells("A5:{$end}5");
        $sheet->mergeCells("A6:{$end}6");
        $sheet->mergeCells("A8:{$end}8");

        $sheet->setCellValue('A1', 'Republic of the Philippines');
        $sheet->setCellValue('A2', 'Province of Surigao del Sur');
        $sheet->setCellValue('A3', 'BISLIG CITY');
        $sheet->setCellValue('A5', 'CITY AGRICULTURE OFFICE');
        $sheet->setCellValue('A6', 'City Organic Agri-Fishery Tourism Complex');
        $sheet->setCellValue('A8', $title);

        $sheet->getStyle("A1:{$end}8")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'font' => [
                'name' => 'Arial Narrow',
            ],
        ]);

        $sheet->getStyle('A1:A2')->getFont()->setSize(11)->setBold(false);
        $sheet->getStyle('A3')->getFont()->setSize(12)->setBold(true);
        $sheet->getStyle('A5:A6')->getFont()->setSize(12)->setBold(true);
        $sheet->getStyle('A8')->getFont()->setSize(16)->setBold(true);

        $this->addLogos($sheet, $end);

        $sheet->getRowDimension(4)->setRowHeight(8);
        $sheet->getRowDimension(7)->setRowHeight(8);

    }

    private function addLogos(Worksheet $sheet, string $end): void
    {
        $leftLogo = new Drawing;
        $leftLogo->setPath(public_path('images/bislig_seal.png'));
        $leftLogo->setName('Bislig City Seal');
        $leftLogo->setCoordinates('A1');
        $leftLogo->setWidth(109);
        $leftLogo->setHeight(109);
        $leftLogo->setOffsetX(22);
        $leftLogo->setOffsetY(11);
        $leftLogo->setWorksheet($sheet);

        $rightLogo = new Drawing;
        $rightLogo->setPath(public_path('images/coaftc.png'));
        $rightLogo->setName('COAFTC Logo');
        $rightLogo->setCoordinates($end.'1');
        $rightLogo->setWidth(101);
        $rightLogo->setHeight(99);
        $rightLogo->setOffsetX(-22);
        $rightLogo->setOffsetY(16);
        $rightLogo->setWorksheet($sheet);
    }
}
