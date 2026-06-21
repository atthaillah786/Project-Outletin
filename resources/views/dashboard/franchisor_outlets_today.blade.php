@extends('layouts.dashboard')

@section('title', 'Pendapatan Hari Ini - Semua Outlet')

@section('content')
<section class="mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Pendapatan Hari Ini — Semua Outlet</h1>
            <p class="text-sm text-taupe">Perbandingan pendapatan dan pengeluaran hari ini per outlet</p>
        </div>
        <div>
            <a href="{{ route('franchisor.financial.daily') }}" class="premium-button-soft">Grafik Harian</a>
        </div>
    </div>
</section>

{{-- Summary Cards per Outlet --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach($tableRows as $row)
        <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
            <p class="text-sm font-semibold text-ink">{{ $row['outlet_name'] }}</p>
            <p class="mt-3 text-xl font-bold text-green-600">Rp {{ number_format($row['total_income'], 0, ',', '.') }}</p>
            <div class="mt-2 text-xs text-taupe space-y-0.5">
                <p>Item terjual: <span class="font-semibold text-ink">{{ $row['total_items'] }}</span></p>
                <p>Expense: <span class="font-semibold {{ $row['total_expense'] > 0 ? 'text-red-600' : 'text-ink' }}">Rp {{ number_format($row['total_expense'], 0, ',', '.') }}</span></p>
                <p>Profit: <span class="font-semibold {{ $row['total_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($row['total_profit'], 0, ',', '.') }}</span></p>
            </div>
        </div>
    @endforeach
</div>

{{-- Bar Chart: Income vs Expense --}}
<section class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
    <h2 class="text-lg font-bold mb-4">Income vs Expense Hari Ini</h2>
    <div class="overflow-x-auto">
        <div class="min-w-[600px]">
            <canvas id="outletBarChart" height="140" role="img" aria-label="Grafik batang income dan expense per outlet hari ini"></canvas>
        </div>
    </div>
</section>

{{-- Tabel Rincian --}}
<section class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-lg font-semibold mb-4">Rincian Hari Ini</h2>
    <div class="overflow-x-auto">
        <table class="premium-table w-full min-w-[600px]">
            <caption class="sr-only">Rincian pendapatan, pengeluaran, dan profit per outlet hari ini</caption>
            <thead>
                <tr>
                    <th scope="col" class="p-3">Nama Outlet</th>
                    <th scope="col" class="p-3 text-right">Item Terjual</th>
                    <th scope="col" class="p-3 text-right">Income (Rp)</th>
                    <th scope="col" class="p-3 text-right">Expense (Rp)</th>
                    <th scope="col" class="p-3 text-right">Profit (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tableRows as $row)
                    <tr class="border-b border-linen/40">
                        <th scope="row" class="p-3 font-semibold text-ink">{{ $row['outlet_name'] }}</th>
                        <td class="p-3 text-right">{{ $row['total_items'] }}</td>
                        <td class="p-3 text-right font-semibold text-green-700">Rp {{ number_format($row['total_income'], 0, ',', '.') }}</td>
                        <td class="p-3 text-right">{{ $row['total_expense'] > 0 ? 'Rp ' . number_format($row['total_expense'], 0, ',', '.') : '-' }}</td>
                        <td class="p-3 text-right font-semibold {{ $row['total_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($row['total_profit'], 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = {!! json_encode($labels) !!};
const incomeData = {!! json_encode($incomeData) !!};
const expenseData = {!! json_encode($expenseData) !!};

const ctx = document.getElementById('outletBarChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels,
        datasets: [
            {
                label: 'Income (Rp)',
                data: incomeData,
                backgroundColor: 'rgba(34, 197, 94, 0.8)',   // green
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 1,
            },
            {
                label: 'Expense (Rp)',
                data: expenseData,
                backgroundColor: 'rgba(239, 68, 68, 0.8)',   // red
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 1,
            }
        ]
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
                ticks: { maxRotation: 0 }
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