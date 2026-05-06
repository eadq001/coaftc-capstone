<?php

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Layout('layouts.dashboard')]
class extends Component {

    public array $currentItem = [];

    #[On('show-data')]
    public function showData()
    {
        dump($this->currentItem);
}
}
?>

<div>
    <div class="relative bg-white p-4 w-96 rounded-lg">
        <form wire:submit="save" class="space-y-3 text-sm ">
            <div class="absolute top-0 right-0 p-2" title="exit this form">
                <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all" wire:click=""
                                  @click="showSubcategoryForm=false"/>
            </div>
            <p class="text-center">Sales Form</p>
            <flux:field>
                <flux:label class="mb-0.5!">Product Name</flux:label>
                <flux:input type="text" wire:model="subcategory_name" placeholder="Product Name" readonly/>
                <flux:error name="subcategory_name"/>
            </flux:field>

            <flux:field>
                <flux:label class="mb-0.5!">Price</flux:label>
                <flux:input type="text" wire:model="Price" placeholder="Product Name" readonly/>
                <flux:error name="subcategory_name"/>
            </flux:field>

            <flux:field>
                <flux:label class="mb-0.5!">Quantity</flux:label>
                <flux:input type="text" wire:model="Quantity" placeholder="Product Name" id="quantity"/>
                <flux:error name="subcategory_name"/>
            </flux:field>

            <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">

                <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all"
                        type="submit">Add Sale
                </button>

            </div>
        </form>
    </div>
</div>