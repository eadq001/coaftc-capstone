<?php

namespace App\Livewire\Dashboard\Archives;

use App\Livewire\Dashboard;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class ArchiveProducts extends Dashboard
{
    use WithPagination;

    #[Computed]
    public function products()
    {
        return Product::onlyTrashed()->paginate(10, pageName: 'archived-products');
    }

    #[Computed]
    public function categories()
    {
        return Category::onlyTrashed()->paginate(6, pageName: 'archived-categories');
    }

    #[Computed]
    public function subcategories()
    {
        return Subcategory::onlyTrashed()->paginate(6, pageName: 'archived-subcategories');
    }

    #[Computed]
    public function units()
    {
        return Unit::onlyTrashed()->paginate(6, pageName: 'archived-units');
    }

    public function render()
    {
        return view('livewire.dashboard.archives.archive-products');
    }
}
