<?php

namespace App\Livewire\Dashboard;

use App\Enums\EggSizes;
use App\Enums\ProductClass;
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

    public string $filterField = '';

    public string $filterValue = '';

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
    public function filterOptions(): array
    {
        return match ($this->filterField) {
            'price', 'stock_level' => [
                'highest' => 'Highest',
                'lowest' => 'Lowest',
            ],
            'unit_id' => Unit::query()
                ->orderBy('unit_name')
                ->pluck('unit_name', 'id')
                ->all(),
            'category_id' => Category::query()
                ->orderBy('category_name')
                ->pluck('category_name', 'id')
                ->all(),
            'subcategory_id' => Subcategory::query()
                ->orderBy('subcategory_name')
                ->pluck('subcategory_name', 'id')
                ->all(),
            'class' => collect(ProductClass::cases())
                ->mapWithKeys(fn (ProductClass $class): array => [$class->value => $class->value])
                ->all(),
            'size' => collect(EggSizes::cases())
                ->mapWithKeys(fn (EggSizes $size): array => [$size->value => $size->label()])
                ->all(),
            default => [],
        };
    }

    #[Computed]
    public function products(): LengthAwarePaginator|array
    {
        $query = Product::query()
            ->with([
                'category:id,category_name',
                'subcategory:id,subcategory_name',
                'unit:id,unit_name',
            ]);

        if ($this->searchText !== '') {
            $query->where('name', 'like', "$this->searchText%");
        }

        if ($this->lowStockOnly) {
            $query->where('stock_level', '<', 20);
        }

        if ($this->filterField !== '' && $this->filterValue !== '') {
            match ($this->filterField) {
                'price', 'stock_level' => $query->orderBy($this->filterField, $this->filterValue === 'highest' ? 'desc' : 'asc'),
                'unit_id', 'category_id', 'subcategory_id' => $query->where($this->filterField, (int) $this->filterValue),
                'class', 'size' => $query->where($this->filterField, $this->filterValue),
                default => null,
            };
        }

        return $query->latest()->paginate(5, pageName: 'products-table');
    }

    public function clearSearchText(): void
    {
        $this->reset('searchText');
    }

    public function clearFilters(): void
    {
        $this->reset('filterField', 'filterValue');
        $this->resetPage('products-table');
    }

    public function updatedFilterField(): void
    {
        $this->reset('filterValue');
        $this->resetPage('products-table');
    }

    public function updatedFilterValue(): void
    {
        $this->resetPage('products-table');
    }

    public function updatedSearchText(): void
    {
        $this->resetPage('products-table');
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
        sleep(1);
        $this->reset('productToEdit');
    }

    #[On('add-edit-product-category-success')]
    public function resetCategoryToEdit(): void
    {
        sleep(1);
        $this->reset('categoryToEdit');
    }

    #[On('add-edit-product-subcategory-success')]
    public function resetSubcategoryToEdit(): void
    {
        sleep(1);
        $this->reset('subcategoryToEdit');
    }

    #[On('add-edit-product-unit-success')]
    public function resetUnitToEdit(): void
    {
        sleep(1);
        $this->reset('unitToEdit');
    }
}
