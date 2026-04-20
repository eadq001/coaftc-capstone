<?php

namespace App\Livewire\Components;

use App\Livewire\Forms\ProductForm;
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

    public function render()
    {
        return view('livewire.components.product-form-edit');
    }
}
