<?php

use App\Models\Category;
use App\Models\Subcategory;
use Livewire\Component;

new class extends Component {
    public string $subcategory_name = '';

    public string $successMessage = '';

    public int $subcategoryToEdit;

    public Subcategory $subcategoryToEditModel;

    public function mount()
    {
        $this->subcategoryToEditModel = Subcategory::find($this->subcategoryToEdit);
        $this->subcategory_name = $this->subcategoryToEditModel->subcategory_name;
    }

    public function update(): void
    {
        $validated = $this->validate([
            'subcategory_name' => 'required|min:5|string',
        ]);

        $this->subcategoryToEditModel->update($validated);
        $this->reset('subcategory_name');
        $this->successMessage = 'Subcategory Successfully updated';
        $this->dispatch('add-edit-product-subcategory-success');
    }
};
?>
<div class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 backdrop-blur-xs">
    <div class="relative bg-white p-4 w-2xl rounded-lg" wire:transition wire:cloak>
        <form wire:submit="update" class="space-y-3 text-sm" x-data="{active:false}">
            <div class="absolute top-0 right-0 p-2" title="exit this form">
                <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all" wire:click="$parent.cancel"
                                  @click="showSubcategoryForm=false"/>
            </div>
            <p class="text-center" x-text="active ? 'Edit Product Category' : 'View Product Category'"></p>
            <flux:field>
                <flux:label class="mb-0.5!">Category Name</flux:label>
                <flux:input type="text" wire:model="subcategory_name" placeholder="Subcategory Name"
                            x-bind:readonly="!active"/>
                <flux:error name="subcategory_name"/>
            </flux:field>

            <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">

                <button class="bg-gray-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all"
                        x-bind:class="active ? 'block' : 'hidden'"
                        wire:dirty.class="bg-green-300"
                        type="submit">Update
                </button>

                <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all"
                        @click="active=true"
                        x-bind:class="active ? 'hidden' : ''"
                        type="button">Edit
                </button>

                <x-success-message event="add-edit-product-subcategory-success"
                                   successMessage="{{$successMessage ?? ''}}"/>

            </div>
        </form>
    </div>
</div>