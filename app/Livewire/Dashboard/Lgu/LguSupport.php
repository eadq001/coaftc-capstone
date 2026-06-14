<?php

namespace App\Livewire\Dashboard\Lgu;

use App\Livewire\Dashboard;
use App\Models\Dispersal;
use App\Models\Product;
use App\PrintReceipt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;

class LguSupport extends Dashboard
{
    #[Validate('min:1|max:11|integer')]
    public $searchId = null;

    public array $items = [];

    public array $currentItem = [];

    public bool $submitted = false;

    public string $dispersalSearch = '';

    public ?array $dispersalReceipt = null;

    public bool $showDispersalNotFound = false;

    public bool $showProductNotFound = false;

    public ?int $editingItemIndex = null;

    #[Validate('min:1|integer')]
    public $currentItemQuantity = null;

    public string $currentItemClass = '';

    public ?int $price = null;

    public float $grandTotal = 0;

    public string $remarks = '';

    public function mount(): void
    {
        $this->js("document.getElementById('product-search').focus();");
    }

    public function updatedSearchId($value = null): void
    {
        if (strlen($value) > 11 || ((int) $value) < 1) {
            $this->reset('searchId');

            return;
        }

        $value = (int) $value;

        $product = Product::find($value);

        if ($product) {
            $this->currentItem = [
                'id' => $product->id,
                'name' => $product->name,
                'availableStock' => $product->stock_level,
                'category' => strtolower($product->category->category_name),
                'quantity' => 0,
                'class' => $product->class?->value ?? '',
                'size' => $product->size ?? '',
                'price' => $product->price,
            ];

            $this->currentItemClass = $product->class?->value ?? '';
            $this->price = $product->price;
        } else {
            $this->showProductNotFound = true;
        }

        $this->dispatch('show-data');
        $this->js("requestAnimationFrame(() => document.getElementById('quantity')?.focus())");
    }

    public function updatedCurrentItemQuantity($value): void
    {
        $this->currentItemQuantity = (int) $value;

        if (strlen($this->currentItemQuantity) > 11 || $this->currentItemQuantity < 1) {
            $this->reset('currentItemQuantity');
        }

        foreach ($this->items as $index => $item) {
            if ($item['id'] === $this->currentItem['id']) {
                if ($this->items[$index]['availableStock'] < ($this->currentItemQuantity + $this->items[$index]['quantity'])) {
                    $this->addError('currentItemQuantity', "Quantity cannot exceed available stock ({$item['availableStock']}).");

                    return;
                }

                $this->validate([
                    'currentItemQuantity' => '|max:'.$this->currentItem['availableStock'],
                ]);
            }
        }
    }

    public function resetCurrentItems(): void
    {
        $this->reset('searchId', 'currentItem', 'currentItemQuantity', 'currentItemClass', 'price', 'editingItemIndex', 'showProductNotFound');
        $this->clearValidation();
        $this->js("document.getElementById('product-search').focus()");
    }

    public function addQuantity(): void
    {
        if (! $this->currentItem) {
            return;
        }

        $this->validate([
            'currentItemQuantity' => 'min:1',
            'currentItemClass' => 'nullable|string',
            'price' => 'min:1|integer',
        ]);

        if ($this->editingItemIndex !== null) {
            if ($this->currentItem['availableStock'] < $this->currentItemQuantity) {
                $this->addError('currentItemQuantity', "Quantity cannot exceed available stock ({$this->currentItem['availableStock']}).");

                return;
            }
            $this->items[$this->editingItemIndex]['quantity'] = $this->currentItemQuantity;
            $this->items[$this->editingItemIndex]['class'] = $this->currentItemClass;
            $this->items[$this->editingItemIndex]['size'] = $this->currentItem['size'];
            $this->items[$this->editingItemIndex]['price'] = $this->price;
            $this->resetCurrentItems();
            $this->dispatch('add-quantity-success');
            $this->grandTotal();

            return;
        }

        if ($this->currentItem['availableStock'] < ($this->currentItemQuantity + $this->currentItem['quantity'])) {
            $this->addError('currentItemQuantity', "Quantity cannot exceed available stock ({$this->currentItem['availableStock']}).");

            return;
        }

        foreach ($this->items as $index => $item) {
            if ($item['id'] === $this->currentItem['id']) {
                if ($this->items[$index]['availableStock'] < ($this->currentItemQuantity + $this->items[$index]['quantity'])) {
                    $this->addError('currentItemQuantity', "Quantity cannot exceed available stock ({$item['availableStock']}).");

                    return;
                }

                $this->items[$index]['quantity'] += $this->currentItemQuantity;
                $this->items[$index]['class'] = $this->currentItemClass;
                $this->items[$index]['size'] = $this->currentItem['size'];
                $this->items[$index]['price'] = $this->price;
                $this->resetCurrentItems();
                $this->dispatch('add-quantity-success');
                $this->grandTotal();

                return;
            }
        }

        $this->currentItem['quantity'] = $this->currentItemQuantity;
        $this->currentItem['class'] = $this->currentItemClass;
        $this->currentItem['size'] = $this->currentItem['size'];
        $this->currentItem['price'] = $this->price;

        $this->items[] = $this->currentItem;
        $this->resetCurrentItems();
        $this->dispatch('add-quantity-success');
        $this->grandTotal();
    }

