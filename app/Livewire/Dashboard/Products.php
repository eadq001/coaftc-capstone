<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Dashboard;
use App\Models\Product;
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
    public $searchResults = [];

    #[Session]
    public bool $lowStockOnly = false;

    public int $productId;

    public function mount(): void
    {
        $this->totalProducts = Product::count('name');
        $this->lowStockItems = Product::where('stock_level', '<', 20)->count();
        $this->totalInventoryValue = Product::sum('price');
    }

    #[On('add-product-success')]
    public function refresh(): void
    {
        unset($this->products);

        $this->totalProducts = Product::count('name');
        $this->lowStockItems = Product::where('stock_level', '<', 20)->count();
        $this->totalInventoryValue = Product::sum('price');
    }

    #[Computed]
    public function products(): LengthAwarePaginator|array
    {
        if ($this->lowStockOnly) {
            $this->resetPage('products-page');
            return Product::where('stock_level', '<', 20)->paginate(5, pageName: 'products-page');
        }

        return Product::paginate(5, pageName: 'products-page');
    }

    public function updatedSearchText($value): void
    {
        $this->searchResults = Product::where('name', 'like', "$value%" )->get();
    }

    public function clearSearchText(): void
    {
        $this->reset('searchText');
    }

    public function toggleLowStockOnly(): void
    {
        $this->lowStockOnly = !$this->lowStockOnly;
    }

    public function cancel(): void
    {
        $this->reset('productId');
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.dashboard.products');
    }
}
