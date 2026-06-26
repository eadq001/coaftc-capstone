<?php

use App\Livewire\Dashboard\Products;

it('has searchText property', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasProperty('searchText'))->toBeTrue();
});

it('has filterField and filterValue properties', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasProperty('filterField'))->toBeTrue()
        ->and($reflection->hasProperty('filterValue'))->toBeTrue();
});

it('has lowStockOnly property', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasProperty('lowStockOnly'))->toBeTrue();
});

it('has refreshData method', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasMethod('refreshData'))->toBeTrue();
});

it('has computed products method', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasMethod('products'))->toBeTrue();
});

it('has computed categories method', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasMethod('categories'))->toBeTrue();
});

it('has computed subcategories method', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasMethod('subcategories'))->toBeTrue();
});

it('has computed units method', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasMethod('units'))->toBeTrue();
});

it('has computed filterOptions method', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasMethod('filterOptions'))->toBeTrue();
});

it('has clearSearchText method', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasMethod('clearSearchText'))->toBeTrue();
});

it('has clearFilters method', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasMethod('clearFilters'))->toBeTrue();
});

it('has toggleLowStockOnly method', function () {
    $reflection = new ReflectionClass(Products::class);

    expect($reflection->hasMethod('toggleLowStockOnly'))->toBeTrue();
});

it('filterOptions returns price options for price field', function () {
    $products = new Products;
    $products->filterField = 'price';

    $options = $products->filterOptions();

    expect($options)->toBe([
        'highest' => 'Highest',
        'lowest' => 'Lowest',
    ]);
});

it('filterOptions returns price options for stock_level field', function () {
    $products = new Products;
    $products->filterField = 'stock_level';

    $options = $products->filterOptions();

    expect($options)->toBe([
        'highest' => 'Highest',
        'lowest' => 'Lowest',
    ]);
});

it('filterOptions returns empty array for unknown field', function () {
    $products = new Products;
    $products->filterField = 'unknown';

    $options = $products->filterOptions();

    expect($options)->toBe([]);
});

it('filterOptions returns class enum values for class field', function () {
    $products = new Products;
    $products->filterField = 'class';

    $options = $products->filterOptions();

    expect($options)->toBe([
        'A' => 'A',
        'B' => 'B',
    ]);
});
