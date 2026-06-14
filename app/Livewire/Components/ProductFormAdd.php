<?php

namespace App\Livewire\Components;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Unit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductFormAdd extends Component
{
    public ProductForm $productForm;

    #[Validate('required|min:1')]
    public $stockLevel = null;

    #[Validate('required|min:1')]
    public $price = null;

    public function save(): void
    {
        $this->validate();

        $this->productForm->store($this->stockLevel, $this->price);

        $this->productForm->successMessage = 'Product added';

        $this->dispatch('add-edit-product-success');

        $this->reset('stockLevel', 'price');

    }

    public function updatedStockLevel($value)
    {
        $this->stockLevel = (int) $value;
        if (strlen($this->stockLevel) > 11 || $this->stockLevel < 1) {
            $this->reset('stockLevel');
        }

        $this->validate([
            'stockLevel' => 'int|min:1|required',
        ]);
    }

    public function updatedPrice($value)
    {
        $this->price = (int) $value;
        if (strlen($this->price) > 11 || $this->price < 1) {
            $this->reset('price');
        }

        $this->validate([
            'price' => 'int|min:1|required',
        ]);
    }

    #[On('add-edit-product-category-success')]
    public function clearCategoriesValue(): void
    {
        unset($this->categories);
    }

    #[On('add-edit-product-subcategory-success')]
    public function clearSubcategoriesValue(): void
    {
        unset($this->subcategories);
    }

    #[On('add-edit-product-unit-success')]
    public function clearUnitsValue(): void
    {
        unset($this->units);
    }

    #[Computed]
    public function categories()
    {
        return Category::all();
    }

    #[Computed]
    public function subcategories()
    {
        return Subcategory::all();
    }

    #[Computed]
    public function units()
    {
        return Unit::all();
    }

    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function cancel(): void
    {
        $this->productForm->cancel();
    }

    public function render()
    {
        return view('livewire.components.product-form-add');
    }
}
