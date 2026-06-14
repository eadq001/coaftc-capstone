<?php

namespace App\Livewire\Components;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class RestoreModal extends Component
{
    public int $id;

    public string $modelName;

    public string $itemName;

    public Model $model;

    public function restoreWithEvent(Product|Category|Subcategory|Unit $model): void
    {
        $events = [
            'Product' => 'product-restore-success',
            'Category' => 'product-category-restore-success',
            'Subcategory' => 'product-subcategory-restore-success',
            'Unit' => 'product-unit-restore-success',
        ];

        $oldValues = ActivityLog::valuesFor($model);

        $model->restore();
        $model->refresh();

        ActivityLog::record(
            action: 'restore',
            model: $this->modelName,
            oldValues: $oldValues,
            newValues: ActivityLog::valuesFor($model),
        );

        $this->dispatch($events[$this->modelName]);
    }

    // flexible restore modal for 4 models
    public function restoreDeletedItem(): void
    {
        strtolower($this->modelName);
        ucfirst($this->modelName);

        switch ($this->modelName) {
            case 'Product':
                $this->model = Product::withTrashed()->find($this->id);
                $this->restoreWithEvent($this->model);
                break;

            case 'Category':
                $this->model = Category::withTrashed()->find($this->id);
                $this->restoreWithEvent($this->model);
                break;

            case 'Subcategory':
                $this->model = Subcategory::withTrashed()->find($this->id);
                $this->restoreWithEvent($this->model);
                break;

            case 'Unit':
                $this->model = Unit::withTrashed()->find($this->id);
                $this->restoreWithEvent($this->model);
                break;
        }

    }

    public function render()
    {
        return view('livewire.components.restore-modal');
    }
}
