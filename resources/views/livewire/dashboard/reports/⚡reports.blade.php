<?php

use App\Exports\DailySalesReportExport;
use App\Exports\SalesSummaryReportExport;
use App\Models\Dispersal;
use App\Models\Sale;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

new #[Layout('layouts.dashboard')]
class extends Component {

    public string $startDate = '';
    public string $endDate = '';
    public ?Collection $result = null;
    public ?Collection $itemsByCategory = null;
    public string $searchText = '';
    public array $availableReportMonths = [];
    public array $availableReportYears = [];

    public function mount(): void
    {
         $this->startDate = now()->format('Y-m-d');
         $this->endDate = now()->format('Y-m-d');
        $this->getSalesReportToday();
        $this->loadReportPeriods();
    }

    public function getSalesReportToday(): void
    {
        if ($this->startDate && $this->endDate) {
            $searchText = trim($this->searchText);

            // Fetch sales
            $this->result = Sale::query()
                ->whereDate('created_at', '>=', $this->startDate)
                ->whereDate('created_at', '<=', $this->endDate)
                ->when($searchText !== '', function ($query) use ($searchText) {
                    $query->whereHas('salesItem.product', function ($query) use ($searchText) {
                        $query->where('name', 'like', "%{$searchText}%");
                    });
                })
                ->with([
                    'salesItem' => function ($query) use ($searchText) {
                        $query
                            ->when($searchText !== '', function ($query) use ($searchText) {
                                $query->whereHas('product', function ($query) use ($searchText) {
                                    $query->where('name', 'like', "%{$searchText}%");
                                });
                            })
                            ->with(['product.category', 'product.unit', 'sale.user']);
                    },
                ])
                ->get();

            // Fetch dispersals
            $dispersals = Dispersal::query()
                ->whereDate('created_at', '>=', $this->startDate)
                ->whereDate('created_at', '<=', $this->endDate)
                ->when($searchText !== '', function ($query) use ($searchText) {
                    $query->whereHas('dispersalItems.product', function ($query) use ($searchText) {
                        $query->where('name', 'like', "%{$searchText}%");
                    });
                })
                ->with([
                    'dispersalItems' => function ($query) use ($searchText) {
                        $query
                            ->when($searchText !== '', function ($query) use ($searchText) {
                                $query->whereHas('product', function ($query) use ($searchText) {
                                    $query->where('name', 'like', "%{$searchText}%");
                                });
                            })
                            ->with(['product.category', 'product.unit', 'dispersal.user']);
                    },
                ])
                ->get();

            // Transform sales items to unified format
            $salesItems = $this->result->flatMap->salesItem->map(function ($item) {
                return [
                    'transaction_number' => $item->sale->prf_number,
                    'product_name' => $item->product->name,
                    'category_name' => $item->product->category?->category_name ?? 'Uncategorized',
                    'quantity' => $item->quantity,
                    'unit_name' => $item->product->unit?->unit_name ?? '',
                    'class' => $item->product->class?->value ?? '',
                    'size' => $item->product->size ?? '',
                    'inventory_start' => $item->inventory_start,
                    'inventory_end' => (string) $item->inventory_end ?? '0',
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                    'remarks' => '',
                    'user_name' => $item->sale->user?->name ?? 'N/A',
                    'created_at' => $item->sale->created_at,
                    'type' => 'sale',
                ];
            });

            // Transform dispersal items to unified format
            $dispersalItems = $dispersals->flatMap->dispersalItems->map(function ($item) {
                return [
                    'transaction_number' => $item->dispersal->dispersal_number,
                    'product_name' => $item->product->name,
                    'category_name' => $item->product->category?->category_name ?? 'Uncategorized',
                    'quantity' => $item->quantity,
                    'unit_name' => $item->product->unit?->unit_name ?? '',
                    'class' => $item->class ?? '',
                    'size' => $item->product->size ?? '',
                    'inventory_start' => $item->inventory_start,
                    'inventory_end' => (string) $item->inventory_end ?? '0',
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                    'remarks' => $item->dispersal->remarks ?? '',
                    'user_name' => $item->dispersal->user?->name ?? 'N/A',
                    'created_at' => $item->dispersal->created_at,
                    'type' => 'dispersal',
                ];
            });

            // Combine and group by date
            $this->itemsByCategory = $salesItems->merge($dispersalItems)
                ->groupBy(fn ($item) => $item['created_at']->format('Y-m-d'));
        }
    }

    public function exportToExcel(): BinaryFileResponse
    {
        $date = now()->format('Ymd');
        $this->getSalesReportToday();

        return Excel::download(
            new DailySalesReportExport($this->itemsByCategory ?? collect()),
            "daily-sales-report-$date.xlsx"
        );
    }

