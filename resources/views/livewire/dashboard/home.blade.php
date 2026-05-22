<div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <flux:heading size="xl" level="1">Sales Analytics</flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                Revenue, sales movement, and top products for the selected period.
            </flux:text>
        </div>

        <flux:field class="w-full md:w-56">
            <flux:label>Filter</flux:label>
            <flux:select wire:model.live="analyticsPeriod">
                <flux:select.option value="daily">Daily</flux:select.option>
                <flux:select.option value="weekly">Weekly</flux:select.option>
                <flux:select.option value="monthly">Monthly</flux:select.option>
                <flux:select.option value="yearly">Yearly</flux:select.option>
            </flux:select>
        </flux:field>
    </div>

    <div class="grid gap-6 lg:grid-cols-3" wire:poll.5s="refreshAnalytics">
        <flux:card  class="border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 transition-transform duration-300 hover:scale-105">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Total sales</flux:text>
                    <div class="mt-2 text-3xl font-semibold tabular-nums text-primary">
                        &#8369; {{ number_format($totalSales) }}
                    </div>
                </div>
                <div class="flex size-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                    <flux:icon.banknotes class="size-6 text-primary" />
                </div>
            </div>
        </flux:card>

        <flux:card class="border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 transition-transform duration-300 hover:scale-105">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Transactions</flux:text>
                    <div class="mt-2 text-3xl font-semibold tabular-nums text-zinc-900 dark:text-zinc-100">
                        {{ number_format($totalOrders) }}
                    </div>
                </div>
                <div class="flex size-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                    <flux:icon.receipt-percent class="size-6 text-zinc-700 dark:text-zinc-300" />
                </div>
            </div>
        </flux:card>

        <flux:card class="border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 transition-transform duration-300 hover:scale-105">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Compared with {{ strtolower($salesComparison['previous_label']) }}</flux:text>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-3xl font-semibold tabular-nums {{ $salesComparison['is_gain'] ? 'text-primary' : 'text-red-600' }}">
                            {{ $salesComparison['is_gain'] ? '+' : '-' }}{{ $salesComparison['percentage_change'] }}%
                        </span>
                        <flux:badge color="{{ $salesComparison['is_gain'] ? 'green' : 'red' }}">
                            {{ $salesComparison['is_gain'] ? 'Gain' : 'Loss' }}
                        </flux:badge>
                    </div>
                </div>
                <div class="flex size-12 items-center justify-center rounded-full {{ $salesComparison['is_gain'] ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                    @if ($salesComparison['is_gain'])
                        <flux:icon.arrow-trending-up class="size-6 text-primary" />
                    @else
                        <flux:icon.arrow-trending-down class="size-6 text-red-600" />
                    @endif
                </div>
            </div>
        </flux:card>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(320px,0.85fr)]">
        <flux:card class="border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <flux:heading size="lg">Revenue trend</flux:heading>
                    <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                        Per-period revenue for {{ $analyticsPeriod }} sales.
                    </flux:text>
                </div>
                <flux:badge color="green" variant="subtle">
                    &#8369; {{ number_format($totalSales) }}
                </flux:badge>
            </div>

            <div wire:ignore wire:loading.class="opacity-50" wire:target="analyticsPeriod" class="h-80">
                <canvas id="revenueTrendChart" class="size-full"></canvas>
            </div>
        </flux:card>

        <flux:card class="border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <flux:heading size="lg">Sales comparison</flux:heading>
                    <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                        {{ $salesComparison['current_label'] }} vs {{ $salesComparison['previous_label'] }}.
                    </flux:text>
                </div>
                <flux:icon.presentation-chart-bar class="size-8 text-primary" />
            </div>

            <div wire:ignore wire:loading.class="opacity-50" wire:target="analyticsPeriod" class="h-80">
                <canvas id="salesComparisonChart" class="size-full"></canvas>
            </div>
        </flux:card>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(320px,0.8fr)]">
        <flux:card class="border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-5 flex items-start justify-between gap-1">
                <div>
                    <flux:heading size="lg">Top 10 highest selling products</flux:heading>
                    <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
                        Ranked by quantity sold in the selected period.
                    </flux:text>
                </div>
                <flux:icon.chart-bar class="size-8 text-primary" />
            </div>

            @if ($topSellingProducts)
                <div wire:ignore wire:loading.class="opacity-50" wire:target="analyticsPeriod" class="h-[13rem]">
                    <canvas id="topProductsChart" class="size-full"></canvas>
                </div>
            @else
                <div class="rounded-lg border border-dashed border-zinc-300 px-6 py-10 text-center dark:border-zinc-700">
                    <flux:text class="text-zinc-500 dark:text-zinc-400">No product sales for this period.</flux:text>
                </div>
            @endif
        </flux:card>

        <flux:card class="border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-5">
                <flux:heading size="lg">Inventory summary</flux:heading>
                <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">Current stock overview.</flux:text>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between gap-4 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700 transition-transform duration-300 hover:scale-105">
                    <div>
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Total products</flux:text>
                        <div class="mt-1 text-2xl font-semibold text-primary">{{ $totalProducts ?? '0' }}</div>
                    </div>
                    <div class="flex size-11 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                        <flux:icon.cube class="size-6 text-primary" />
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700 transition-transform duration-300 hover:scale-105">
                    <div>
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Low stock items</flux:text>
                        <div class="mt-1 text-2xl font-semibold text-red-600">{{ $lowStockItems ?? '0' }}</div>
                    </div>
                    <div class="flex size-11 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                        <flux:icon.exclamation-triangle class="size-6 text-red-600" />
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700 transition-transform duration-300 hover:scale-105">
                    <div>
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Total inventory value</flux:text>
                        <div class="mt-1 text-2xl font-semibold text-primary">&#8369; {{ number_format($totalInventoryValue) }}</div>
                    </div>
                    <div class="flex size-11 items-center justify-center rounded-full bg-green-100 text-2xl text-primary dark:bg-green-900/30">
                        &#8369;
                    </div>
                </div>
            </div>
        </flux:card>
    </div>
