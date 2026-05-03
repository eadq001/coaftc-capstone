<?php

namespace App\Livewire\Components;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use App\Models\Subcategory;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductFormAdd extends Component
{
    public ProductForm $productForm;

    public function save(): void
    {
        $this->productForm->store();

        $this->productForm->successMessage = 'Product added';

        $this->dispatch('add-edit-product-success');

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
        return \App\Models\Unit::all();
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
