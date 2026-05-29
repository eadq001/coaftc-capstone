<?php

namespace App\Livewire\Dashboard\Archives;

use App\Livewire\Dashboard;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class ArchiveProducts extends Dashboard
{
    use WithPagination;

    #[Computed]
    public function products()
    {
        return Product::onlyTrashed()->paginate(3, pageName: 'archived-products');
    }

    public function restoreProduct(int $id)
    {
        $product = Product::withTrashed()->where('id', '=', $id);
        $product->restore();
        $this->dispatch('restore-success');
    }

    public function render()
    {
        return view('livewire.dashboard.archives.archive-products');
    }
}
