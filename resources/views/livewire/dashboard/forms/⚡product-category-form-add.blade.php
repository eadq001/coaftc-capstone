<?php

use App\Models\ActivityLog;
use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {

    #[Validate('min:0')]
    public string $category_name = '';

    public string $successMessage = '';

    public function categoryCheck()
    {
        $result = Category::where('category_name', 'LIKE', '%' . $this->category_name . '%')->exists();
        if ($result) {
            $this->addError('category_name', 'Category already exist');
            return true;
        }
    }
    public function updatedCategoryName(): void
    {
        $this->categoryCheck();
    }
    public function save(): void
    {
        if ($this->categoryCheck()) {
            return;
        }

        $validated = $this->validate([
            'category_name' => 'required|min:5|string'
        ]);
        $validated['category_name'] = ucfirst($validated['category_name']);
        $this->reset('category_name');

        $category = Category::create($validated);

        ActivityLog::record(
            action: 'create',
            model: 'Category',
            newValues: ActivityLog::valuesFor($category),
        );

        $this->dispatch('add-edit-product-category-success');
        $this->successMessage = 'Category Successfully added';
    }
};
?>

<div class="relative bg-white p-4 w-2xl rounded-lg">
    <form wire:submit="save" class="space-y-3 text-sm ">
        <div class="absolute top-0 right-0 p-2" title="exit this form">
            <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all" wire:click="$parent.cancel"
                              @click="showCategoryForm=false"/>
        </div>
        <p class="text-center">Add Product Category</p>
        <flux:field>
            <flux:label class="mb-0.5!">Category Name</flux:label>
            <flux:input type="text" wire:model.live.debounce.500ms="category_name" placeholder="Category Name"/>
            <flux:error name="category_name"/>
        </flux:field>

        <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">

            <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all"
                    type="submit">Add
            </button>

            <x-success-message event="add-edit-product-category-success" successMessage="{{$successMessage ?? ''}}"/>

        </div>
    </form>
</div>