</div>

@script
<script>
(() => {
    const pesoFormatter = new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        maximumFractionDigits: 0,
    });

    const chartColors = {
        primary: '#16a34a',
        primarySoft: 'rgba(22, 163, 74, 0.14)',
        zinc: '#71717a',
        grid: 'rgba(113, 113, 122, 0.16)',
        text: '#3f3f46',
        red: '#dc2626',
    };

    let revenueTrendChart;
    let salesComparisonChart;
    let topProductsChart;

    const destroyChart = (chart) => {
        if (chart) {
            chart.destroy();
        }
    };

    const baseOptions = () => ({
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: chartColors.text,
                    boxWidth: 12,
                    boxHeight: 12,
                },
            },
            tooltip: {
                callbacks: {
                    label: (context) => `${context.dataset.label}: ${pesoFormatter.format(context.parsed.y ?? context.parsed.x ?? 0)}`,
                },
            },
        },
        scales: {
            x: {
                ticks: {
                    color: chartColors.zinc,
                    maxRotation: 0,
                    autoSkip: true,
                },
                grid: {
                    display: false,
                },
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: chartColors.zinc,
                    callback: (value) => pesoFormatter.format(value),
                },
                grid: {
                    color: chartColors.grid,
                },
            },
        },
    });

    const renderAnalyticsCharts = (charts) => {
        if (!window.Chart || !charts) {
            return;
        }

        const revenueCanvas = document.getElementById('revenueTrendChart');
        const comparisonCanvas = document.getElementById('salesComparisonChart');
        const productsCanvas = document.getElementById('topProductsChart');

        destroyChart(revenueTrendChart);
        destroyChart(salesComparisonChart);
        destroyChart(topProductsChart);

        if (revenueCanvas) {
            revenueTrendChart = new window.Chart(revenueCanvas, {
                type: 'line',
                data: {
                    labels: charts.revenue.labels,
                    datasets: [{
                        label: 'Revenue',
                        data: charts.revenue.values,
                        borderColor: chartColors.primary,
                        backgroundColor: chartColors.primarySoft,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    }],
                },
                options: baseOptions(),
            });
        }

        if (comparisonCanvas) {
            salesComparisonChart = new window.Chart(comparisonCanvas, {
                type: 'bar',
                data: {
                    labels: charts.comparison.labels,
                    datasets: [{
                        label: 'Sales',
                        data: charts.comparison.values,
                        backgroundColor: [chartColors.zinc, chartColors.primary],
                        borderRadius: 8,
                        maxBarThickness: 80,
                    }],
                },
                options: {
                    ...baseOptions(),
                    plugins: {
                        ...baseOptions().plugins,
                        legend: {
                            display: false,
                        },
                    },
                },
            });
        }

        if (productsCanvas) {
            topProductsChart = new window.Chart(productsCanvas, {
                type: 'bar',
                data: {
                    labels: charts.topProducts.labels,
                    datasets: [
                        {
                            label: 'Quantity sold',
                            data: charts.topProducts.quantities,
                            backgroundColor: chartColors.primary,
                            borderRadius: 6,
                            maxBarThickness: 24,
                        },
                        {
                            label: 'Revenue',
                            data: charts.topProducts.revenues,
                            backgroundColor: 'rgba(113, 113, 122, 0.55)',
                            borderRadius: 6,
                            maxBarThickness: 24,
                            hidden: true,
                        },
                    ],
                },
                options: {
                    ...baseOptions(),
                    indexAxis: 'y',
                    plugins: {
                        ...baseOptions().plugins,
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    if (context.dataset.label === 'Revenue') {
                                        return `Revenue: ${pesoFormatter.format(context.parsed.x ?? 0)}`;
                                    }

                                    return `Quantity sold: ${context.parsed.x ?? 0}`;
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                color: chartColors.zinc,
                            },
                            grid: {
                                color: chartColors.grid,
                            },
                        },
                        y: {
                            ticks: {
                                color: chartColors.zinc,
                            },
                            grid: {
                                display: false,
                            },
                        },
                    },
                },
            });
        }
    };

    renderAnalyticsCharts(@js($analyticsCharts));

    if (window.coaftcAnalyticsChartListener) {
        window.removeEventListener('analytics-charts-updated', window.coaftcAnalyticsChartListener);
    }

    window.coaftcAnalyticsChartListener = (event) => {
        renderAnalyticsCharts(event.detail.charts);
    };

    window.addEventListener('analytics-charts-updated', window.coaftcAnalyticsChartListener);
})();
</script>
@endscript
