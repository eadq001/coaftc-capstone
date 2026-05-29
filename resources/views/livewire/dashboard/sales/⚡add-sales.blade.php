<?php

use App\Models\Product;
use App\Models\Sale;
use App\PrintReceipt;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Session;

new #[Layout('layouts.dashboard')]
class extends Component {

    #[Validate('min:1|max:11|integer')]
    public $searchId = null;

    #[Session]
    public array $items = [];

//    #[Session]
    public array $currentItem = [];

    public bool $paid = false;

    #[Session]
    public float $grandTotal = 0;

    public ?int $availableStock = null;

    public bool $showProductNotFound = false;

    public ?int $editingItemIndex = null;

    #[Validate("min:1|integer")]
    public $currentItemQuantity = null;

    public function mount(): void
    {
        $this->js("document.getElementById('product-search').focus();");
//        $this->reset();

    }

    public function updatedSearchId($value = null): void
    {
        if (strlen($value) > 11 || ((int)$value) < 1) {
            $this->reset('searchId');
            return;
        }

        $value = (int)$value;

        $product = Product::find($value);

        if ($product) {
            $this->currentItem = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'availableStock' => $product->stock_level,
                'quantity' => 0,
                'class' => $product->class->value ?? null,
            ];

            if (!empty($product['size'])) {
                $this->currentItem['size'] = $product->size;
            }

        } else {
            $this->showProductNotFound = true;
        }

        $this->dispatch('show-data');

        $this->js("requestAnimationFrame(() => document.getElementById('quantity')?.focus())");

    }

    public function updatedCurrentItemQuantity($value): void
    {
        $this->currentItemQuantity = (int)$value;

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
                    'currentItemQuantity' => '|max:' . $this->currentItem['availableStock']
                ]);
            }

        }
    }

    public function resetCurrentItems(): void
    {
        $this->reset('searchId', 'currentItem', 'currentItemQuantity', 'editingItemIndex', 'showProductNotFound');
        $this->clearValidation();
        $this->js("document.getElementById('product-search').focus()");

    }

    public function addQuantity(): void
    {
        if (!$this->currentItem) {
            return;
        }

        $this->validateOnly('currentItemQuantity');

        // Editing existing item — replace quantity directly
        if ($this->editingItemIndex !== null) {
            if ($this->currentItem['availableStock'] < $this->currentItemQuantity) {
                $this->addError('currentItemQuantity', "Quantity cannot exceed available stock ({$this->currentItem['availableStock']}).");
                return;
            }
            $this->items[$this->editingItemIndex]['quantity'] = $this->currentItemQuantity;
            $this->resetCurrentItems();
            $this->dispatch('add-quantity-success');
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
                $this->resetCurrentItems();
                $this->dispatch('add-quantity-success');
                return;
            }
        }

        $this->currentItem['quantity'] = $this->currentItemQuantity;

        $this->items[] = $this->currentItem;
        $this->resetCurrentItems();
        $this->dispatch('add-quantity-success');
    }

    #[On('add-quantity-success')]
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
        $this->editingItemIndex = $index;

        $this->js("requestAnimationFrame(() => document.getElementById('quantity')?.focus())");

    }

    public function newTransaction(): void
    {
        $this->reset();
        $this->js("document.getElementById('product-search').focus();");
    }

    public function pay(): void
    {
        if ($this->items) {

            DB::transaction(function () {

                $sales = Sale::create([
                    'user_id' => auth()->id(),
                    'total_amount' => $this->grandTotal
                ]);

                $salesItems = collect($this->items)->map(fn($item) => [
                    'sale_id' => $sales->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'inventory_start' => $item['availableStock'],
                    'inventory_end' => $item['availableStock'] - $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ])->toArray();

                $stocksToSubtract = collect($this->items)->map(fn($item) => [
                    'id' => $item['id'],
                    'quantity' => $item['quantity']
                ]);

                foreach ($stocksToSubtract as $stock) {
                    $product = Product::find($stock['id']);
                    $product->decrement('stock_level', $stock['quantity']);
                }

                $sales->salesItem()->createMany($salesItems);

                $transactionInfo = [
                    'salesItems' => $salesItems,
                    'prfNumber' => $sales->prf_number,
                    'cashier' => auth()->user()->name,
                    'date' => now()->format('d/m/Y'),
                    'time' => now()->format('g:i:s A'),
                    'grandTotal' => $this->grandTotal

                ];

                PrintReceipt::print($transactionInfo);
                $this->paid = true;
            });
        }
    }

    public function removeItem(int $itemIndex)
    {
        unset($this->items[$itemIndex]);
        $this->items = array_values($this->items);
        $this->grandTotal();

        $this->js("document.getElementById('product-search').focus()");
    }

    protected function messages()
    {
        return [
            'currentItemQuantity.required' => 'Enter a quantity'
        ];
    }
};
?>

