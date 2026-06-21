@extends('layouts.dashboard')

@section('title', 'Grafik Transaksi Harian - Semua Outlet')

@section('content')
<section class="mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Grafik Transaksi Harian</h1>
            <p class="text-sm text-taupe">Perbandingan kinerja transaksi harian antar outlet</p>
        </div>
        <a href="{{ route('franchisor.financial.outlets_today') }}" class="premium-button-soft text-sm">
            Pendapatan Hari Ini
        </a>
    </div>
</section>

{{-- Filter Form --}}
<section class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('franchisor.financial.daily') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-sm font-semibold mb-1 text-ink">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ $startDate->toDateString() }}" class="premium-input" />
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1 text-ink">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ $endDate->toDateString() }}" class="premium-input" />
        </div>
        <div class="flex gap-3">
            <button type="submit" class="premium-button">
                Tampilkan
            </button>
            <a href="{{ route('franchisor.financial.daily') }}" class="premium-button-soft">
                Reset
            </a>
        </div>
    </form>
</section>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
        <p class="text-xs uppercase tracking-wide text-taupe">Total Income</p>
        <p class="mt-2 text-2xl font-bold text-green-600">Rp {{ number_format($grandIncome, 0, ',', '.') }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
        <p class="text-xs uppercase tracking-wide text-taupe">Total Expense</p>
        <p class="mt-2 text-2xl font-bold text-red-600">Rp {{ number_format($grandExpense, 0, ',', '.') }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
        <p class="text-xs uppercase tracking-wide text-taupe">Total Profit</p>
        <p class="mt-2 text-2xl font-bold {{ $grandProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
            Rp {{ number_format($grandProfit, 0, ',', '.') }}
        </p>
    </div>
</div>

{{-- Income Bar Chart --}}
<section class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold">Income Harian per Outlet</h2>
        <p class="text-xs text-taupe">Grafik batang membandingkan pendapatan harian tiap outlet</p>
    </div>
    <div class="overflow-x-auto">
        <div class="min-w-[700px]">
            <canvas id="incomeChart" height="240" role="img" aria-label="Grafik batang income harian per outlet"></canvas>
        </div>
    </div>
</section>

{{-- Expense Line Chart --}}
<section class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold">Expense Harian per Outlet</h2>
        <p class="text-xs text-taupe">Grafik garis menunjukkan tren pengeluaran harian tiap outlet</p>
    </div>
    <div class="overflow-x-auto">
        <div class="min-w-[700px]">
            <canvas id="expenseChart" height="240" role="img" aria-label="Grafik garis expense harian per outlet"></canvas>
        </div>
    </div>
</section>

{{-- Ranking & Detail Table --}}
<section class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex items-end justify-between gap-4 mb-4">
        <div>
            <h2 class="text-xl font-bold">Rekap Periode (Ranking Income)</h2>
            <p class="text-sm text-taupe">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="premium-table w-full min-w-[800px]">
            <caption class="sr-only">Ranking outlet berdasarkan total income periode {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</caption>
            <thead>
                <tr>
                    <th scope="col" class="p-3">#</th>
                    <th scope="col" class="p-3">Outlet</th>
                    <th scope="col" class="p-3 text-right">Items</th>
                    <th scope="col" class="p-3 text-right">Income</th>
                    <th scope="col" class="p-3 text-right">Expense</th>
                    <th scope="col" class="p-3 text-right">Profit</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($outletTotals as $idx => $outlet)
                    @php
                        $rank = $idx + 1;
                        $isTop = $rank <= 3;
                        $rowBg = $isTop ? 'bg-yellow-50' : '';
                    @endphp
                    <tr class="border-b border-linen/40 {{ $rowBg }}">
                        <td class="p-3 font-semibold text-ink">{{ $rank }}</td>
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                @if($isTop)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-yellow-200 text-yellow-900">TOP</span>
                                @endif
                                <span class="font-semibold text-ink">{{ $outlet['outlet_name'] }}</span>
                            </div>
                        </td>
                        <td class="p-3 text-right font-semibold">{{ $outlet['total_items'] ?? 0 }}</td>
                        <td class="p-3 font-semibold text-right text-green-700">Rp {{ number_format($outlet['total_income'], 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($outlet['total_expense'], 0, ',', '.') }}</td>
                        <td class="p-3 font-semibold text-right {{ $outlet['total_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($outlet['total_profit'], 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-taupe">Belum ada data transaksi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mini cards per outlet --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
        @foreach ($outletTotals as $outlet)
            <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
                <p class="text-sm font-semibold text-ink">{{ $outlet['outlet_name'] }}</p>
                <p class="mt-3 text-xl font-bold">Rp {{ number_format($outlet['total_income'], 0, ',', '.') }}</p>
                <div class="mt-1 text-xs text-taupe space-y-0.5">
                    <p>Items terjual: <span class="font-semibold text-ink">{{ $outlet['total_items'] ?? 0 }}</span></p>
                    <p>
                        Expense: Rp {{ number_format($outlet['total_expense'], 0, ',', '.') }} |
                        Profit: <span class="font-semibold {{ $outlet['total_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($outlet['total_profit'], 0, ',', '.') }}</span>
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const dateLabels = {!! json_encode($dateLabels) !!};
const incomeDatasets = {!! json_encode($incomeDatasets) !!};
const expenseDatasets = {!! json_encode($expenseDatasets) !!};

// ── Income Bar Chart ──
const ctxIncome = document.getElementById('incomeChart').getContext('2d');
new Chart(ctxIncome, {
    type: 'bar',
    data: {
        labels: dateLabels,
        datasets: incomeDatasets
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        scales: {
            x: {
                stacked: false,
                ticks: { maxRotation: 0, autoSkip: true }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                }
            }
        },
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 12, padding: 16 }
            },
            tooltip: {
                callbacks: {
                    label: context => {
                        return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                    }
                }
            }
        }
    }
});

// ── Expense Line Chart ──
const ctxExpense = document.getElementById('expenseChart').getContext('2d');
new Chart(ctxExpense, {
    type: 'line',
    data: {
        labels: dateLabels,
        datasets: expenseDatasets
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        scales: {
            x: {
                ticks: { maxRotation: 0, autoSkip: true }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                }
            }
        },
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 12, padding: 16 }
            },
            tooltip: {
                callbacks: {
                    label: context => {
                        return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                    }
                }
            }
        }
    }
});
</script>
@endsection