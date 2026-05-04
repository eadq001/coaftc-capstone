<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.dashboard')]
class extends Component {
    public function mount(): void
    {
        $this->js("document.getElementById('product-search').focus();");
    }
};
?>

@php
    $cartItems = [
        ['name' => 'Coca-Cola 1.5L', 'code' => 'QR-10021', 'qty' => 2, 'price' => 95.00],
        ['name' => 'Gardenia Classic', 'code' => 'QR-10042', 'qty' => 1, 'price' => 78.00],
        ['name' => 'Cup Noodles Beef', 'code' => 'QR-10056', 'qty' => 3, 'price' => 32.00],
    ];

    $subtotal = collect($cartItems)->sum(fn (array $item): float => $item['qty'] * $item['price']);
    $discount = 25.00;
    $tax = 18.50;
    $grandTotal = $subtotal - $discount + $tax;
@endphp

<div class="">
{{--    <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">--}}
{{--        <div>--}}
{{--            <flux:heading size="xl" level="1">Point of Sale</flux:heading>--}}
{{--            <flux:text class="mt-1 text-zinc-700/80">--}}
{{--                Scan items, review the basket, and complete the transaction from one screen.--}}
{{--            </flux:text>--}}
{{--        </div>--}}

{{--        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">--}}
{{--            <div class="rounded-2xl border border-emerald-200 bg-white/90 px-4 py-3 shadow-sm">--}}
{{--                <p class="text-xs uppercase tracking-[0.24em] text-zinc-500">Register</p>--}}
{{--                <p class="mt-2 text-sm font-semibold text-zinc-900">POS-03</p>--}}
{{--            </div>--}}
{{--            <div class="rounded-2xl border border-emerald-200 bg-white/90 px-4 py-3 shadow-sm">--}}
{{--                <p class="text-xs uppercase tracking-[0.24em] text-zinc-500">Shift</p>--}}
{{--                <p class="mt-2 text-sm font-semibold text-zinc-900">Morning</p>--}}
{{--            </div>--}}
{{--            <div class="rounded-2xl border border-emerald-200 bg-white/90 px-4 py-3 shadow-sm">--}}
{{--                <p class="text-xs uppercase tracking-[0.24em] text-zinc-500">Cashier</p>--}}
{{--                <p class="mt-2 text-sm font-semibold text-zinc-900">{{ auth()->user()->name }}</p>--}}
{{--            </div>--}}
{{--            <div class="rounded-2xl border border-emerald-200 bg-white/90 px-4 py-3 shadow-sm">--}}
{{--                <p class="text-xs uppercase tracking-[0.24em] text-zinc-500">Queue</p>--}}
{{--                <p class="mt-2 text-sm font-semibold text-zinc-900">#1048</p>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="overflow-hidden rounded-[2rem] border border-emerald-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.10)]">
        <div class="grid gap-0 xl:grid-cols-[minmax(0,1.65fr)_minmax(320px,0.85fr)]">
            <section class="border-b border-emerald-100 xl:border-r xl:border-b-0">
                <div class="border-b border-emerald-100 bg-linear-to-r from-emerald-50 via-white to-emerald-100/70 px-6 py-5 sm:px-8">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1.3fr)_repeat(4,minmax(0,0.7fr))]">
                        <flux:field class="lg:col-span-2">
                            <flux:label>QR Code / Product Search</flux:label>
                            <flux:input icon="qr-code" placeholder="Scan QR or type product name..."  id="product-search"/>
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
                            @foreach($cartItems as $item)
                                <div class="grid grid-cols-[minmax(0,1.6fr)_110px_110px_140px] items-center bg-white text-sm text-zinc-700 transition hover:bg-emerald-50/60">
                                    <div class="px-4 py-4">
                                        <p class="font-semibold text-zinc-900">{{ $item['name'] }}</p>
{{--                                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-zinc-500">{{ $item['code'] }}</p>--}}
                                    </div>
                                    <div class="px-4 py-4 text-right font-medium">{{ $item['qty'] }}</div>
                                    <div class="px-4 py-4 text-right">₱{{ number_format($item['price'], 2) }}</div>
                                    <div class="px-4 py-4 text-right font-semibold text-zinc-900">
                                        ₱{{ number_format($item['qty'] * $item['price'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <aside class="bg-zinc-950 text-white">
                <div class="border-b border-white/10 px-6 py-5 sm:px-8">
                    <flux:heading size="lg" class="!text-white">Sales Summary</flux:heading>
                    <flux:text class="mt-1 !text-zinc-300">
                        Review totals, customer details, and payment readiness before checkout.
                    </flux:text>
                </div>

                <div class="grid gap-0">
                    <div class="grid grid-cols-2 border-b border-white/10">
                        <div class="border-r border-white/10 px-6 py-5">
                            <p class="text-xs uppercase tracking-[0.24em] text-zinc-400">Items</p>
                            <p class="mt-2 text-3xl font-semibold">{{ collect($cartItems)->sum('qty') }}</p>
                        </div>
                        <div class="px-6 py-5">
                            <p class="text-xs uppercase tracking-[0.24em] text-zinc-400">Lines</p>
                            <p class="mt-2 text-3xl font-semibold">{{ count($cartItems) }}</p>
                        </div>
                    </div>

                    <div class="border-b border-white/10 px-6 py-5 sm:px-8">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-sm text-zinc-300">
                                <span>Subtotal</span>
                                <span class="font-medium text-white">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-zinc-300">
                                <span>Discount</span>
                                <span class="font-medium text-emerald-300">- ₱{{ number_format($discount, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-zinc-300">
                                <span>Tax</span>
                                <span class="font-medium text-white">₱{{ number_format($tax, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-white/10 bg-linear-to-br from-emerald-500 to-emerald-700 px-6 py-6 sm:px-8">
                        <p class="text-xs uppercase tracking-[0.3em] text-emerald-100">Grand Total</p>
                        <p class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">
                            ₱{{ number_format($grandTotal, 2) }}
                        </p>
                        <p class="mt-2 text-sm text-emerald-100/90">Ready for cash, card, or mixed payment.</p>
                    </div>

                    <div class="border-b border-white/10 px-6 py-5 sm:px-8">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-zinc-400">Customer</p>
                                <p class="mt-2 font-semibold">Walk-in</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-zinc-400">Payment</p>
                                <p class="mt-2 font-semibold">Cash</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-5 sm:px-8">
                        <p class="text-xs uppercase tracking-[0.24em] text-zinc-400">Notes</p>
                        <div class="mt-3 rounded-2xl border border-dashed border-white/15 bg-white/5 p-4 text-sm text-zinc-300">
                            Verify item quantities before payment. Printing and hold actions are available below.
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <div class="grid gap-0 border-t border-emerald-200 bg-zinc-50 lg:grid-cols-[minmax(0,1fr)_auto]">
            <div class="grid gap-0 sm:grid-cols-2 xl:grid-cols-4">
                <flux:button variant="primary" class="h-18 rounded-none border-r border-zinc-200 text-base font-semibold">
                    Pay
                </flux:button>
                <flux:button variant="ghost" class="h-18 rounded-none border-t border-zinc-200 sm:border-t-0 sm:border-r text-base font-semibold">
                    Print Receipt
                </flux:button>
                <flux:button variant="ghost" class="h-18 rounded-none border-t border-zinc-200 xl:border-t-0 xl:border-r text-base font-semibold">
                    Hold Transaction
                </flux:button>
                <flux:button variant="ghost" class="h-18 rounded-none border-t border-zinc-200 xl:border-t-0 text-base font-semibold">
                    New Sale
                </flux:button>
            </div>

            <div class="grid gap-0 sm:grid-cols-2 lg:min-w-[320px]">
                <flux:button variant="subtle" class="h-18 rounded-none border-t border-zinc-200 lg:border-t-0 lg:border-l text-base font-semibold">
                    Discount
                </flux:button>
                <flux:button variant="subtle" class="h-18 rounded-none border-t border-zinc-200 sm:border-l lg:border-l text-base font-semibold">
                    More Actions
                </flux:button>
            </div>
        </div>
    </div>
</div>
