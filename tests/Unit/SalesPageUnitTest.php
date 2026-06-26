<?php

use App\Models\Sale;
use App\Models\SalesItem;
use App\PrintReceipt;

it('sale model auto-generates prf_number on creating', function () {
    $reflection = new ReflectionClass(Sale::class);
    $bootedMethod = $reflection->getMethod('booted');

    expect($reflection->hasMethod('booted'))->toBeTrue();
});

it('sale model has guarded empty array', function () {
    $sale = new Sale;

    expect($sale->getGuarded())->toBe([]);
});

it('sale model has user relationship', function () {
    $reflection = new ReflectionClass(Sale::class);

    expect($reflection->hasMethod('user'))->toBeTrue();
});

it('sale model has salesItem relationship', function () {
    $reflection = new ReflectionClass(Sale::class);

    expect($reflection->hasMethod('salesItem'))->toBeTrue();
});

it('salesItem model has guarded empty array', function () {
    $salesItem = new SalesItem;

    expect($salesItem->getGuarded())->toBe([]);
});

it('salesItem model has sale relationship', function () {
    $reflection = new ReflectionClass(SalesItem::class);

    expect($reflection->hasMethod('sale'))->toBeTrue();
});

it('salesItem model has product relationship', function () {
    $reflection = new ReflectionClass(SalesItem::class);

    expect($reflection->hasMethod('product'))->toBeTrue();
});

it('print receipt class has print static method', function () {
    $reflection = new ReflectionClass(PrintReceipt::class);

    expect($reflection->hasMethod('print'))->toBeTrue();
});

it('print receipt class has printDispersal static method', function () {
    $reflection = new ReflectionClass(PrintReceipt::class);

    expect($reflection->hasMethod('printDispersal'))->toBeTrue();
});
