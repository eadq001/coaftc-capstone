<?php

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Exports\TestResultsExport;
use Illuminate\Contracts\Console\Kernel;
use Maatwebsite\Excel\Facades\Excel;

$xmlPath = __DIR__.'/../storage/app/private/test-results.xml';

if (! file_exists($xmlPath)) {
    echo "ERROR: test-results.xml not found.\n";
    exit(1);
}

$xml = simplexml_load_string(file_get_contents($xmlPath));

$featureTests = [];
$unitTests = [];

function extractTests($node, string $parentName = '', string $parentFile = '', array &$feature = [], array &$unit = []): void
{
    foreach ($node->children() ?? [] as $child) {
        $name = $child->getName();

        if ($name === 'testcase') {
            $testName = (string) $child['name'];
            $testFile = (string) $child['file'] ?: $parentFile;
            $assertions = (string) $child['assertions'] ?: '0';
            $hasFailure = isset($child->failure) || isset($child->error);
            $status = $hasFailure ? 'FAIL' : 'PASS';

            $row = [$status, $testName, $testFile ?: $parentName, $assertions];

            if (str_contains($testFile, 'tests'.DIRECTORY_SEPARATOR.'Unit')) {
                $unit[] = $row;
            } else {
                $feature[] = $row;
            }
        } elseif ($name === 'testsuite') {
            $suiteName = (string) $child['name'];
            $suiteFile = (string) $child['file'] ?: $parentFile;
            extractTests($child, $suiteName ?: $parentName, $suiteFile ?: $parentFile, $feature, $unit);
        }
    }
}

extractTests($xml, 'root', '', $featureTests, $unitTests);

if (empty($featureTests) && empty($unitTests)) {
    echo "ERROR: No tests found in XML.\n";
    exit(1);
}

Excel::store(
    new TestResultsExport($featureTests, $unitTests),
    'test-report.xlsx'
);

$total = count($featureTests) + count($unitTests);
$passed = collect($featureTests)->where('0', 'PASS')->count() + collect($unitTests)->where('0', 'PASS')->count();
$failed = $total - $passed;

echo "Report generated: storage/app/private/test-report.xlsx\n";
echo "Total: {$total} | Passed: {$passed} | Failed: {$failed}\n";
echo 'Feature tests: '.count($featureTests).' | Unit tests: '.count($unitTests)."\n";
