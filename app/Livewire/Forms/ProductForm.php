<?php

namespace App\Livewire\Forms;

use App\Models\ActivityLog;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductForm extends Form
{
    public ?int $id = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

//    #[Validate('required|min:1')]
    public $stock_level = null;

//    #[Validate('required|integer|min:1')]
    public $price = null;

    #[Validate('required')]
    public string $unit_id = '';

    #[Validate('required|exists:categories,id')]
    public string $category_id = '';

    #[Validate('required|exists:subcategories,id')]
    public string $subcategory_id = '';

    #[Validate('nullable|string|max:100')]
    public string $size = '';

    #[Validate('nullable|string|max:100')]
    public ?string $class = '';

    public ?Product $product;

    public string $successMessage = '';

    public function store(int $stockLevel, int $price): void
    {
        $validated = $this->validate();

        // cast the string to int
        $validated['unit_id'] = (int) $validated['unit_id'];
        $validated['category_id'] = (int) $validated['category_id'];
        $validated['user_id'] = auth()->user()->id;
        $validated['subcategory_id'] = (int) $validated['subcategory_id'];

        //add the stockLevel and price value from productFormAdd
        $validated['stock_level'] = $stockLevel;
        $validated['price'] = $price;

        if (! $validated['class']) {
            $validated['class'] = null;
        }

        DB::transaction(function () use($validated) {

            Product::create($validated);
            ActivityLog::create([
                'user_id' => auth()->user()->id,
                'action' => 'create',
                'model' => 'product',
                'new_values' => $validated

            ]);
        });

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

    public function update(int $stockLevel, int $price): void
    {
        $validated = $this->validate();

        // cast the string to int
        $validated['unit_id'] = (int) $validated['unit_id'];
        $validated['category_id'] = (int) $validated['category_id'];
        $validated['subcategory_id'] = (int) $validated['subcategory_id'];
        $validated['user_id'] = auth()->user()->id;
        $validated['class'] = $validated['class'] ?: null;

        //add the stockLevel and price value from productFormAdd
        $validated['stock_level'] = $stockLevel;
        $validated['price'] = $price;

        //model update method
        $this->product->update($validated);

        $this->reset(['name', 'stock_level', 'unit_id', 'price', 'category_id', 'subcategory_id', 'product', 'size', 'class']);
    }

    public function addStock(int $stockLevel): void
    {
        $this->product->increment('stock_level', $stockLevel);
        $this->product->update(['user_id' => auth()->user()->id]);
        $this->stock_level = $this->product->stock_level;
        $this->resetStockToAdd();
    }

    public function resetStockToAdd()
    {
        $this->reset('stockToAdd');
    }

    public function cancel(): void
    {
        $this->reset();
        $this->resetValidation();
    }
}
