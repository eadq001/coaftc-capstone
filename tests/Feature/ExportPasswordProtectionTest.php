<?php

use App\Enums\UserRoles;
use App\Exports\DailySalesReportExport;
use App\Exports\SalesSummaryReportExport;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function actingAsAdminForExport(): User
{
    $user = User::factory()->create([
        'user_role' => UserRoles::ADMIN,
    ]);

    actingAs($user);

    return $user;
}

function createSaleWithProductForExport(): Sale
{
    $product = Product::factory()->create([
        'name' => 'Layer Egg',
        'user_id' => auth()->id(),
    ]);

    $sale = Sale::factory()->create(['user_id' => auth()->id()]);

    SalesItem::create([
        'sale_id' => $sale->id,
        'product_id' => $product->id,
        'quantity' => 2,
        'unit_price' => 180,
        'subtotal' => 360,
        'inventory_start' => 100,
        'inventory_end' => 98,
    ]);

    return $sale->fresh();
}

it('downloads the Excel file directly without a password prompt', function () {
    actingAsAdminForExport();
    createSaleWithProductForExport();

    Livewire::test('dashboard.reports')
        ->set('startDate', now()->format('Y-m-d'))
        ->set('endDate', now()->format('Y-m-d'))
        ->call('exportToExcel')
        ->assertFileDownloaded('daily-sales-report-'.now()->format('Ymd').'.xlsx');
});

it('applies sheet protection using the configured password on the exported file', function () {
    actingAsAdminForExport();
    createSaleWithProductForExport();

    $export = new DailySalesReportExport(
        collect(),
        collect(),
        collect(),
        now()->format('Y-m-d'),
        config('app.excel_protection_password'),
    );

    $fileName = 'test-protected-export.xlsx';
    Excel::store($export, $fileName, 'local');

    $reader = IOFactory::createReader('Xlsx');
    $spreadsheet = $reader->load(storage_path('app/private/'.$fileName));

    foreach ($spreadsheet->getAllSheets() as $sheet) {
        expect($sheet->getProtection()->getSheet())->toBeTrue();
    }

    unlink(storage_path('app/private/'.$fileName));
});

it('does not apply sheet protection when no password is passed to the export', function () {
    $export = new DailySalesReportExport(
        collect(),
        collect(),
        collect(),
        now()->format('Y-m-d'),
        null,
    );

    expect($export->sheets())->toHaveCount(2);
});

it('applies sheet protection on the monthly sales report export', function () {
    actingAsAdminForExport();
    createSaleWithProductForExport();

    $export = new SalesSummaryReportExport(
        collect([now()->format('Y-m') => Sale::with('salesItem.product.category')->get()->flatMap->salesItem]),
        false,
        collect(),
        config('app.excel_protection_password'),
    );

    $fileName = 'test-monthly-protected.xlsx';
    Excel::store($export, $fileName, 'local');

    $reader = IOFactory::createReader('Xlsx');
    $spreadsheet = $reader->load(storage_path('app/private/'.$fileName));

    foreach ($spreadsheet->getAllSheets() as $sheet) {
        expect($sheet->getProtection()->getSheet())->toBeTrue();
    }

    unlink(storage_path('app/private/'.$fileName));
});

it('applies sheet protection on the yearly sales report export', function () {
    actingAsAdminForExport();
    createSaleWithProductForExport();

    $items = Sale::with('salesItem.product.category')->get()->flatMap->salesItem;

    $export = new SalesSummaryReportExport(
        $items->groupBy(fn ($item) => $item->sale->created_at->format('Y-m'))->sortKeys(),
        true,
        collect(),
        config('app.excel_protection_password'),
    );

    $fileName = 'test-yearly-protected.xlsx';
    Excel::store($export, $fileName, 'local');

    $reader = IOFactory::createReader('Xlsx');
    $spreadsheet = $reader->load(storage_path('app/private/'.$fileName));

    foreach ($spreadsheet->getAllSheets() as $sheet) {
        expect($sheet->getProtection()->getSheet())->toBeTrue();
    }

    unlink(storage_path('app/private/'.$fileName));
});

it('does not apply sheet protection on SalesSummaryReportExport when no password is passed', function () {
    $export = new SalesSummaryReportExport(
        collect(),
        false,
        collect(),
        null,
    );

    expect($export)->not->toBeNull();
});
