<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DailySalesReportExport implements WithMultipleSheets
{
    public function __construct(
        private readonly Collection $itemsByDate,
        private readonly Collection $volumeProduced,
        private readonly Collection $volumeSold,
        private readonly ?string $reportDate = null,
        private readonly ?string $password = null,
    ) {}

    /**
     * @return array<int, object>
     */
    public function sheets(): array
    {
        return [
            new DailySalesSheet($this->itemsByDate, $this->reportDate, $this->password),
            new VolumeSheet($this->volumeProduced, $this->volumeSold, $this->itemsByDate, $this->reportDate, $this->password),
        ];
    }
}
