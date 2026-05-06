<?php

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts.dashboard')]
class extends Component {

    public ?int $searchId = null;

    public array $items = [];

    public array $currentItem = [];

    public float $grandTotal = 0;

    #[Validate('integer|required')]
    public ?int $currentItemQuantity = null;

    public function mount(): void
    {
//        $this->items = new Collection();
//        $this->currentItem = new Collection();
        $this->js("document.getElementById('product-search').focus();");
//        $this->js("document.getElementById('quantity').focus();");

    }

    public function updatedSearchId(?int $value = null)
    {
        if (!$value) {
            return;
        }

        $product = Product::find($value);

        if ($product) {
            $this->currentItem = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'class' => $product->class->value,
            ];

            if (!empty($product['size'])) {
                $this->currentItem['size'] = $product->size;
            }

//            dd($this->currentItem);
        } else {
            $this->js("alert('The product doesnt exist')");
        }

        $this->dispatch('show-data');
//        $this->reset('searchId');
    }

    public function resetCurrentItems()
    {
        $this->reset('searchId', 'currentItem', 'currentItemQuantity');
        $this->js("document.getElementById('product-search').focus()");

    }

    public function addQuantity(): void
    {
        if (!$this->currentItem) {
            return;
        }

        $this->validateOnly('currentItemQuantity');
        $this->currentItem['quantity'] = $this->currentItemQuantity;

        foreach ($this->items as $index => $item) {
            if ($item['id'] === $this->currentItem['id']) {
                $this->items[$index]['quantity'] += $this->currentItem['quantity'];
                $this->resetCurrentItems();
                $this->dispatch('add-quantity-success');
                return;
            }
        }

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
};
?>

<div class="">
    <div class="overflow-hidden rounded-[2rem] border border-emerald-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.10)]">
        <div class="grid gap-0 xl:grid-cols-[minmax(0,1.65fr)_minmax(320px,0.85fr)]">
            <section class="border-b border-emerald-100 xl:border-r xl:border-b-0">
                <div class="border-b border-emerald-100 bg-linear-to-r from-emerald-50 via-white to-emerald-100/70 px-6 py-5 sm:px-8">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1.3fr)_repeat(4,minmax(0,0.7fr))]">
                        <flux:field class="lg:col-span-2">
                            <flux:label>QR Code / Product Search</flux:label>
                            <flux:input icon="qr-code" type="number" placeholder="Scan QR or type product name..."
                                        id="product-search"
                                        autocomplete="off" wire:model.live="searchId"/>
                        </flux:field>
                    </div>
                </div>

                <div class="px-6 py-5 sm:px-8">

                    <div class="overflow-hidden rounded-2xl border border-zinc-200">
                        <div class="grid grid-cols-[minmax(0,1.6fr)_110px_110px_140px] border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase tracking-[0.22em] text-zinc-500">
                            <div class="px-4 py-3">Product</div>
                            <div class="px-4 py-3 text-right">Qty</div>
                            <div class="px-4 py-3 text-right">Price</div>
                            <div class="px-4 py-3 text-right">Amount</div>
                        </div>

                        <div class="divide-y divide-zinc-200">
                            @forelse($items as $item)
                                <div class="grid grid-cols-[minmax(0,1.6fr)_110px_110px_140px] items-center bg-white text-sm text-zinc-700 transition hover:bg-emerald-50/60">
                                    <div class="px-4 py-4">
                                        <p class="font-semibold text-zinc-900">{{ $item['name'] }}</p>
                                        {{--                                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-zinc-500">{{ $item['code'] }}</p>--}}
                                    </div>
                                    <div class="px-4 py-4 text-right font-medium">{{ $item['quantity'] }}</div>
                                    <div class="px-4 py-4 text-right">₱{{ number_format($item['price'], 2) }}</div>
                                    <div class="px-4 py-4 text-right font-semibold text-zinc-900">
                                        ₱{{ number_format($item['quantity'] * $item['price'], 2) }}
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
                        CASHIER: {{ strtoupper(auth()->user()->name) }}</flux:heading>
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
                            <div class="rounded-2xl border border-white/10 bg-white/5 px-6 py-1 flex items-center justify-center cursor-pointer">
                                <button type="submit" class="font-semibold cursor-pointer">Pay</button>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 px-6 flex items-center justify-center">
                                <button type="button" class="font-semibold">Print</button>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 px-6 py-1 flex items-center justify-center">
                                <button type="button" class="font-semibold">New Transaction</button>
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
                    <flux:label class="mb-0.5!">Quantity</flux:label>
                    <flux:input type="text" wire:model="currentItemQuantity" placeholder="Quantity" id="quantity"/>
                </flux:field>

                <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">

                    <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all"
                            type="submit"
                    >Add Sale
                    </button>

                </div>
            </form>
        </div>
    @endif
</div>
