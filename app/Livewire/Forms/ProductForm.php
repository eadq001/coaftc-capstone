<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductForm extends Form
{
    #[Locked]
    public ?int $id = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|int|min:1')]
    public ?int $stock_level;

    #[Validate('required|string|max:50')]
    public string $unit = '';

    #[Validate('required|numeric|min:0')]
    public float|string $price = '';

    #[Validate('required|string|max:100')]
    public string $category = '';

    #[Validate('required|string|max:100')]
    public string $subcategory = '';

    public ?Product $product;

    public string $successMessage = '';

    public function store(): void
    {
        Product::create($this->validate());
        $this->reset();
    }

    public function set(int $productToEdit): void
    {
        $product = Product::find($productToEdit);
        $this->id = $product->id;
        $this->name = $product->name;
        $this->stock_level = $product->stock_level;
        $this->unit = $product->unit;
        $this->price = $product->price;
        $this->category = $product->category;
        $this->subcategory = $product->subcategory;
        $this->product = $product;

    }

    public function update(): void
    {
        $this->product->update($this->validate());
        $this->reset(['name', 'stock_level', 'unit', 'price', 'category', 'subcategory', 'product']);
    }

    public function cancel(): void
    {
        $this->reset();
        $this->resetValidation();
    }

    public function updated($property): void
    {
        $this->validateOnly($property);
    }
}
