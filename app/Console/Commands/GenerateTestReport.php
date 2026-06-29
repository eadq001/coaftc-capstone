<?php

namespace App\Console\Commands;

use App\Exports\TestResultsExport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class GenerateTestReport extends Command
{
    protected $signature = 'test:report';

    protected $description = 'Run tests and generate an Excel report with Feature and Unit test results separated into two sheets';

    public function handle(): int
    {
        $xmlPath = storage_path('app/private/test-results.xml');

        $this->info('Running all tests with JUnit XML output...');

        passthru('"'.PHP_BINARY.'" artisan test --compact --log-junit="'.$xmlPath.'" 2>&1', $exitCode);

        if (! File::exists($xmlPath)) {
            $this->error('Test results XML not found.');

            return self::FAILURE;
        }

        $this->info('Parsing test results...');

        $xmlContent = File::get($xmlPath);
        $xml = simplexml_load_string($xmlContent);

        if ($xml === false) {
            $this->error('Failed to parse XML.');

            return self::FAILURE;
        }

        $featureTests = [];
        $unitTests = [];

        $this->extractTests($xml, $featureTests, $unitTests);

        if (empty($featureTests) && empty($unitTests)) {
            $this->warn('No test cases found in XML via simplexml. Trying fallback...');

            $featureTests = [['INFO', 'Run php artisan test --compact first, then re-run this command', 'N/A', '-']];
            $unitTests = [['INFO', 'Run php artisan test --compact first, then re-run this command', 'N/A', '-']];
        }

        Excel::store(
            new TestResultsExport($featureTests, $unitTests),
            'test-report.xlsx'
        );

        $total = count($featureTests) + count($unitTests);
        $passed = collect($featureTests)->where('0', 'PASS')->count()
                + collect($unitTests)->where('0', 'PASS')->count();
        $failed = $total - $passed;

        $this->info('Report generated: storage/app/private/test-report.xlsx');
        $this->info("Total: {$total} tests, {$passed} passed, {$failed} failed, 2 sheets");
        $this->info('  - Feature Tests: '.count($featureTests));
        $this->info('  - Unit Tests:    '.count($unitTests));

        return self::SUCCESS;
    }

    private function extractTests(\SimpleXMLElement $node, array &$feature, array &$unit): void
    {
        foreach ($node->children() as $child) {
            if ($child->getName() === 'testcase') {
                $testName = (string) $child['name'];
                $testFile = (string) ($child['file'] ?? '');
                $assertions = (string) ($child['assertions'] ?? '0');
                $hasFailure = isset($child->failure) || isset($child->error);
                $status = $hasFailure ? 'FAIL' : 'PASS';

                $row = [$status, $testName, $testFile, $assertions];

                if (str_contains($testFile, 'tests'.DIRECTORY_SEPARATOR.'Unit')) {
                    $unit[] = $row;
                } else {
                    $feature[] = $row;
                }
            } elseif ($child->getName() === 'testsuite') {
                $this->extractTests($child, $feature, $unit);
            }
        }
    }
}
