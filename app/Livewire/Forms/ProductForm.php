<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductForm extends Form
{
    public ?int $id = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|int|min:1')]
    public ?int $stock_level;

    #[Validate('required|integer|min:1')]
    public ?float $price = null;

    #[Validate('required')]
    public string $unit_id = '';

    #[Validate('required|exists:categories,id')]
    public string $category_id = '';

    #[Validate('required|exists:subcategories,id')]
    public string $subcategory_id = '';

    #[Validate('nullable|string|max:100')]
    public string $size = '';

    #[Validate('nullable|string|max:100')]
    public string $class = '';

    public ?Product $product;

    public string $successMessage = '';

    public function store(): void
    {
        $validated = $this->validate();

        //cast the string to int
        $validated['unit_id'] = (int) $validated['unit_id'];
        $validated['category_id'] = (int) $validated['category_id'];
        $validated['subcategory_id'] = (int) $validated['subcategory_id'];

        Product::create($validated);
        $this->reset();
    }

    public function set(int $productToEdit): void
    {
        $product = Product::find($productToEdit);
        $this->id = $product->id;
        $this->name = $product->name;
        $this->stock_level = $product->stock_level;
        $this->unit_id = $product->unit_id;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->subcategory_id = $product->subcategory_id;
        $this->size = $product->size ?? '';
        $this->class = $product->class->value ?? '';
        $this->product = $product;

    }

    public function update(): void
    {
        $validated = $this->validate();

        //cast the string to int
        $validated['unit_id'] = (int) $validated['unit_id'];
        $validated['category_id'] = (int) $validated['category_id'];
        $validated['subcategory_id'] = (int) $validated['subcategory_id'];

        $this->product->update($this->validate());

        $this->reset(['name', 'stock_level', 'unit_id', 'price', 'category_id', 'subcategory_id', 'product', 'size', 'class']);
    }

    public function cancel(): void
    {
        $this->reset();
        $this->resetValidation();
    }

}
