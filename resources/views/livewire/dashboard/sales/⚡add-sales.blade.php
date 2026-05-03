<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.dashboard')]
class extends Component {
    //
};
?>

<div>
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.26),transparent_28%),linear-gradient(135deg,#d9f99d_0%,#86efac_38%,#ecfccb_100%)] p-4 sm:p-6 lg:p-8">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-6">
            <div class="rounded-[2rem] border border-white/70 bg-white/80 p-4 shadow-[0_24px_60px_rgba(22,101,52,0.14)] backdrop-blur sm:p-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-700">Point Of Sale</p>
                        <h1 class="mt-2 text-3xl font-semibold text-zinc-900">Sales Counter</h1>
                        <p class="mt-1 text-sm text-zinc-600">Scan items on the left and review the current ticket in real time.</p>
                    </div>

                    <div class="w-full lg:max-w-xl">
                        <flux:input placeholder="Search product, scan barcode, or enter SKU">
                            <x-slot name="iconTrailing">
                                <flux:button size="sm" variant="subtle" icon="qr-code" class="-mr-1" />
                            </x-slot>
                        </flux:input>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,0.95fr)]">
                <section class="space-y-6">
                    <flux:card class="overflow-hidden rounded-[2rem] border border-white/65 bg-white/88 shadow-[0_18px_40px_rgba(22,101,52,0.12)]">
                        <div class="border-b border-emerald-100 bg-emerald-50/80 px-5 py-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <flux:heading size="lg">Scanning Panel</flux:heading>
                                    <flux:text class="mt-1 text-sm text-zinc-600">Prepared for barcode input, quick quantity edits, and ticket building.</flux:text>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <flux:badge color="emerald" variant="solid" rounded>Lane 01</flux:badge>
                                    <flux:badge color="zinc" variant="subtle" rounded>Cashier Station</flux:badge>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-5 p-5 lg:grid-cols-[minmax(0,0.95fr)_minmax(0,1.35fr)]">
                            <div class="rounded-[1.5rem] border border-dashed border-emerald-300 bg-emerald-50/60 p-5">
                                <div class="flex h-full flex-col justify-between gap-8">
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600 text-white">
                                                <flux:icon.qr-code class="h-6 w-6" />
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-zinc-900">Scanner Input</p>
                                                <p class="text-xs text-zinc-500">Reserved area for barcode capture and manual item lookup.</p>
                                            </div>
                                        </div>

                                        <div class="mt-6 rounded-2xl border border-white/90 bg-white p-4 shadow-sm">
                                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-zinc-500">Ready To Scan</p>
                                            <div class="mt-3 flex items-center justify-between rounded-xl bg-zinc-950 px-4 py-3 text-lime-300">
                                                <span class="font-mono text-sm">SKU-0000000000</span>
                                                <flux:icon.signal class="h-4 w-4" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-emerald-100">
                                            <p class="text-xs uppercase tracking-[0.22em] text-zinc-500">Items</p>
                                            <p class="mt-2 text-2xl font-semibold text-zinc-900">04</p>
                                        </div>
                                        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-emerald-100">
                                            <p class="text-xs uppercase tracking-[0.22em] text-zinc-500">Discount</p>
                                            <p class="mt-2 text-2xl font-semibold text-zinc-900">0%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-[1.5rem] border border-zinc-200 bg-white p-4 shadow-sm">
                                <div class="flex items-center justify-between border-b border-zinc-100 px-2 pb-4">
                                    <div>
                                        <p class="text-sm font-semibold text-zinc-900">Current Sale</p>
                                        <p class="text-xs text-zinc-500">Layout preview for scanned items only.</p>
                                    </div>
                                    <flux:badge color="amber" variant="subtle" rounded>Open Ticket</flux:badge>
                                </div>

                                <div class="mt-4 space-y-3">
                                    <div class="grid grid-cols-[minmax(0,1.5fr)_72px_88px_88px] items-center gap-3 rounded-2xl bg-zinc-50 px-4 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">
                                        <span>Product</span>
                                        <span class="text-center">Qty</span>
                                        <span class="text-right">Price</span>
                                        <span class="text-right">Total</span>
                                    </div>

                                    <div class="space-y-3">
                                        @foreach ([
                                            ['name' => 'Large Brown Eggs', 'sku' => 'SKU-10024', 'qty' => '2', 'price' => '$6.50', 'total' => '$13.00'],
                                            ['name' => 'Fresh Eggplant', 'sku' => 'SKU-10081', 'qty' => '1', 'price' => '$4.25', 'total' => '$4.25'],
                                            ['name' => 'Native Chicken Feed', 'sku' => 'SKU-10107', 'qty' => '3', 'price' => '$8.10', 'total' => '$24.30'],
                                        ] as $line)
                                            <div class="grid grid-cols-[minmax(0,1.5fr)_72px_88px_88px] items-center gap-3 rounded-2xl border border-zinc-100 bg-white px-4 py-4 shadow-sm">
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-semibold text-zinc-900">{{ $line['name'] }}</p>
                                                    <p class="mt-1 text-xs text-zinc-500">{{ $line['sku'] }}</p>
                                                </div>
                                                <div class="text-center text-sm font-medium text-zinc-700">{{ $line['qty'] }}</div>
                                                <div class="text-right text-sm text-zinc-700">{{ $line['price'] }}</div>
                                                <div class="text-right text-sm font-semibold text-zinc-900">{{ $line['total'] }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </flux:card>
                </section>

                <aside class="space-y-6">
                    <flux:card class="rounded-[2rem] border border-white/65 bg-white/88 p-5 shadow-[0_18px_40px_rgba(22,101,52,0.12)]">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <flux:heading size="lg">Product Display</flux:heading>
                                <flux:text class="mt-1 text-sm text-zinc-600">Suggested products, categories, and quick-pick cards.</flux:text>
                            </div>

                            <flux:badge color="blue" variant="subtle" rounded>Catalog</flux:badge>
                        </div>

                        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-1">
                            @foreach ([
                                ['name' => 'Jumbo Eggs', 'meta' => 'Tray of 12', 'price' => '$8.40'],
                                ['name' => 'Duck Eggs', 'meta' => 'Fresh stock', 'price' => '$7.25'],
                                ['name' => 'Organic Fertilizer', 'meta' => '5 kg bag', 'price' => '$18.90'],
                                ['name' => 'Farm Fresh Vegetables', 'meta' => 'Mixed basket', 'price' => '$12.50'],
                            ] as $product)
                                <div class="rounded-[1.5rem] border border-zinc-200 bg-linear-to-br from-white to-emerald-50/70 p-4 shadow-sm">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-zinc-900">{{ $product['name'] }}</p>
                                            <p class="mt-1 text-xs text-zinc-500">{{ $product['meta'] }}</p>
                                        </div>
                                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                                            <flux:icon.cube class="h-5 w-5" />
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between">
                                        <flux:badge color="emerald" variant="subtle" rounded>In stock</flux:badge>
                                        <p class="text-base font-semibold text-zinc-900">{{ $product['price'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </flux:card>

                    <flux:card class="rounded-[2rem] border border-white/65 bg-zinc-950 p-5 text-white shadow-[0_18px_40px_rgba(15,23,42,0.22)]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.26em] text-emerald-300">Summary</p>
                                <p class="mt-2 text-3xl font-semibold">$41.55</p>
                            </div>
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10">
                                <flux:icon.banknotes class="h-7 w-7 text-emerald-300" />
                            </div>
                        </div>

                        <div class="mt-5 space-y-3 text-sm text-zinc-300">
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <span>$41.55</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Tax</span>
                                <span>$0.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Discount</span>
                                <span>$0.00</span>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <flux:button variant="subtle" class="w-full">Hold Sale</flux:button>
                            <flux:button variant="primary" class="w-full">Checkout</flux:button>
                        </div>
                    </flux:card>
                </aside>
            </div>
        </div>
    </div>
</div>
