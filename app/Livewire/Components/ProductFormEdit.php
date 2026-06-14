<?php

namespace App\Livewire\Components;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductFormEdit extends Component
{
    public ProductForm $productForm;

    public int $productToEdit;

    // array of values for comparing the live changes in edit form
    public array $oldValues = [];

    public array $liveValues = [];

    public array $formValuesChangeState = [];

    public $stockLevel = null;

    public $price = null;

    public bool $isFormValuesChange = false;

    #[Validate('min:1')]
    public $stockToAdd = null;

    public function mount(): void
    {

        $product = Product::find($this->productToEdit);
        $this->stockLevel = $product->stock_level;
        $this->price = $product->price;

        $this->productForm->set($this->productToEdit);

        $this->oldValues = [
            'productForm.name' => $product->name,
            'stockLevel' => (string) $product->stock_level,
            'price' => (string) $product->price,
            'productForm.unit_id' => (string) $product->unit_id,
            'productForm.category_id' => (string) $product->category_id,
            'productForm.subcategory_id' => (string) $product->subcategory_id,
            'productForm.size' => $product->size ?? '',
            'productForm.class' => $product->class ?? '',
        ];

    }

    public function update(): void
    {
        $this->validate([
            'stockLevel' => 'min:1',
            'price' => 'min:1',
        ]);

        $this->productForm->update($this->stockLevel, $this->price);
        $this->dispatch('add-edit-product-success');

        $this->reset('stockLevel', 'price', 'oldValues', 'liveValues');
    }

    // track live changes of the edit form
    public function updated($property, $value): void
    {
        $this->liveValues[$property] = (string) $value;

        foreach ($this->liveValues as $liveValue) {
            if (array_key_exists($property, $this->oldValues)) {
                if ($this->oldValues[$property] === $value) {
                    $this->formValuesChangeState[$property] = 'false';
                } else {
                    $this->formValuesChangeState[$property] = 'true';
                }
            }
        }

        if (in_array('true', $this->formValuesChangeState)) {
            $this->isFormValuesChange = true;
        } else {
            $this->isFormValuesChange = false;
        }

    }

    public function updatedStockLevel($value)
    {
        //        $this->stockLevel = (int) $value;
        if (strlen($this->stockLevel) > 11 || (int) $this->stockLevel < 1) {
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

    public function updatedStockToAdd($value): void
    {
        $this->stockToAdd = (int) $value;

        if (strlen($this->stockToAdd) > 11 || $this->stockToAdd < 1) {
            $this->reset('stockToAdd');
        }

        $this->validate([
            'stockToAdd' => 'int|min:1|required',
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
            'stockToAdd' => 'min:1|required',
        ]);

        $this->productForm->addStock((int) $this->stockToAdd);
        $this->reset('stockToAdd');
        $this->dispatch('add-product-stock-success');

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
