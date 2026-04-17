<?php

namespace App\Livewire\Components;

use App\Models\Product;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductFormAdd extends Component
{
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

    public function save(): void
    {
        $validated = $this->validate();

        Product::create($validated);

        $this->reset();

        $this->successMessage = 'Product added';

        $this->dispatch('add-product-success');
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

    public function render()
    {
        return view('livewire.components.product-form-add');
    }
}
