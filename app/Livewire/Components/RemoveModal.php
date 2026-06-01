<?php

namespace App\Livewire\Components;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class RemoveModal extends Component
{
    public int $id;
    public string $name;
    public string $eventName;

    public Model $model;
    public string $modelName;

    public function deleteWithEvent(Product|Category|Subcategory|Unit $model): void
    {
        $events = [
            'Product' => 'product-delete-success',
            'Category' => 'product-category-delete-success',
            'Subcategory' => 'product-subcategory-delete-success',
            'Unit' => 'product-unit-delete-success'
        ];

        $eventToDispatch = $events[$this->modelName];

        $model->delete();
        $this->dispatch($eventToDispatch);
    }

    //flexible delete modal for 4 models
    public function softDeleteItem(): void
    {
        strtolower($this->modelName);
        ucfirst($this->modelName);

        switch ($this->modelName) {
            case 'Product':
                $this->model = Product::find($this->id);
                if ($this->model->salesItem->count() !== 0) {
                    $this->dispatch('product-delete-error');
                    return;
                }
                $this->deleteWithEvent($this->model);
                break;

            case 'Category':
                $this->model = Category::find($this->id);
                $this->deleteWithEvent($this->model);
                break;

            case 'Subcategory':
                $this->model = Subcategory::find($this->id);
                $this->deleteWithEvent($this->model);
                break;

            case 'Unit':
                $this->model = Unit::find($this->id);
                $this->deleteWithEvent($this->model);
                break;
        }


    }

    public function render()
    {
        return view('livewire.components.remove-modal');
    }
}
