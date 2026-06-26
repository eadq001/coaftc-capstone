<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TestResultsExport implements WithMultipleSheets
{
    public function __construct(
        private readonly array $featureTests,
        private readonly array $unitTests,
    ) {}

    public function sheets(): array
    {
        return [
            new TestResultSheet($this->featureTests, 'Feature Tests'),
            new TestResultSheet($this->unitTests, 'Unit Tests'),
        ];
    }
}
