<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Dashboard;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\WithPagination;

class Products extends Dashboard
{
    use WithPagination;

    public int $totalProducts;

    public int $lowStockItems;

    public int $totalInventoryValue;

    public string $searchText = '';

//    public $searchResults = [];

    #[Session]
    public bool $lowStockOnly = false;

    public int $productToEdit;

    public ?int $categoryToEdit = null;

    public int $subcategoryToEdit;

    public int $unitToEdit;

    public function refreshData(?string $action = null): void
    {
        if ($action) {
            unset($this->products);
        }

        $this->totalProducts = Product::count('name');
        $this->lowStockItems = Product::where('stock_level', '<', 20)->count();
        $this->totalInventoryValue = Product::sum('price');
    }

    #[Computed]
    public function categories()
    {
        return Category::paginate(9, ['id', 'category_name'], 'category-table');
    }

    #[Computed]
    public function subcategories()
    {
        return Subcategory::paginate(9, ['id', 'subcategory_name'], 'subcategory-table');
    }

    #[Computed]
    public function units()
    {
        return Unit::paginate(9, ['id', 'unit_name'], 'unit-table');
    }

    #[Computed]
    public function products(): LengthAwarePaginator|array
    {
        if ($this->searchText) {
            $this->resetPage('products-table');
            return Product::where('name', 'like', "$this->searchText%")->paginate(5, pageName: 'products-table');
        }

        elseif ($this->lowStockOnly) {
            $this->resetPage('products-page');

            return Product::where('stock_level', '<', 20)->paginate(5, pageName: 'products-table');
        }

        return Product::paginate(5, pageName: 'products-table');
    }

//    public function updatedSearchText($value): void
//    {
//        $this->searchResults = Product::where('name', 'like', "$value%")->limit(10)->get();
//    }

    public function clearSearchText(): void
    {
        $this->reset('searchText');
    }

    public function toggleLowStockOnly(): void
    {
        $this->lowStockOnly = ! $this->lowStockOnly;
        $this->resetPage('products-table');
    }

    public function cancel(): void
    {
        $this->reset('productToEdit', 'categoryToEdit', 'subcategoryToEdit', 'unitToEdit');
        $this->resetValidation();
    }

    #[On('add-edit-product-success')]
    public function resetProductToEdit(): void
    {
        $this->reset('productToEdit');
    }

    #[On('add-edit-product-category-success')]
    public function resetCategoryToEdit(): void
    {
        $this->reset('categoryToEdit');
    }

    #[On('add-edit-product-subcategory-success')]
    public function resetSubcategoryToEdit(): void
    {
        $this->reset('subcategoryToEdit');
    }

    #[On('add-edit-product-unit-success')]
    public function resetUnitToEdit(): void
    {
        $this->reset('unitToEdit');
    }

}