<div class="">
    <div class="overflow-hidden rounded-[2rem] border border-emerald-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.10)]">
        <div class="grid gap-0 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,1fr)] grid-rows-1">
            <section class="border-b border-emerald-100 xl:border-r xl:border-b-0 h-[90vh]">
                <div class="border-b border-emerald-100 bg-linear-to-r from-emerald-50 via-white to-emerald-100/70 px-6 py-5 sm:px-8">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1.3fr)_repeat(4,minmax(0,0.7fr))]">
                        <flux:field class="lg:col-span-2">
                            <flux:label>QR Code / Product Search</flux:label>
                            <flux:input icon="qr-code" type="number" placeholder="Scan QR or type product id..."
                                        id="product-search"
                                        autocomplete="off" wire:model.live="searchId"/>
                        </flux:field>
                    </div>
                </div>

                <div class="px-6 py-5 sm:px-8">

                    <div class="overflow-hidden rounded-2xl border border-zinc-200">
                        <div class="grid grid-cols-[minmax(0,1.6fr)_110px_110px_110px_110px] border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase tracking-[0.22em] text-zinc-500">
                            <div class="px-4 py-3">Product</div>
                            <div class="px-4 py-3 text-right">Qty</div>
                            <div class="px-4 py-3 text-right">Price</div>
                            <div class="px-4 py-3 text-right">Amount</div>
                            <div class="px-4 py-3 text-right">Action</div>
                        </div>

                        <div class="divide-y divide-zinc-200">
                            @forelse($items as $item)
                                <div wire:key="{{ $item['id'] }}"
                                     wire:click="editItem({{ $loop->index }})"
                                     class="grid grid-cols-[minmax(0,1.6fr)_110px_110px_110px_110px]  bg-white text-sm text-zinc-700 transition hover:bg-emerald-50/60 cursor-pointer">
                                    <div class="px-4 py-4">
                                        <p class="font-semibold text-zinc-900">{{ $item['name'] }}</p>
                                        {{--                                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-zinc-500">{{ $item['code'] }}</p>--}}
                                    </div>
                                    <div class="px-4 py-4 text-right font-medium">{{ $item['quantity'] }}</div>
                                    <div class="px-4 py-4 text-right">₱{{ number_format($item['price'], 2) }}</div>
                                    <div class="px-4 py-4 text-right font-semibold text-zinc-900">
                                        ₱{{ number_format($item['quantity'] * $item['price'], 2) }}
                                    </div>

                                    <div
                                            x-on:click.stop=""
                                            class="px-4 py-4 text-right font-semibold text-zinc-900 relative z-10"
                                    >
                                        <flux:modal.trigger name="remove-item-{{ $item['id'] }}">
                                            <flux:button variant="danger" size="xs">Remove</flux:button>
                                        </flux:modal.trigger>

                                        <flux:modal name="remove-item-{{ $item['id'] }}" class="min-w-[22rem]">
                                            <div class="space-y-6 text-left">
                                                <div>
                                                    <flux:heading size="lg">Remove product?</flux:heading>
                                                    <flux:text class="mt-2">
                                                        Do you want to remove "{{ $item['name'] }}" from the current
                                                        sale?
                                                    </flux:text>
                                                </div>

                                                <div class="flex gap-2">
                                                    <flux:spacer/>

                                                    <flux:modal.close>
                                                        <flux:button variant="ghost">No</flux:button>
                                                    </flux:modal.close>

                                                    <flux:modal.close>
                                                        <flux:button variant="danger"
                                                                     wire:click.stop="removeItem({{ $loop->index }})">
                                                            Yes
                                                        </flux:button>
                                                    </flux:modal.close>
                                                </div>
                                            </div>
                                        </flux:modal>

                                    </div>
                                </div>
                            @empty
                                <div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            <aside class="bg-zinc-950 text-white">
                <div class="border-b border-white/10 px-6 py-5 sm:px-8">
                    <flux:heading size="lg" class="!text-white">
                        Associate: {{ strtoupper(auth()->user()->name) }}</flux:heading>
                </div>

                <div class="grid gap-0">
                    <div class="grid grid-cols-2 border-b border-white/10">
                        <div class="border-r border-white/10 px-6 py-5">
                            <p class="text-xs uppercase tracking-[0.24em] text-zinc-400">Items</p>
                            <p class="mt-2 text-3xl font-semibold">{{ collect($items)->count() ?? 0 }}</p>
                        </div>
                        <div class="px-6 py-5">
                            <p class="text-xs uppercase tracking-[0.24em] text-zinc-400">Date</p>
                            <div x-data="{ current_date: new Date().toLocaleDateString() }">
                                <span x-text="current_date"></span>
                            </div>

                        </div>
                    </div>

                    <div class="border-b border-white/10 bg-linear-to-br from-emerald-500 to-emerald-700 px-6 py-6 sm:px-8">
                        <p class="text-xs uppercase tracking-[0.3em] text-emerald-100">Grand Total</p>
                        <p class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">
                            ₱{{ number_format($grandTotal, 2) }}
                        </p>
                        {{--                        <p class="mt-2 text-sm text-emerald-100/90">Ready for cash, card, or mixed payment.</p>--}}
                    </div>

                    <div class="border-b border-white/10 px-6 py-5 sm:px-8">
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" wire:click="pay" @disabled($paid) x-data
                                    @keydown.window.p="$wire.pay()"
                                    class="w-full rounded-2xl hover:bg-zinc-800 border border-white/10 bg-white/5 px-6 py-1 disabled:bg-gray-500 disabled:cursor-cell font-semibold cursor-pointer">
                                Pay
                            </button>

                            <div wire:click="newTransaction"
                                 class="hover:bg-zinc-800 cursor-pointer rounded-2xl border border-white/10 bg-white/5 px-6 py-1 flex items-center justify-center">
                                <button type="button" class="font-semibold" x-data
                                        @keydown.window.n="$wire.newTransaction()">New
                                    Transaction
                                </button>
                            </div>
                        </div>

                        <div class="px-6 py-5 sm:px-8">
                            <p class="text-xs uppercase tracking-[0.24em] text-zinc-400">Notes</p>
                            <div class="mt-3 rounded-2xl border border-dashed border-white/15 bg-white/5 p-4 text-sm text-zinc-300">
                                Verify item quantities before payment.
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
    {{--  SALES FORM  --}}
    @if($currentItem)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 backdrop-blur-xs">
            <div class="relative bg-white p-4 w-96 rounded-lg">
                <form class="space-y-3 text-sm" wire:submit="addQuantity">
                    <div class="absolute top-0 right-0 p-2" title="exit this form">
                        <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all" wire:click="resetCurrentItems"
                                          @click="showSubcategoryForm=false"/>
                    </div>
                    <p class="text-center">Sales Form</p>
                    <flux:field>
                        <flux:label class="mb-0.5!">Product Name</flux:label>
                        <flux:input type="text" value="{{ $currentItem['name'] }}" placeholder="Product Name"
                                    readonly/>
                    </flux:field>

                    <flux:field>
                        <flux:label class="mb-0.5!">Price</flux:label>
                        <flux:input type="text" value="{{ $currentItem['price'] }}" placeholder="Price" readonly/>
                    </flux:field>
                    <flux:field>
                        <flux:label class="mb-0.5!">Stocks Available</flux:label>
                        <flux:input type="number" value="{{ $currentItem['availableStock'] }}" placeholder="Quantity"
                                    readonly/>
                    </flux:field>

                    <flux:field>
                        <flux:label class="mb-0.5!">Quantity</flux:label>
                        <flux:input type="number" wire:model.live.debounce.600ms="currentItemQuantity"
                                    placeholder="Quantity" autocomplete="off" id="quantity"/>
                        <flux:error name="currentItemQuantity"/>
                    </flux:field>

                    <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">

                        <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all"
                                type="submit"
                        >{{ $editingItemIndex !== null ? 'Update' : 'Add Sale' }}
                        </button>

                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($paid)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/30 backdrop-blur-sm"
             x-data @keydown.enter.window="$wire.newTransaction()"
             x-init="$nextTick(() => $el.querySelector('button')?.focus())">
            <div class="bg-white rounded-[2rem] p-10 text-center shadow-[0_32px_80px_rgba(0,0,0,0.35)] max-w-sm w-full mx-4">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                    <flux:icon.check class="h-8 w-8 text-emerald-700"/>
                </div>
                <flux:heading size="lg" class="text-zinc-950">Transaction Paid</flux:heading>
                <p class="mt-3 text-sm text-zinc-500">Press <kbd
                            class="rounded-md border border-zinc-300 bg-zinc-100 px-2 py-0.5 font-mono text-xs text-zinc-600">Enter</kbd>
                    to start a new transaction.</p>
                <button type="button" wire:click="newTransaction"
                        class="mt-8 w-full rounded-xl bg-emerald-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    New Transaction
                </button>
            </div>
        </div>
    @endif

    @if($showProductNotFound)
        <div x-data @keydown.enter.window="$wire.set('showProductNotFound', false);$wire.resetCurrentItems()"
             class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-white rounded-[2rem] p-10 text-center shadow-[0_32px_80px_rgba(0,0,0,0.35)] max-w-sm w-full mx-4">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                    <flux:icon.x-mark class="h-8 w-8 text-red-600"/>
                </div>
                <flux:heading size="lg" class="text-zinc-950">Product Not Found</flux:heading>
                <p class="mt-3 text-sm text-zinc-500">The product ID <strong>#{{ $searchId }}</strong> does not exist.
                </p>
                <button type="button" wire:click="resetCurrentItems"
                        class="mt-8 w-full rounded-xl bg-zinc-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2">
                    OK
                </button>
            </div>
        </div>
    @endif

</div>