    public function loadReportPeriods(): void
    {
        $salesDates = Sale::query()
            ->whereHas('salesItem')
            ->oldest('created_at')
            ->get(['created_at'])
            ->pluck('created_at');

        $this->availableReportMonths = $salesDates
            ->map(fn ($createdAt) => $createdAt->format('Y-m'))
            ->unique()
            ->sort()
            ->map(fn (string $month) => [
                'value' => $month,
                'label' => CarbonImmutable::createFromFormat('Y-m', $month)->format('F Y'),
            ])
            ->values()
            ->all();

        $this->availableReportYears = $salesDates
            ->map(fn ($createdAt) => $createdAt->format('Y'))
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    public function downloadMonthlyReport(string $month): ?BinaryFileResponse
    {
        $monthParts = $this->parseReportMonth($month);

        if ($monthParts === null) {
            return null;
        }

        [$year, $monthNumber] = $monthParts;
        $items = $this->salesItemsForPeriod($year, $monthNumber);

        if ($items->isEmpty()) {
            $this->loadReportPeriods();

            return null;
        }

        return Excel::download(
            new SalesSummaryReportExport(collect([$month => $items])),
            "monthly-sales-report-{$year}-" . str_pad((string) $monthNumber, 2, '0', STR_PAD_LEFT) . '.xlsx'
        );
    }

    public function downloadYearlyReport(string $year): ?BinaryFileResponse
    {
        $reportYear = $this->parseReportYear($year);

        if ($reportYear === null) {
            return null;
        }

        $items = $this->salesItemsForPeriod($reportYear);

        if ($items->isEmpty()) {
            $this->loadReportPeriods();

            return null;
        }

        return Excel::download(
            new SalesSummaryReportExport(
                $items
                    ->groupBy(fn ($item) => $item->sale->created_at->format('Y-m'))
                    ->sortKeys(),
                true
            ),
            "yearly-sales-report-{$reportYear}.xlsx"
        );
    }

    public function updatedSearchText(): void
    {
        $this->getSalesReportToday();
    }

    public function updatedStartDate(): void
    {
        $this->getSalesReportToday();
    }

    public function updatedEndDate(): void
    {
        $this->getSalesReportToday();
    }

    private function salesItemsForPeriod(int $year, ?int $month = null): Collection
    {
        return Sale::query()
            ->whereYear('created_at', $year)
            ->when($month !== null, fn ($query) => $query->whereMonth('created_at', $month))
            ->whereHas('salesItem')
            ->with(['salesItem.product.category', 'salesItem.sale'])
            ->oldest('created_at')
            ->get()
            ->flatMap
            ->salesItem
            ->values();
    }

    /**
     * @return array{0: int, 1: int}|null
     */
    private function parseReportMonth(string $month): ?array
    {
        if (! preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {
            return null;
        }

        return [
            (int) substr($month, 0, 4),
            (int) substr($month, 5, 2),
        ];
    }

    private function parseReportYear(string $year): ?int
    {
        if (! preg_match('/^\d{4}$/', $year)) {
            return null;
        }

        return (int) $year;
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

                        <div class="mt-8 flex gap-4 lg:items-end">
                            <flux:field>
                                <flux:label>Starting Date</flux:label>
                                <flux:input type="date" icon="calendar-days" wire:model.live="startDate"/>
                            </flux:field>

                            <flux:field>
                                <flux:label>Ending Date</flux:label>
                                <flux:input type="date" icon="calendar-days" wire:model.live="endDate"/>
                            </flux:field>

{{--                            <flux:button type="button" variant="primary" icon="document-chart-bar" class="h-10"--}}
{{--                                         wire:click="getSalesReportToday">--}}
{{--                                Generate Report--}}
{{--                            </flux:button>--}}

                            <flux:modal.trigger name="monthly-sales-report">
                                <flux:button type="button" variant="primary" icon="document-chart-bar" class="h-10"
                                             wire:click="loadReportPeriods">
                                    Generate Monthly Sales
                                </flux:button>
                            </flux:modal.trigger>

                            <flux:modal.trigger name="yearly-sales-report">
                                <flux:button type="button" variant="primary" icon="document-chart-bar" class="h-10"
                                             wire:click="loadReportPeriods">
                                    Generate Yearly Report
                                </flux:button>
                            </flux:modal.trigger>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <flux:modal name="monthly-sales-report" class="min-w-[26rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Select month</flux:heading>
                </div>

                @if($availableReportMonths)
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach($availableReportMonths as $month)
                            <flux:modal.close>
                                <flux:button
                                        type="button"
                                        variant="ghost"
                                        icon="calendar-days"
                                        class="w-full justify-start"
                                        wire:key="monthly-sales-report-{{ $month['value'] }}"
                                        wire:click="downloadMonthlyReport('{{ $month['value'] }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="downloadMonthlyReport"
                                >
                                    {{ $month['label'] }}
                                </flux:button>
                            </flux:modal.close>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-500">
                        No sales months available.
                    </div>
                @endif
            </div>
        </flux:modal>

        <flux:modal name="yearly-sales-report" class="min-w-[26rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Select year</flux:heading>
                </div>

                @if($availableReportYears)
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach($availableReportYears as $year)
                            <flux:modal.close>
                                <flux:button
                                        type="button"
                                        variant="ghost"
                                        icon="calendar-days"
                                        class="w-full justify-start"
                                        wire:key="yearly-sales-report-{{ $year }}"
                                        wire:click="downloadYearlyReport('{{ $year }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="downloadYearlyReport"
                                >
                                    {{ $year }}
                                </flux:button>
                            </flux:modal.close>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-500">
                        No sales years available.
                    </div>
                @endif
            </div>
        </flux:modal>


        @if($result)
            <div class="flex gap-2" >
                <flux:input
                        icon="magnifying-glass"
                        placeholder="Search by product name..."
                        autocomplete="off"
                        wire:model.live.debounce.500ms="searchText"
                />
                <button type="button" variant="primary" class="h-10 bg-white p-3 flex items-center rounded-lg disabled:bg-gray-200 transition cursor-pointer" {{ $searchText ? '' : 'disabled' }}
                             wire:click="$set('searchText', '')">
                    Clear
                </button>
                <flux:button type="button" variant="primary" icon="document-chart-bar" class="h-10"
                             wire:click="exportToExcel">
                    Export
                </flux:button>
            </div>

            <section class="overflow-hidden rounded-[2rem] border border-zinc-200 bg-white shadow-sm" >
                @forelse($itemsByCategory as $key => $saleDate)
                    @php
                        $salesTotal = $saleDate->sum('subtotal');
                    @endphp

                    <div class="overflow-x-hidden">
                        <table class="min-w-[1180px] w-full border-collapse text-sm" wire:target="getSalesReportToday" wire:loading.delay.longest.class="opacity-40">
                            <thead>
                            <tr class="bg-emerald-700 text-white">
                                <th colspan="13"
                                    class="px-4 py-4 text-left text-sm font-semibold uppercase tracking-[0.18em]">
                                    <div class="space-x-8">
                                        {{ date_format(date_create($key), 'F j, Y') }}
                                    </div>
                                </th>
                            </tr>
                            <tr class="border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase tracking-[0.14em] text-zinc-500">
                                <th class="px-4 py-3 text-left">Ref No.</th>
                                <th class="px-4 py-3 text-left">Product Name</th>
                                <th class="px-4 py-3 text-left">Category</th>
                                <th class="px-4 py-3 text-left">Qty</th>
                                <th class="px-4 py-3 text-left">Unit</th>
                                <th class="px-4 py-3 text-left">Class</th>
                                <th class="px-4 py-3 text-left">Size</th>
                                <th class="px-4 py-3 text-left">Start</th>
                                <th class="px-4 py-3 text-left">End</th>
                                <th class="px-4 py-3 text-left">Price</th>
                                <th class="px-4 py-3 text-left">Subtotal</th>
                                <th class="px-4 py-3 text-left">Remarks</th>
                                <th class="px-4 py-3 text-left">Associate</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-zinc-100 bg-white">
                            @foreach($saleDate as $item)
                                <tr class="transition hover:bg-emerald-50/60">
                                    <td class="px-4 py-4 font-medium text-zinc-900">{{ $item['transaction_number'] }}</td>
                                    <td class="px-4 py-4 text-zinc-800">{{ $item['product_name'] }}</td>
                                    <td class="px-4 py-4 text-zinc-600">{{ $item['category_name'] ?? 'Uncategorized' }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item['quantity'] }}</td>
                                    <td class="px-4 py-4 text-left text-zinc-600">{{ $item['unit_name'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-4 text-left text-zinc-600">{{ $item['class'] ?? '' }}</td>
                                    <td class="px-4 py-4 text-left text-zinc-600">{{ $item['size'] ?? '' }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item['inventory_start'] }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item['inventory_end'] }}</td>
                                    <td class="px-4 py-4 text-left tabular-nums text-zinc-700">{{ $item['unit_price'] }}</td>
                                    <td class="px-4 py-4 text-left font-semibold tabular-nums text-zinc-950">{{ $item['subtotal'] }}</td>
                                    <td class="px-4 py-4 text-left text-zinc-600">{{ $item['remarks'] }}</td>
                                    <td class="px-4 py-4 text-zinc-600">
                                        <span>{{ $item['user_name'] ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>

                            <tfoot>
                            <tr class="border-t-2 border-emerald-600 bg-emerald-50">
                                <td colspan="10"
                                    class="px-4 py-4 text-left text-sm font-semibold uppercase tracking-[0.14em] text-emerald-800">
                                    Total
                                </td>
                                <td colspan="3"
                                    class="px-4 py-4 text-left text-base font-bold tabular-nums text-emerald-900">
                                    {{ $salesTotal }}
                                </td>
                            </tr>
                            </tfoot>
                        </table>

                        <div class="p-4 flex gap-4 text-sm ">
                        @foreach($saleDate->groupBy(fn($item) => $item['category_name'] ?? 'Uncategorized') as $category => $item)
                        <span class="text-zinc-900 bg-green-200 rounded-lg p-2">
                            <span>{{ $category . ':'}}</span>
                            <span>{{ $item->sum('subtotal') }}</span>
                        </span>
                        @endforeach
                        </div>
                    </div>

                @empty
                    <div class="px-6 py-10 text-center text-sm text-zinc-500">
                        No sales found for the selected date range and product search.
                    </div>
                @endforelse
            </section>
        @endif
    </div>

</div>