    public function grandTotal(): void
    {
        $grandTotal = 0;
        foreach ($this->items as $item) {
            $grandTotal += $item['quantity'] * $item['price'];
        }

        $this->grandTotal = $grandTotal;
    }

    public function editItem(int $index): void
    {
        $this->currentItem = $this->items[$index];
        $this->currentItemQuantity = $this->items[$index]['quantity'];
        $this->currentItemClass = $this->items[$index]['class'];
        $this->price = $this->items[$index]['price'];
        $this->editingItemIndex = $index;

        $this->js("requestAnimationFrame(() => document.getElementById('quantity')?.focus())");
    }

    public function newTransaction(): void
    {
        $this->reset();
        $this->js("document.getElementById('product-search').focus();");
    }

    public function submit(): void
    {
        if (! $this->items) {
            return;
        }

        DB::transaction(function () {
            $dispersal = Dispersal::create([
                'user_id' => auth()->id(),
                'total_amount' => $this->grandTotal,
                'remarks' => $this->remarks,
            ]);

            $dispersalItems = collect($this->items)->map(fn ($item) => [
                'dispersal_id' => $dispersal->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'class' => $item['class'],
                'size' => $item['size'] ?? null,
                'unit_price' => $item['price'],
                'subtotal' => $item['quantity'] * $item['price'],
                'inventory_start' => $item['availableStock'],
                'inventory_end' => $item['availableStock'] - $item['quantity'],
            ])->toArray();

            $stocksToSubtract = collect($this->items)->map(fn ($item) => [
                'id' => $item['id'],
                'quantity' => $item['quantity'],
            ]);

            foreach ($stocksToSubtract as $stock) {
                $product = Product::find($stock['id']);
                $product->decrement('stock_level', $stock['quantity']);
            }

            $dispersal->dispersalItems()->createMany($dispersalItems);

            $transactionInfo = [
                'dispersalItems' => $dispersalItems,
                'dispersalNumber' => $dispersal->dispersal_number,
                'cashier' => auth()->user()->name,
                'date' => now()->format('d/m/Y'),
                'time' => now()->format('g:i:s A'),
                'grandTotal' => $this->grandTotal,
                'remarks' => $this->remarks,
            ];

            PrintReceipt::printDispersal($transactionInfo);
            $this->submitted = true;
        });
    }

    public function updatedDispersalSearch(string $value): void
    {
        $this->reset('dispersalReceipt', 'showDispersalNotFound');

        $value = trim($value);

        if (strlen($value) < 4) {
            return;
        }

        $dispersal = Dispersal::query()
            ->with(['dispersalItems.product.unit', 'user'])
            ->where('dispersal_number', 'like', "%{$value}%")
            ->latest('id')
            ->first();

        if (! $dispersal) {
            $this->showDispersalNotFound = true;

            return;
        }

        $this->dispersalReceipt = $this->receiptPayload($dispersal);
    }

    public function resetDispersalSearch(): void
    {
        $this->reset('dispersalSearch', 'dispersalReceipt', 'showDispersalNotFound');
    }

    public function printDispersalReceipt(): void
    {
        if (! $this->dispersalReceipt) {
            return;
        }

        PrintReceipt::printDispersal($this->dispersalReceipt, true);
    }

    private function receiptPayload(Dispersal $dispersal): array
    {
        return [
            'dispersalItems' => $dispersal->dispersalItems
                ->map(fn ($item) => [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'class' => $item->class,
                    'size' => $item->size,
                    'inventory_start' => $item->inventory_start,
                    'inventory_end' => $item->inventory_end,
                    'product_name' => $item->product?->name,
                    'product_unit' => $item->product?->unit?->unit_name,
                ])
                ->toArray(),
            'dispersalNumber' => $dispersal->dispersal_number,
            'cashier' => $dispersal->user?->name ?? 'Unknown',
            'date' => $dispersal->created_at?->format('d/m/Y') ?? now()->format('d/m/Y'),
            'time' => $dispersal->created_at?->format('g:i:s A') ?? now()->format('g:i:s A'),
            'remarks' => $dispersal->remarks,
        ];
    }

    public function removeItem(int $itemIndex): void
    {
        unset($this->items[$itemIndex]);
        $this->items = array_values($this->items);
        $this->grandTotal();

        $this->js("document.getElementById('product-search').focus()");
    }

    protected function messages(): array
    {
        return [
            'currentItemQuantity.required' => 'Enter a quantity',
            'currentItemClass.required' => 'Select a class',
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.lgu.lgu-support');
    }
}
