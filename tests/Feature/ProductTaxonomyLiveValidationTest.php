<?php

use App\Models\Subcategory;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('validates duplicate units while typing', function () {
    Unit::create(['unit_name' => 'Tray']);

    Livewire::test('dashboard.forms.product-unit-form-add')
        ->set('unit_name', 'Tray')
        ->assertHasErrors(['unit_name']);
});

it('validates duplicate subcategories while typing', function () {
    Subcategory::create(['subcategory_name' => 'Brown Eggs']);

    Livewire::test('dashboard.forms.product-subcategory-form-add')
        ->set('subcategory_name', 'Brown Eggs')
        ->assertHasErrors(['subcategory_name']);
});
