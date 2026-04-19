<?php

namespace App\Livewire\Components;

use App\Livewire\Forms\ProductForm;
use Livewire\Component;

class ProductFormAdd extends Component
{
    public ProductForm $productForm;

    public function save(): void
    {
        $this->productForm->store();

        $this->productForm->successMessage = 'Product added';

        $this->dispatch('add-edit-product-success');
    }

    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function cancel(): void
    {
        $this->productForm->cancel();
    }


    public function render()
    {
        return view('livewire.components.product-form-add');
    }
}
