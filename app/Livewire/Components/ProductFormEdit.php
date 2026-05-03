<?php

namespace App\Livewire\Components;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Unit;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ProductFormEdit extends Component
{
    public ProductForm $productForm;

    public int $productToEdit;

    public function mount(): void
    {
        $this->productForm->set($this->productToEdit);
    }

    public function update(): void
    {
        $this->productForm->update();
        $this->dispatch('add-edit-product-success');
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

    public function cancel()
    {
        $this->reset();
    }

    public function resetStockToAdd()
    {
        $this->productForm->resetStockToAdd();
    }

    public function addStock()
    {
        $this->productForm->addStock();
        $this->dispatch('add-product-stock-success');

    }

    public function render()
    {
        return view('livewire.components.product-form-edit');
    }
}
