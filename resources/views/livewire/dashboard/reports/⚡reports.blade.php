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
    public ?Collection $totalSalesPerCategory = null;
    public string $searchText = '';

    public function mount(): void
    {
        $this->getSalesReportToday();
    }

    public function getSalesReportToday()
    {
        if ($this->startDate && $this->endDate) {
            $this->result = Sale::whereDate('created_at', '>=', $this->startDate)
                ->whereDate('created_at', '<=', $this->endDate)
                ->get();

            $this->itemsByCategory = $this->result
                ->flatMap
                ->salesItem
                ->groupBy(fn($item) => $item->sale->created_at->format('Y-m-d'));

            $this->totalSalesPerCategory = $this->itemsByCategory
                ->flatMap
                ->groupBy(fn($item) => $item->product->category->category_name);
        }
//            dd($this->result, $this->totalSalesPerCategory);
    }

    public function updatedSearchText()
    {

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

                    <div class="relative w-full">
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

                        <div class="mt-8 grid gap-4 grid-cols-6 lg:items-end">

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
                            <flux:button type="button" variant="primary" icon="document-chart-bar" class="h-10"
                                         wire:click="">
                                Generate Daily Report
                            </flux:button>
                            <flux:button type="button" variant="primary" icon="document-chart-bar" class="h-10"
                                         wire:click="">
                                Generate Monthly Report
                            </flux:button>
                            <flux:button type="button" variant="primary" icon="document-chart-bar" class="h-10"
                                         wire:click="">
                                Generate Yearly Report
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        @if($result)
            <flux:input
                    class="flex-start"
                    icon="magnifying-glass"
                    placeholder="Search by product name..."
                    autocomplete="off"
                    wire:model.live.debounce.500ms="searchText"
            />
            <section class="overflow-hidden rounded-[2rem] border border-zinc-200 bg-white shadow-sm">

                @foreach($itemsByCategory as $key => $saleDate)
                    {{--                    @php--}}
                    {{--                        dd($itemsByCategory);--}}
                    {{--                    @endphp--}}
                    <div class="overflow-x-auto">
                        <table class="min-w-[1180px] w-full border-collapse text-sm">
                            <thead>
                            <tr class="bg-emerald-700 text-white">
                                <th colspan="10"
                                    class="px-4 py-4 text-left text-sm font-semibold uppercase tracking-[0.18em]">
                                    <div class="space-x-8">
                                        {{ date_format(date_create($key), 'F j, Y') }}
                                    </div>
                                </th>
                            </tr>
                            <tr class="border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase tracking-[0.14em] text-zinc-500">
                                <th class="px-4 py-3 text-left">PRF No.</th>
                                <th class="px-4 py-3 text-left">Product Name</th>
                                <th class="px-4 py-3 text-left">Subcategory</th>
                                <th class="px-4 py-3 text-left">Quantity</th>
                                <th class="px-4 py-3 text-left">Unit</th>
                                <th class="px-4 py-3 text-left">Start</th>
                                <th class="px-4 py-3 text-left">End</th>
                                <th class="px-4 py-3 text-left">Price</th>
                                <th class="px-4 py-3 text-left">Subtotal</th>
                                <th class="px-4 py-3 text-left">Associate</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-zinc-100 bg-white">
                            @foreach($saleDate as $item)
                                <tr class="transition hover:bg-emerald-50/60">
                                    <td class="px-4 py-4 font-medium text-zinc-900">{{ $item->sale->prf_number }}</td>
                                    <td class="px-4 py-4 text-zinc-800">{{ $item->product->name }}</td>
                                    <td class="px-4 py-4 text-zinc-600">{{ $item->product->category->category_name }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item->quantity }}</td>
                                    <td class="px-4 py-4 text-left text-zinc-600">{{ $item->product->unit->unit_name }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item->inventory_start }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item->inventory_end }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item->unit_price }}</td>
                                    <td class="px-4 py-4 text-left font-semibold tabular-nums text-zinc-950">{{ $item->subtotal }}</td>
                                    <td class="px-4 py-4 text-zinc-600">
                                        @php
                                            $salesTotal = $saleDate->sum('subtotal');
                                        @endphp
                                        <span>{{ $item->sale->user->name }}</span>
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
                                <td colspan="3"
                                    class="px-4 py-4 text-left text-base font-bold tabular-nums text-emerald-900">
                                    {{ $salesTotal }}
                                </td>
                            </tr>
                            </tfoot>

                            <table class="min-w-[1180px] w-full border-collapse text-sm flex items-center">
{{--                                Controller --}}
                                @foreach($saleDate->groupBy(fn ($item) => $item->product->category->category_name)
                                    as $categoryName => $categoryItems)
{{--                                    @php--}}
{{--                                        dd($categoryName, $categoryItems)--}}
{{--                                    @endphp--}}
                                    <thead>
                                        <tr class="border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase tracking-[0.14em] text-zinc-500">
                                            <th class="px-4 py-3 text-left">{{ $categoryName }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr class="transition hover:bg-emerald-50/60">
                                            <td class="px-4 py-4 font-medium text-zinc-900">{{ $categoryItems->sum("subtotal")}}</td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        @endforeach
                        </table>
                        @endif

                    </div>
            </section>
    </div>

</div>
