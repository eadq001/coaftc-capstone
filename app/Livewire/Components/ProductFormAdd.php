<?php

namespace App\Livewire\Components;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
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

    #[On('add-edit-product-category-success')]
    public function clearCategoriesValue(): void
    {
        unset($this->categories);
    }

    #[Computed]
    public function categories()
    {
        return Category::get('category_name');
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
