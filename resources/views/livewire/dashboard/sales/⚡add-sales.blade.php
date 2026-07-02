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
    public ?int $price = null;

    public bool $paid = false;

    public string $prfSearch = '';

    public ?array $prfReceipt = null;

    public bool $showPrfNotFound = false;

    #[Session]
    public float $grandTotal = 0;

    public ?float $availableStock = null;

    public bool $showProductNotFound = false;

    public ?int $editingItemIndex = null;

    public string $productSearchText = '';

    public array $productSearchResults = [];

    #[Validate("min:0.01|numeric")]
    public $currentItemQuantity = null;

    public function mount(): void
    {
        $this->js("document.getElementById('product-search').focus();");
//        $this->reset();

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
            $this->loadProductIntoCurrentItem($product);
        } else {
            $this->showProductNotFound = true;
        }

        $this->dispatch('show-data');

        $this->js("requestAnimationFrame(() => document.getElementById('quantity')?.focus())");
    }

    public function updatedProductSearchText(string $value): void
    {
        $this->productSearchResults = [];

        if (strlen($value) < 2) {
            return;
        }

        $this->productSearchResults = Product::query()
            ->with(['category:id,category_name'])
            ->where('name', 'like', "%{$value}%")
            ->limit(10)
            ->get()
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category?->category_name ?? 'Uncategorized',
                'stock_level' => $product->stock_level,
                'price' => $product->price,
                'class' => $product->class?->value ?? '',
                'size' => $product->size ?? '',
            ])
            ->toArray();
    }

    public function selectProduct(int $productId): void
    {
        $product = Product::find($productId);

        if (! $product) {
            $this->showProductNotFound = true;

            return;
        }

        $this->loadProductIntoCurrentItem($product);
        $this->reset('productSearchText', 'productSearchResults');
        $this->js("requestAnimationFrame(() => document.getElementById('quantity')?.focus())");
    }

    private function loadProductIntoCurrentItem(Product $product): void
    {
        $this->currentItem = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'availableStock' => $product->stock_level,
            'size' => $product->size,
            'category' => strtolower($product->category->category_name),
            'quantity' => 0,
            'class' => $product->class?->value ?? null,
        ];

        $this->price = $product->price;

        if (! empty($product['size'])) {
            $this->currentItem['size'] = $product->size;
        }
    }

    public function updatedCurrentItemQuantity($value): void
    {
        $this->currentItemQuantity = (float) $value;

        if ($this->currentItemQuantity === 0.00 || $this->currentItemQuantity < 0.01) {
            $this->reset('currentItemQuantity');

            return;
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
        $this->reset('searchId', 'currentItem', 'currentItemQuantity', 'editingItemIndex', 'showProductNotFound', 'productSearchText', 'productSearchResults');
        $this->clearValidation();
        $this->js("document.getElementById('product-search').focus()");
    }

    public function addQuantity(): void
    {
        if (!$this->currentItem) {
            return;
        }

        $this->validate([
            'currentItemQuantity' => 'min:0.01|numeric',
            'price' => 'min:1|integer',
        ]);

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
        $this->currentItem['price'] = $this->price;

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

    public function updatedPrfSearch(string $value): void
    {
        $this->reset('prfReceipt', 'showPrfNotFound');

        $value = trim($value);

        if (strlen($value) < 4) {
            return;
        }

        $sale = Sale::query()
            ->with(['salesItem.product.unit', 'user'])
            ->where('prf_number', 'like', "%{$value}%")
            ->latest('id')
            ->first();

        if (!$sale) {
            $this->showPrfNotFound = true;

            return;
        }

        $this->prfReceipt = $this->receiptPayload($sale);
    }

    public function resetPrfSearch(): void
    {
        $this->reset('prfSearch', 'prfReceipt', 'showPrfNotFound');
    }

    public function printPrfReceipt(): void
    {
        if (!$this->prfReceipt) {
            return;
        }

        PrintReceipt::print($this->prfReceipt, true);
    }

    private function receiptPayload(Sale $sale): array
    {
        return [
            'salesItems' => $sale->salesItem
                ->map(fn ($salesItem) => [
                    'product_id' => $salesItem->product_id,
                    'quantity' => $salesItem->quantity,
                    'inventory_start' => $salesItem->inventory_start,
                    'inventory_end' => $salesItem->inventory_end,
                    'unit_price' => $salesItem->unit_price,
                    'subtotal' => $salesItem->subtotal,
                    'product_name' => $salesItem->product?->name,
                    'product_unit' => $salesItem->product?->unit?->unit_name,
                ])
                ->toArray(),
            'prfNumber' => $sale->prf_number,
            'cashier' => $sale->user?->name ?? 'Unknown',
            'date' => $sale->created_at?->format('d/m/Y') ?? now()->format('d/m/Y'),
            'time' => $sale->created_at?->format('g:i:s A') ?? now()->format('g:i:s A'),
            'grandTotal' => $sale->total_amount,
        ];
    }

    public function pay(): void
    {
        if ($this->paid || ! $this->items) {
            return;
        }

        $this->paid = true;

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
        });

        $this->items = [];
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
        <div class="grid gap-0 grid-cols-1 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,1fr)]">
            <section class="border-b border-emerald-100 xl:border-r xl:border-b-0 xl:h-[90vh]">
                <div class="border-b border-emerald-100 bg-linear-to-r from-emerald-50 via-white to-emerald-100/70 px-6 py-5 sm:px-8">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,1.3fr)]">
                        <flux:field>
                            <flux:label>QR Code / Product Search</flux:label>
                            <flux:input icon="qr-code" type="number" placeholder="Scan QR or type product id..."
                                        id="product-search"
                                        autocomplete="off" wire:model.live="searchId"/>
                        </flux:field>

                        <flux:field class="relative">
                            <flux:label>Product Name Search</flux:label>
                            <flux:input icon="magnifying-glass" type="text" placeholder="Type product name..."
                                        autocomplete="off" wire:model.live.debounce.300ms="productSearchText"/>

                            @if(!empty($productSearchResults))
                                <div class="absolute z-20 mt-1 left-0 w-4xl max-w-[calc(100vw-2rem)] rounded-xl border border-zinc-200 bg-white shadow-lg p-2">
                                    <div class="overflow-x-auto">
                                        <div class="grid gap-x-2 grid-cols-[60px_200px_150px_70px_80px_65px_70px] min-w-[700px] border-b border-zinc-100 bg-zinc-50 px-3 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-zinc-500">
                                            <div>ID</div>
                                            <div>Name</div>
                                            <div>Category</div>
                                            <div>Qty</div>
                                            <div>Price</div>
                                            <div>Class</div>
                                            <div>Size</div>
                                        </div>
                                        <div class="max-h-56 overflow-y-auto">
                                            @foreach($productSearchResults as $result)
                                                <div wire:click="selectProduct({{ $result['id'] }})"
                                                     wire:key="search-result-{{ $result['id'] }}"
                                                      class="grid gap-x-2 cursor-pointer grid-cols-[60px_200px_150px_70px_80px_65px_70px] min-w-[700px] items-center border-b border-zinc-100 px-3 py-2.5 text-sm text-zinc-700 transition hover:bg-emerald-50">
                                                <span class="font-medium text-zinc-900">{{ $result['id'] }}</span>
                                                 <span class="min-w-0 truncate font-medium text-zinc-900">{{ $result['name'] }}</span>
                                                 <span class="min-w-0 truncate text-xs text-zinc-500">{{ $result['category'] }}</span>
                                                <span class="tabular-nums">{{ format_qty($result['stock_level']) }}</span>
                                                <span class="tabular-nums">₱{{ number_format($result['price'], 2) }}</span>
                                                <span class="min-w-0 truncate text-xs text-zinc-500">{{ $result['class'] }}</span>
                                                <span class="min-w-0 truncate text-xs text-zinc-500">{{ $result['size'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </flux:field>
                    </div>
                </div>

                <div class="px-6 py-5 sm:px-8">

                    <div class="overflow-hidden rounded-2xl border border-zinc-200">
                        <div class="overflow-x-auto">
                        <div class="grid grid-cols-[minmax(0,1.6fr)_110px_110px_110px_110px] min-w-[560px] border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase tracking-[0.22em] text-zinc-500">
                            <div class="px-4 py-3">Product</div>
                            <div class="px-4 py-3 text-right">Qty</div>
                            <div class="px-4 py-3 text-right">Price</div>
                            <div class="px-4 py-3 text-right">Amount</div>
                            <div class="px-4 py-3 text-right">Action</div>
                        </div>

                        <div class="divide-y divide-zinc-200 max-h-96 overflow-y-auto">
                            @forelse($items as $item)
                                <div wire:key="{{ $item['id'] }}"
                                     wire:click="editItem({{ $loop->index }})"
                                     class="grid grid-cols-[minmax(0,1.6fr)_110px_110px_110px_110px] min-w-[560px] bg-white text-sm text-zinc-700 transition hover:bg-emerald-50/60 cursor-pointer">
                                    <div class="px-4 py-4">
                                        <p class="font-semibold text-zinc-900">{{ $item['name'] }}</p>
                                        {{--                                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-zinc-500">{{ $item['code'] }}</p>--}}
                                    </div>
                                    <div class="px-4 py-4 text-right font-medium">{{ format_qty($item['quantity']) }}</div>
                                    <div class="px-4 py-4 text-right">₱{{ number_format($item['price'], 2) }}</div>
                                    <div class="px-4 py-4 text-right font-semibold text-zinc-900">
                                        @if(in_array($item['category'], ['livestock', 'poultry']))
                                            ₱{{ number_format($item['price'], 2) }}
                                        @else
                                        ₱{{ number_format($item['quantity'] * $item['price'], 2) }}
                                        @endif
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
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <button type="button" wire:click="pay" wire:loading.attr="disabled" wire:target="pay"
                                    :disabled="$paid"
                                    class="w-full rounded-2xl hover:bg-zinc-800 border border-white/10 bg-white/5 px-6 py-3 disabled:bg-gray-500 disabled:cursor-cell font-semibold cursor-pointer">
                                <span wire:loading.remove wire:target="pay">Pay</span>
                                <span wire:loading wire:target="pay">Processing...</span>
                            </button>

                            <div wire:click="newTransaction"
                                 class="hover:bg-zinc-800 cursor-pointer rounded-2xl border border-white/10 bg-white/5 px-6 py-3 flex items-center justify-center">
                                <button type="button" class="font-semibold" x-data
                                        >New
                                    Transaction
                                </button>
                            </div>
                            <flux:modal.trigger name="print-prf-receipt">
                                <button type="button"
                                        class="hover:bg-zinc-800 cursor-pointer rounded-2xl border border-white/10 bg-white/5 px-6 py-3 flex items-center justify-center font-semibold">
                                    Print PRF
                                </button>
                            </flux:modal.trigger>
                        </div>

                        </div>

                        <div class="px-6 py-5 sm:px-8">
                            <p class="text-xs uppercase tracking-[0.24em] text-zinc-400">Notes</p>
                            <div class="mt-3 rounded-2xl border border-dashed border-white/15 bg-white/5 p-4 text-sm text-zinc-300">
                                Verify item quantities before payment.
                            </div>
                        </div>
                    </div>
            </aside>
        </div>
    </div>
    {{--  SALES FORM  --}}
    @if($currentItem)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 backdrop-blur-xs p-4">
            <div class="relative bg-white p-4 w-full max-w-sm rounded-lg max-h-[90vh] overflow-y-auto">
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
                        <flux:label class="mb-0.5!">Category</flux:label>
                        <flux:input type="text" value="{{ $currentItem['category'] }}" placeholder="category"
                                    readonly/>
                    </flux:field>

                    <flux:field>
                        <flux:label class="mb-0.5!">Price</flux:label>
{{--                        <flux:input type="number" wire:model="price" placeholder="Price" :readonly="!in_array($currentItem['category'], ['livestock', 'poultry'])"/>--}}
                        <flux:input type="number" wire:model="price" step="0.01" placeholder="Price"/>
                        <flux:error name="price"/>
                    </flux:field>

                    <flux:field>
                        <flux:label class="mb-0.5!">Stocks Available</flux:label>
                        <flux:input type="number" value="{{ format_qty($currentItem['availableStock']) }}" placeholder="Quantity"
                                    readonly/>
                    </flux:field>

                    @if($currentItem['size'])
                    <flux:field>
                        <flux:label class="mb-0.5!">Size</flux:label>
                        <flux:input type="text" value="{{ $currentItem['size'] }}" placeholder="size"
                                    readonly/>
                    </flux:field>
                    @endif

                    @if($currentItem['class'])
                    <flux:field>
                        <flux:label class="mb-0.5!">Class</flux:label>
                        <flux:input type="text" value="{{ $currentItem['class'] }}" placeholder="class"
                                    readonly/>
                    </flux:field>
                    @endif


                    <flux:field>
                        <flux:label class="mb-0.5!">Quantity</flux:label>
                        <flux:input type="number" step="0.01" wire:model.live.debounce.1200ms="currentItemQuantity"
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

    <flux:modal name="print-prf-receipt" class="max-w-[32rem] w-full" @close="$wire.resetPrfSearch()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Print PRF Receipt</flux:heading>
                <flux:text class="mt-2">Search the PRF number to load the receipt details.</flux:text>
            </div>

            <flux:field>
                <flux:label>PRF Number</flux:label>
                <flux:input icon="magnifying-glass"
                            placeholder="PRF26-000001"
                            autocomplete="on"
                            wire:model.live.debounce.400ms="prfSearch"/>
            </flux:field>

            <div wire:loading wire:target="prfSearch" class="text-sm text-zinc-500">
                Searching receipt...
            </div>

            @if($showPrfNotFound)
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    No receipt found for "{{ $prfSearch }}".
                </div>
            @endif

            @if($prfReceipt)
                <div class="overflow-hidden rounded-xl border border-zinc-200">
                    <div class="grid gap-3 border-b border-zinc-200 bg-zinc-50 px-4 py-3 text-sm sm:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">PRF No.</p>
                            <p class="font-semibold text-zinc-900">{{ $prfReceipt['prfNumber'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Associate</p>
                            <p class="font-semibold text-zinc-900">{{ $prfReceipt['cashier'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Date</p>
                            <p class="font-semibold text-zinc-900">{{ $prfReceipt['date'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Total</p>
                            <p class="font-semibold text-zinc-900">₱{{ number_format($prfReceipt['grandTotal'], 2) }}</p>
                        </div>
                    </div>

                    <div class="max-h-64 divide-y divide-zinc-200 overflow-y-auto">
                        @foreach($prfReceipt['salesItems'] as $salesItem)
                            <div class="grid grid-cols-[minmax(0,1fr)_80px_100px] sm:grid-cols-[minmax(0,1fr)_90px_110px] gap-3 px-4 py-3 text-sm"
                                 wire:key="prf-item-{{ $salesItem['product_id'] }}-{{ $loop->index }}">
                                <div>
                                    <p class="font-medium text-zinc-900">{{ $salesItem['product_name'] ?? 'Unknown product' }}</p>
                                    <p class="text-xs text-zinc-500">{{ $salesItem['product_unit'] ?? 'unit' }}</p>
                                </div>
                                <p class="text-right text-zinc-700">{{ format_qty($salesItem['quantity']) }}</p>
                                <p class="text-right font-semibold text-zinc-900">₱{{ number_format($salesItem['subtotal'], 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex gap-2">
                <flux:spacer/>

                <flux:modal.close>
                    <flux:button variant="ghost">Close</flux:button>
                </flux:modal.close>

                <flux:button variant="primary"
                             icon="printer"
                             wire:click="printPrfReceipt"
                             wire:loading.attr="disabled"
                             wire:target="printPrfReceipt"
                             :disabled="!$prfReceipt">
                    Print
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <div wire:loading.flex wire:target="printPrfReceipt"
         class="fixed inset-0 z-[70] items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="w-full max-w-sm rounded-[2rem] bg-white p-8 text-center shadow-[0_32px_80px_rgba(0,0,0,0.35)]">
            <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100">
                <flux:icon.printer class="h-7 w-7 text-emerald-700"/>
            </div>
            <flux:heading size="lg" class="text-zinc-950">Printing receipt</flux:heading>
            <p class="mt-3 text-sm text-zinc-500">Please wait while the PRF receipt is sent to the printer.</p>
        </div>
    </div>

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
