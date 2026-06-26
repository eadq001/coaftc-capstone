<?php

use App\Livewire\Dashboard\Archives\ArchiveProducts;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;

it('has computed products method', function () {
    $reflection = new ReflectionClass(ArchiveProducts::class);

    expect($reflection->hasMethod('products'))->toBeTrue();
});

it('has computed categories method', function () {
    $reflection = new ReflectionClass(ArchiveProducts::class);

    expect($reflection->hasMethod('categories'))->toBeTrue();
});

it('has computed subcategories method', function () {
    $reflection = new ReflectionClass(ArchiveProducts::class);

    expect($reflection->hasMethod('subcategories'))->toBeTrue();
});

it('has computed units method', function () {
    $reflection = new ReflectionClass(ArchiveProducts::class);

    expect($reflection->hasMethod('units'))->toBeTrue();
});

it('uses with pagination trait', function () {
    $reflection = new ReflectionClass(ArchiveProducts::class);

    expect($reflection->getTraitNames())->toContain('Livewire\WithPagination');
});

it('extends dashboard base class', function () {
    $reflection = new ReflectionClass(ArchiveProducts::class);

    expect($reflection->getParentClass()->getName())->toBe('App\Livewire\Dashboard');
});

it('product model uses soft deletes', function () {
    $reflection = new ReflectionClass(Product::class);

    expect($reflection->getTraitNames())->toContain('Illuminate\Database\Eloquent\SoftDeletes');
});

it('category model uses soft deletes', function () {
    $reflection = new ReflectionClass(Category::class);

    expect($reflection->getTraitNames())->toContain('Illuminate\Database\Eloquent\SoftDeletes');
});

it('subcategory model uses soft deletes', function () {
    $reflection = new ReflectionClass(Subcategory::class);

    expect($reflection->getTraitNames())->toContain('Illuminate\Database\Eloquent\SoftDeletes');
});

it('unit model uses soft deletes', function () {
    $reflection = new ReflectionClass(Unit::class);

    expect($reflection->getTraitNames())->toContain('Illuminate\Database\Eloquent\SoftDeletes');
});

it('category has fillable category_name', function () {
    $category = new Category;

    expect($category->getFillable())->toBe(['category_name']);
});

it('subcategory has fillable subcategory_name', function () {
    $subcategory = new Subcategory;

    expect($subcategory->getFillable())->toBe(['subcategory_name']);
});

it('unit has fillable unit_name', function () {
    $unit = new Unit;

    expect($unit->getFillable())->toBe(['unit_name']);
});
