@extends('layouts.dashboard')

@section('title', 'Pendapatan Hari Ini - Semua Outlet')

@section('content')
<section class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Pendapatan Hari Ini - Semua Outlet</h1>
            <p class="text-sm text-gray-500">Perbandingan total pendapatan hari ini per outlet</p>
        </div>
        <div>
            <a href="{{ route('franchisee.financial.create') }}" class="premium-button">Form Input Transaksi Harian</a>
        </div>
    </div>
</section>

<section class="bg-white p-6 rounded shadow mb-6">
    <canvas id="outletBarChart" height="120"></canvas>
</section>

<section class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Rincian Hari Ini</h2>
    <table class="premium-table w-full">
        <thead>
            <tr>
                <th>Nama Outlet</th>
                <th class="text-right">Total Transaksi</th>
                <th class="text-right">Total Pendapatan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tableRows as $row)
                <tr class="border-b">
                    <td class="py-3">{{ $row['outlet_name'] }}</td>
                    <td class="py-3 text-right">{{ $row['transactions'] }}</td>
                    <td class="py-3 text-right">Rp {{ number_format($row['total_income'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = {!! json_encode($labels) !!};
const data = {!! json_encode($data) !!};

const ctx = document.getElementById('outletBarChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Total Pendapatan Hari Ini (Rp)',
            data,
            backgroundColor: 'rgba(54,162,235,0.8)',
            borderColor: 'rgba(54,162,235,1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                }
            }
        },
        plugins: { legend: { display: false } }
    }
});
</script>

@endsection
