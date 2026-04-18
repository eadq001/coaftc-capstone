<?php

namespace App\Livewire\Components;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductFormEdit extends Component
{
    public int $productId;


    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|int|min:1')]
    public int $stock_level;

    #[Validate('required|string|max:50')]
    public string $unit = '';

    #[Validate('required|numeric|min:0')]
    public float|string $price = '';

    #[Validate('required|string|max:100')]
    public string $category = '';

    #[Validate('required|string|max:100')]
    public string $subcategory = '';

    public string $successMessage = '';

    public function mount(): void
    {
        $product = Product::find($this->productId);

        $this->name = $product->name;
        $this->price = $product->price;
    }


    public function render()
    {
        return view('livewire.components.product-form-edit');
    }
}
