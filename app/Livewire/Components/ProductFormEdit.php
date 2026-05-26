<?php

namespace App\Livewire\Components;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Unit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductFormEdit extends Component
{
    public ProductForm $productForm;

    public int $productToEdit;

    #[Validate('min:1')]
    public $stockToAdd = null;

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

    public function cancel(): void
    {
        $this->reset();
    }

    public function resetStockToAdd(): void
    {
        $this->reset('stockToAdd');
        $this->resetValidation();
//        $this->productForm->resetStockToAdd();
    }

    public function updatedStockToAdd($value): void
    {
        if (strlen($value) > 10 || ((int)$value) < 1 ) {
            $this->addError('stockToAdd', 'the minimum value to add a stock is 1');
            $this->reset('stockToAdd');
        }
    }

    public function addStock(): void
    {
        $this->productForm->addStock((int) $this->stockToAdd);
        $this->reset('stockToAdd');
        $this->dispatch('add-product-stock-success');

    }

    public function render()
    {
        return view('livewire.components.product-form-edit');
    }
}
