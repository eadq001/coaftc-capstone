<?php

use App\Models\Unit;
use Livewire\Component;

new class extends Component {
    public string $unit_name = '';

    public string $successMessage = '';

    public int $unitToEdit;

    public Unit $unitToEditModel;

    public function mount()
    {
        $this->unitToEditModel = Unit::find($this->unitToEdit);
        $this->unit_name = $this->unitToEditModel->unit_name;
    }

    public function update(): void
    {
        $validated = $this->validate([
            'unit_name' => 'required|min:1|string',
        ]);

        $this->unitToEditModel->update($validated);
        $this->reset('unit_name');
        $this->successMessage = 'Unit Successfully updated';
        $this->dispatch('add-edit-product-unit-success');
    }
};
?>
<div class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 backdrop-blur-xs">
    <div class="relative bg-white p-4 w-2xl rounded-lg" wire:transition wire:cloak>
        <form wire:submit="update" class="space-y-3 text-sm" x-data="{active:false}">
            <div class="absolute top-0 right-0 p-2" title="exit this form">
                <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all" wire:click="$parent.cancel"
                                  @click="showUnitForm=false"/>
            </div>
            <p class="text-center" x-text="active ? 'Edit Product Unit' : 'View Product Unit'"></p>
            <flux:field>
                <flux:label class="mb-0.5!">Unit Name</flux:label>
                <flux:input type="text" wire:model="unit_name" placeholder="Unit Name"
                            x-bind:readonly="!active"/>
                <flux:error name="unit_name"/>
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

                <x-success-message event="add-edit-product-unit-success"
                                   successMessage="{{$successMessage ?? ''}}"/>

            </div>
        </form>
    </div>
</div>
