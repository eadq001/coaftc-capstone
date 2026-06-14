<?php

namespace App\Livewire\Dashboard;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class Home extends Component
{
    public string $username;

    public string $analyticsPeriod = 'daily';

    public int $totalProducts;

    public int $lowStockItems;

    public int $totalInventoryValue;

    public int $poultryLivestockValue;

    public int $otherProductsValue;

    public array $revenueTrend = [];

    public array $topSellingProducts = [];

    public array $salesComparison = [];

    public array $analyticsCharts = [];

    public int $totalSales = 0;

    public int $totalOrders = 0;

    public function mount(): void
    {
        $this->totalProducts = Product::count('name');
        $this->lowStockItems = Product::where('stock_level', '<', 20)->count();
        $this->calculateInventoryValues();
        $this->username = auth()->user()->name;
        $this->refreshAnalytics();
    }

    public function updatedAnalyticsPeriod(): void
    {
        if (! in_array($this->analyticsPeriod, ['daily', 'weekly', 'monthly', 'yearly'], true)) {
            $this->analyticsPeriod = 'daily';
        }

        $this->refreshAnalytics();
    }

    public function render(): View
    {
        return view('livewire.dashboard.home')->layout('layouts.dashboard');
    }

    public function refreshAnalytics(): void
    {
        [$startDate, $endDate] = $this->dateRange();
        [$previousStartDate, $previousEndDate] = $this->previousDateRange();

        $this->totalSales = (int) Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $this->totalOrders = Sale::whereBetween('created_at', [$startDate, $endDate])->count();
        $previousSales = (int) Sale::whereBetween('created_at', [$previousStartDate, $previousEndDate])->sum('total_amount');

        $this->revenueTrend = $this->buildRevenueTrend($startDate, $endDate);
        $this->topSellingProducts = $this->buildTopSellingProducts($startDate, $endDate);
        $this->salesComparison = $this->buildSalesComparison($previousSales);
        $this->analyticsCharts = $this->buildAnalyticsCharts();

        $this->dispatch('analytics-charts-updated', charts: $this->analyticsCharts);
    }

    private function calculateInventoryValues(): void
    {
        $products = Product::query()
            ->select('products.id', 'products.stock_level', 'products.price', 'categories.category_name')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->get();

        $this->totalInventoryValue = 0;
        $this->poultryLivestockValue = 0;
        $this->otherProductsValue = 0;

        foreach ($products as $product) {
            $value = $product->stock_level * $product->price;
            $this->totalInventoryValue += $value;

            $category = $product->category_name ? strtolower($product->category_name) : '';
            if (in_array($category, ['poultry', 'livestock'], true)) {
                $this->poultryLivestockValue += $value;
            } else {
                $this->otherProductsValue += $value;
            }
        }
    }

    private function dateRange(): array
    {
        return match ($this->analyticsPeriod) {
            'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            'yearly' => [now()->startOfYear(), now()->endOfYear()],
            default => [now()->startOfDay(), now()->endOfDay()],
        };
    }

    private function previousDateRange(): array
    {
        return match ($this->analyticsPeriod) {
            'weekly' => [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()],
            'monthly' => [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()],
            'yearly' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            default => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
        };
    }

