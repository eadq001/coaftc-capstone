<?php

namespace App\Livewire\Components;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
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

    #[Validate('numeric|min:0.01')]
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
            'stockLevel' => 'numeric|min:0.01',
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
        $this->stockLevel = (float) $value;
        if ($this->stockLevel === 0.00 || $this->stockLevel < 0.01) {
            $this->reset('stockLevel');
        }

        $this->validate([
            'stockLevel' => 'numeric|min:0.01|required',
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
        $this->stockToAdd = (float) $value;

        if ($this->stockToAdd === 0.0 || $this->stockToAdd < 0.01) {
            $this->reset('stockToAdd');
        }

        $this->validate([
            'stockToAdd' => 'numeric|min:0.01|required',
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

    public function addStock(int $id): void
    {
        $this->validate([
            'stockToAdd' => 'min:0.01|required',
        ]);

        $this->productForm->addStock((float) $this->stockToAdd);
        $this->reset('stockToAdd');
        $this->dispatch('add-product-stock-success', $id);

    }

    #[On('add-product-stock-success')]
    public function stockLevelUpdate(int $id)
    {
        $product = Product::find($id);

        $this->stockLevel = $product->stock_level;
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
