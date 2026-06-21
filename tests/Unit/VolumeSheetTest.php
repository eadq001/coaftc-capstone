<?php

use App\Exports\VolumeSheet;

it('places a Unit column before the Sales column and shows each product unit', function () {
    $volumeProduced = collect([
        '2025-01-01' => collect([
            ['product_id' => 1, 'product_name' => 'Layer Egg', 'quantity_added' => 100, 'unit_name' => 'Tray'],
        ]),
    ]);

    $volumeSold = collect([
        '2025-01-01' => collect([
            ['product_id' => 1, 'product_name' => 'Layer Egg', 'quantity_sold' => 40, 'total_sales' => 1200, 'unit_name' => 'Tray'],
            ['product_id' => 2, 'product_name' => 'Brown Egg', 'quantity_sold' => 10, 'total_sales' => 350, 'unit_name' => 'Piece'],
        ]),
    ]);

    $rows = (new VolumeSheet($volumeProduced, $volumeSold, collect(), '2025-01-01'))->array();

    $header = $rows[0];
    expect($header)->toBe(['Date', 'Product Name', 'Volume Produced', 'Volume Sold', 'Unit', 'Sales'])
        ->and(array_search('Unit', $header))->toBeLessThan(array_search('Sales', $header));

    $productRows = array_slice($rows, 2);
    $byName = collect($productRows)->keyBy(fn ($row) => $row[1]);

    expect($byName->get('Layer Egg')[4])->toBe('Tray')
        ->and($byName->get('Brown Egg')[4])->toBe('Piece')
        ->and($byName->get('Layer Egg')[5])->toBe(1200);
});

it('falls back to the produced unit when a product was not sold on a date', function () {
    $volumeProduced = collect([
        '2025-01-01' => collect([
            ['product_id' => 1, 'product_name' => 'Native Egg', 'quantity_added' => 50, 'unit_name' => 'Dozen'],
        ]),
    ]);

    $rows = (new VolumeSheet($volumeProduced, collect(), collect(), '2025-01-01'))->array();

    $productRows = array_slice($rows, 2);
    $native = collect($productRows)->first(fn ($row) => $row[1] === 'Native Egg');

    expect($native[4])->toBe('Dozen')
        ->and($native[5])->toBe(0);
});

it('keeps separate rows for products with the same name but different ids', function () {
    $volumeProduced = collect([
        '2025-01-01' => collect([
            ['product_id' => 2, 'product_name' => 'Bell Pepper', 'quantity_added' => 2, 'unit_name' => 'kg'],
            ['product_id' => 3, 'product_name' => 'Bell Pepper', 'quantity_added' => 3, 'unit_name' => 'kg'],
        ]),
    ]);

    $rows = (new VolumeSheet($volumeProduced, collect(), collect(), '2025-01-01'))->array();

    $productRows = array_slice($rows, 2);
    $bellPepperRows = collect($productRows)->filter(fn ($row) => $row[1] === 'Bell Pepper');

    expect($bellPepperRows)->toHaveCount(2);

    $quantities = $bellPepperRows->pluck(2)->sort()->values();

    expect($quantities->all())->toBe([2, 3]);
});