    private function buildRevenueTrend(Carbon $startDate, Carbon $endDate): array
    {
        $groupFormat = $this->analyticsPeriod === 'daily' ? '%H' : ($this->analyticsPeriod === 'yearly' ? '%Y-%m' : '%Y-%m-%d');
        $revenues = Sale::query()
            ->selectRaw("DATE_FORMAT(created_at, '{$groupFormat}') as period")
            ->selectRaw('sum(total_amount) as revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('period')
            ->pluck('revenue', 'period');

        $points = $this->emptyRevenuePoints($startDate, $endDate)
            ->map(function (array $point) use ($revenues): array {
                $revenue = (int) ($revenues[$point['key']] ?? 0);

                return [
                    ...$point,
                    'revenue' => $revenue,
                    'formatted_revenue' => number_format($revenue),
                ];
            });

        $highestRevenue = max($points->max('revenue'), 1);

        return $points
            ->map(fn (array $point): array => [
                ...$point,
                'height' => max(4, (int) round(($point['revenue'] / $highestRevenue) * 100)),
            ])
            ->all();
    }

    private function emptyRevenuePoints(Carbon $startDate, Carbon $endDate): Collection
    {
        if ($this->analyticsPeriod === 'daily') {
            return collect(range(0, 23))->map(fn (int $hour): array => [
                'key' => str_pad((string) $hour, 2, '0', STR_PAD_LEFT),
                'label' => Carbon::createFromTime($hour)->format('g A'),
            ]);
        }

        if ($this->analyticsPeriod === 'yearly') {
            return collect(range(1, 12))->map(fn (int $month): array => [
                'key' => now()->month($month)->format('Y-m'),
                'label' => now()->month($month)->format('M'),
            ]);
        }

        return collect(CarbonPeriod::create($startDate->copy()->startOfDay(), $endDate->copy()->startOfDay()))
            ->map(fn (Carbon $date): array => [
                'key' => $date->format('Y-m-d'),
                'label' => $date->format('M j'),
            ]);
    }

    private function buildTopSellingProducts(Carbon $startDate, Carbon $endDate): array
    {
        $products = SalesItem::query()
            ->join('sales', 'sales.id', '=', 'sales_items.sale_id')
            ->join('products', 'products.id', '=', 'sales_items.product_id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->groupBy('sales_items.product_id', 'products.name')
            ->orderByDesc(DB::raw('sum(sales_items.quantity)'))
            ->limit(10)
            ->get([
                'products.name',
                DB::raw('sum(sales_items.quantity) as quantity_sold'),
                DB::raw('sum(sales_items.subtotal) as revenue'),
            ]);

        $highestQuantity = max((int) $products->max('quantity_sold'), 1);

        return $products
            ->map(fn (SalesItem $product): array => [
                'name' => $product->name,
                'quantity_sold' => (int) $product->quantity_sold,
                'revenue' => (int) $product->revenue,
                'formatted_revenue' => number_format((int) $product->revenue),
                'width' => max(4, (int) round(((int) $product->quantity_sold / $highestQuantity) * 100)),
            ])
            ->all();
    }

    private function buildSalesComparison(int $previousSales): array
    {
        $difference = $this->totalSales - $previousSales;
        $percentageChange = $previousSales > 0 ? ($difference / $previousSales) * 100 : ($this->totalSales > 0 ? 100 : 0);
        $highestSales = max($this->totalSales, $previousSales, 1);

        return [
            'current_label' => match ($this->analyticsPeriod) {
                'weekly' => 'This week',
                'monthly' => 'This month',
                'yearly' => 'This year',
                default => 'Today',
            },
            'previous_label' => match ($this->analyticsPeriod) {
                'weekly' => 'Last week',
                'monthly' => 'Last month',
                'yearly' => 'Last year',
                default => 'Yesterday',
            },
            'current_sales' => $this->totalSales,
            'previous_sales' => $previousSales,
            'formatted_current_sales' => number_format($this->totalSales),
            'formatted_previous_sales' => number_format($previousSales),
            'difference' => $difference,
            'percentage_change' => number_format(abs($percentageChange), 1),
            'is_gain' => $difference >= 0,
            'current_height' => max(4, (int) round(($this->totalSales / $highestSales) * 100)),
            'previous_height' => max(4, (int) round(($previousSales / $highestSales) * 100)),
            'multi_year' => $this->analyticsPeriod === 'yearly' ? $this->buildYearlySalesComparison() : [],
        ];
    }

    private function buildYearlySalesComparison(): array
    {
        $years = collect(range(0, 4))->map(fn (int $i): int => (int) now()->subYears($i)->format('Y'));

        $yearlySales = Sale::query()
            ->selectRaw('YEAR(created_at) as year')
            ->selectRaw('sum(total_amount) as total')
            ->whereYear('created_at', '>=', $years->last())
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('total', 'year');

        $highest = max($yearlySales->max() ?? 1, 1);

        return $years
            ->sort()
            ->values()
            ->map(fn (int $year): array => [
                'year' => (string) $year,
                'total' => (int) ($yearlySales[$year] ?? 0),
                'formatted_total' => number_format((int) ($yearlySales[$year] ?? 0)),
                'height' => max(4, (int) round((($yearlySales[$year] ?? 0) / $highest) * 100)),
            ])
            ->all();
    }

    private function buildAnalyticsCharts(): array
    {
        $comparison = $this->analyticsPeriod === 'yearly'
            ? [
                'labels' => collect($this->salesComparison['multi_year'])->pluck('year')->all(),
                'values' => collect($this->salesComparison['multi_year'])->pluck('total')->all(),
            ]
            : [
                'labels' => [
                    $this->salesComparison['previous_label'],
                    $this->salesComparison['current_label'],
                ],
                'values' => [
                    $this->salesComparison['previous_sales'],
                    $this->salesComparison['current_sales'],
                ],
            ];

        return [
            'revenue' => [
                'labels' => collect($this->revenueTrend)->pluck('label')->all(),
                'values' => collect($this->revenueTrend)->pluck('revenue')->all(),
            ],
            'topProducts' => [
                'labels' => collect($this->topSellingProducts)->pluck('name')->all(),
                'quantities' => collect($this->topSellingProducts)->pluck('quantity_sold')->all(),
                'revenues' => collect($this->topSellingProducts)->pluck('revenue')->all(),
            ],
            'comparison' => $comparison,
        ];
    }
}
