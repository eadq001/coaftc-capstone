<?php

namespace App\Livewire\Components;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Session;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductFormEdit extends Component
{
    public ProductForm $productForm;

    public int $productToEdit;

    public $oldStockLevel = null;
    public $oldPrice = null;

    public array $oldValues = [];
    public array $newValues = [];

    public $stockLevel = null;

    public $price = null;

    #[Validate('min:1')]
    public $stockToAdd = null;

    public function mount(): void
    {

        $product = Product::find($this->productToEdit);
        $this->stockLevel = $product->stock_level;
        $this->price = $product->price;

        $this->oldStockLevel = $product->stock_level;
        $this->oldPrice = $product->price;


        $this->productForm->set($this->productToEdit);
    }

    public function update(): void
    {
        $this->validate([
            'stockLevel' => 'min:1',
            'price' => 'min:1'
        ]);

        $this->productForm->update($this->stockLevel, $this->price);
        $this->dispatch('add-edit-product-success');

        $this->reset('stockLevel', 'price', 'oldPrice', 'oldStockLevel');
    }

    public function updatedStockLevel($value)
    {
        $this->stockLevel = (int) $value;
        if (strlen($this->stockLevel) > 11 || $this->stockLevel < 1) {
            $this->reset('stockLevel');
        }

        $this->validate([
            'stockLevel' => 'int|min:1|required'
        ]);
    }

    public function updatedPrice($value)
    {
        $this->price = (int) $value;
        if (strlen($this->price) > 11 || $this->price < 1) {
            $this->reset('price');
        }

        $this->validate([
            'price' => 'int|min:1|required'
        ]);
    }

    public function updatedStockToAdd($value): void
    {
        $this->stockToAdd = (int) $value;

        if (strlen($this->stockToAdd) > 11 || $this->stockToAdd < 1) {
            $this->reset('stockToAdd');
        }

        $this->validate([
            'stockToAdd' => 'int|min:1|required'
        ]);
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


    public function addStock(): void
    {
        $this->validate([
            'stockToAdd' => 'min:1|required'
        ]);

        $this->productForm->addStock((int) $this->stockToAdd);
        $this->reset('stockToAdd');
        $this->dispatch('add-product-stock-success');

    }

    public function softDeleteProduct(int $productId): void
    {
        $product = Product::find($productId);

        if ($product->salesItem->count() !== 0){
            $this->dispatch('product-delete-error');
            return;
        }

        $product->delete();
        $this->dispatch('product-delete-success');
    }

    public function render()
    {
        return view('livewire.components.product-form-edit');
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
}
