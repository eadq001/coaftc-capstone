<?php

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.dashboard')]
class extends Component {

    public $startDate = '2026-05-01';
    public $endDate = '2026-05-19';
    public ?Collection $result = null;
    public ?Collection $itemsByCategory = null;

    public function mount()
    {
        if ($this->startDate && $this->endDate) {
            $this->result = Sale::whereDate('created_at', '>=', $this->startDate)
                ->whereDate('created_at', '<=', $this->endDate)
                ->get();

            $this->itemsByCategory = $this->result
                ->flatMap
                ->salesItem
                ->groupBy(fn($item) => $item->product->category->category_name);
        }
    }
};
?>

<div>
    <div class="space-y-6">
        <section
                class="overflow-hidden rounded-[2rem] border border-emerald-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.10)]">
            <div class="grid gap-0 xl:grid-cols-1">
                <div class="relative border-b border-emerald-100 bg-linear-to-br from-emerald-50 via-white to-lime-50 px-6 py-8 sm:px-8 xl:border-r xl:border-b-0">
                    <div class="absolute top-0 right-0 h-44 w-44 rounded-full bg-emerald-200/40 blur-3xl"></div>
                    <div class="absolute bottom-0 left-10 h-28 w-28 rounded-full bg-lime-200/50 blur-2xl"></div>

                    <div class="relative max-w-3xl">
                        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Reports Center
                        </div>

                        <flux:heading size="xl" level="1" class="text-zinc-950">
                            Generate dated operational reports
                        </flux:heading>

                        <flux:text class="mt-3 max-w-2xl text-zinc-600">
                            Select a starting date and ending date, then generate a report when the backend logic is
                            ready.
                        </flux:text>

                        <div class="mt-8 grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] lg:items-end">
                            <flux:field>
                                <flux:label>Starting Date</flux:label>
                                <flux:input type="date" icon="calendar-days" wire:model="startDate"/>
                            </flux:field>

                            <flux:field>
                                <flux:label>Ending Date</flux:label>
                                <flux:input type="date" icon="calendar-days" wire:model="endDate"/>
                            </flux:field>

                            <flux:button type="button" variant="primary" icon="document-chart-bar" class="h-10"
                                         wire:click="getSalesReportToday">
                                Generate Report
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-[2rem] border border-zinc-200 bg-white shadow-sm">
            <div class="border-b border-zinc-200 px-6 py-4">
                <flux:heading size="lg">Inventory Report Preview</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-500">
                    Table layout for generated report results grouped by category.
                </flux:text>
            </div>

            <div class="overflow-x-auto">
                @if($result)
                    @foreach($itemsByCategory as $categoryItems)
                        @php
                            $category = $categoryItems->first()->product->category->category_name;
//                            dd($categoryItems);
                        @endphp
                            <table class="min-w-[1180px] w-full border-collapse text-sm mb-3">
                                <thead>
                                <tr class="bg-emerald-700 text-white">
                                    <th colspan="9"
                                        class="px-4 py-4 text-left text-sm font-semibold uppercase tracking-[0.18em]">
                                        {{ $category }}
                                    </th>
                                </tr>

                                <tr class="border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase tracking-[0.14em] text-zinc-500">
                                    <th class="px-4 py-3 text-left">PRF No.</th>
                                    <th class="px-4 py-3 text-left">Product Name</th>
                                    <th class="px-4 py-3 text-left">Subcategory</th>
                                    <th class="px-4 py-3 text-left">Quantity</th>
                                    <th class="px-4 py-3 text-left">Unit</th>
                                    <th class="px-4 py-3 text-left">Inventory Start</th>
                                    <th class="px-4 py-3 text-left">Inventory End</th>
                                    <th class="px-4 py-3 text-left">Price</th>
                                    <th class="px-4 py-3 text-left">Subtotal</th>
                                </tr>
                                </thead>

                                <tbody class="divide-y divide-zinc-100 bg-white">
                                @foreach($categoryItems as $item)
                                <tr class="transition hover:bg-emerald-50/60">
                                    <td class="px-4 py-4 font-medium text-zinc-900">{{ $item->sale->prf_number }}</td>
                                    <td class="px-4 py-4 text-zinc-800">{{ $item->product->name }}</td>
                                    <td class="px-4 py-4 text-zinc-600">{{ $item->product->subcategory->subcategory_name }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item->quantity }}</td>
                                    <td class="px-4 py-4 text-left text-zinc-600">{{ $item->product->unit->unit_name }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item->product->stock_level + $item->quantity }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item->product->stock_level }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item->unit_pricel }}</td>
                                    <td class="px-4 py-4 text-left font-semibold tabular-nums text-zinc-950">{{ $item->subtotal }}
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>

                                <tfoot>
                                <tr class="border-t-2 border-emerald-600 bg-emerald-50">
                                    <td colspan="8"
                                        class="px-4 py-4 text-left text-sm font-semibold uppercase tracking-[0.14em] text-emerald-800">
                                        Total
                                    </td>
                                    <td class="px-4 py-4 text-right text-base font-bold tabular-nums text-emerald-900">
                                        {{ $item->sale->total_amount }}
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                    @endforeach
                @endif

            </div>
        </section>
    </div>

</div>
