@extends('layouts.dashboard')

@section('title', 'Tren Pendapatan 7 Hari')

@section('content')
<section class="mb-4 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold">Tren Pendapatan - {{ $outlet->outlet_name }}</h1>
        <p class="text-sm text-gray-500">7 hari terakhir</p>
    </div>

    <div>
        <a href="{{ route('franchisee.financial.create') }}" class="premium-button">Form Input Transaksi Harian</a>
    </div>
</section>

<section class="bg-white p-6 rounded shadow mb-6">
    <canvas id="weeklyLineChart" height="120"></canvas>
</section>

<section class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Transaksi Harian (7 hari)</h2>
    <table class="premium-table w-full">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-right">Jumlah Transaksi</th>
                <th>Metode Pembayaran</th>
                <th class="text-right">Total Pendapatan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tableRows as $row)
                <tr class="border-b">
                    <td class="py-3">{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                    <td class="py-3 text-right">{{ $row['count'] }}</td>
                    <td class="py-3">{{ $row['methods'] }}</td>
                    <td class="py-3 text-right">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($labels) !!};
    const data = {!! json_encode($incomeData) !!};

    const ctx = document.getElementById('weeklyLineChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Total Pendapatan (Rp)',
                data,
                borderColor: 'rgba(75,192,192,1)',
                backgroundColor: 'rgba(75,192,192,0.15)',
                tension: 0.3,
                fill: true,
                pointRadius: 3
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
            }
        }
    });
</script>

@endsection
