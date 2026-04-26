<?php

namespace App\Livewire\Components;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use App\Models\Subcategory;
use Livewire\Attributes\Computed;
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

    #[Computed]
    public function categories()
    {
        return Category::get('category_name');
    }

    #[Computed]
    public function subcategories()
    {
        return Subcategory::get('subcategory_name');
    }

    public function render()
    {
        return view('livewire.components.product-form-edit');
    }
}
