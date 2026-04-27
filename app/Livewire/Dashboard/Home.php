<?php

namespace App\Livewire\Dashboard;

use App\Models\Product;
use Livewire\Component;

class Home extends Component
{
    public string $username;
    public int $totalProducts;
    public int $lowStockItems;
    public int $totalInventoryValue;

    public function mount(): void
    {
        $this->totalProducts = Product::count('name');
        $this->lowStockItems = Product::where('stock_level', '<', 20)->count();
        $this->totalInventoryValue = Product::sum('price');
        $this->username = auth()->user()->name;
    }


    public function render()
    {
        return view('livewire.dashboard.home')->layout('layouts.dashboard');
    }
}
