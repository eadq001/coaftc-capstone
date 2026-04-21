<?php

use App\Models\Category;
use Livewire\Component;

new class extends Component {
    public string $category_name = '';

    public string $successMessage = '';

    public int $categoryToEdit;

    public Category $categoryToEditModel;

    public function mount()
    {
        $this->categoryToEditModel = Category::find($this->categoryToEdit);
        $this->category_name = $this->categoryToEditModel->category_name;
    }

    public function update(): void
    {
        $validated = $this->validate([
            'category_name' => 'required|min:5|string',
        ]);

        $this->categoryToEditModel->update($validated);
        $this->reset('category_name');
//        $this->js('setTimeout()=>Livewire.dispatch("add-edit-product-category-success"), 0)');
        $this->successMessage = 'Category Successfully updated';
        $this->dispatch('add-edit-product-category-success');
    }
};
?>
<div class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 backdrop-blur-xs">
    <div class="relative bg-white p-4 w-2xl rounded-lg" wire:transition wire:cloak>
        <form wire:submit="update" class="space-y-3 text-sm" x-data="{active:false}">
            <div class="absolute top-0 right-0 p-2" title="exit this form">
                <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all" wire:click="$parent.cancel"
                                  @click="showCategoryForm=false"/>
            </div>
            <p class="text-center">Add Product Category</p>
            <flux:field>
                <flux:label class="mb-0.5!">Category Name</flux:label>
                <flux:input type="text" wire:model="category_name" placeholder="Category Name" x-bind:readonly="!active"/>
                <flux:error name="category_name"/>
            </flux:field>

            <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">

                <button class="bg-gray-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all"
                        x-bind:class = "active ? 'block' : 'hidden'"
                        wire:dirty.class="bg-green-300"
                        type="submit">Update
                </button>

                <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all"
                        @click="active=true"
                        x-bind:class = "active ? 'hidden' : ''"
                        type="button">Edit
                </button>

                <x-success-message event="add-edit-product-category-success"
                                   successMessage="{{$successMessage ?? ''}}"/>

            </div>
        </form>
    </div>
</div>