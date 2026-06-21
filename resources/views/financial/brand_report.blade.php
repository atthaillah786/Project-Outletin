@extends('layouts.dashboard')

@section('title', 'Laporan Keuangan Brand')

@section('content')
<main class="container mx-auto px-4 py-12">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold">Laporan Keuangan - {{ $brand->brand_name }}</h1>
                <p class="text-sm text-gray-500">
                    Perbandingan performa tiap outlet (Income/Expense/Profit)
                    pada periode {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}
                </p>
            </div>

<a
                href="{{ route('franchisor.brand.financials.download', [
                    $brand->brand_id,
                    'outlet_id' => $outletId,
                    'start_date' => $start ? $start->toDateString() : null,
                    'end_date' => $end ? $end->toDateString() : null,
                ]) }}"

                class="inline-flex items-center rounded-full bg-green-600 px-4 py-2 text-white shadow-sm hover:bg-green-700"
            >
                Download CSV
            </a>
        </div>

        {{-- Filter/Form (Owner View) --}}
        <section class="mb-8">
            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4">
                <h2 class="text-lg font-bold mb-4">Filter Laporan</h2>

                <form method="GET" action="{{ route('franchisor.brand.financials', $brand->brand_id) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Outlet</label>
<select name="outlet_id" class="w-full border rounded p-2">
                            <option value="" {{ empty($outletId) ? 'selected' : '' }}>Semua Outlet</option>
@foreach(($outletsList ?? $outletTotals) as $o)
                                <option value="{{ $o['outlet_id'] }}" {{ (string)($outletId ?? '') === (string)$o['outlet_id'] ? 'selected' : '' }}>
                                    {{ $o['outlet_name'] }}
                                </option>
                            @endforeach
                        </select>


{{-- Filter outlet dan rentang tanggal akan memengaruhi tabel & grafik. --}}
                        <p class="text-xs text-gray-500 mt-1">Filter outlet dan rentang tanggal akan memengaruhi tabel & grafik.</p>


                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ $start->toDateString() }}" class="w-full border rounded p-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ $end->toDateString() }}" class="w-full border rounded p-2" />
                    </div>

                    <div class="md:col-span-3 flex gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow-sm hover:bg-blue-700">
                            Terapkan Filter
                        </button>
                        <a
                            href="{{ route('franchisor.brand.financials', $brand->brand_id) }}"
                            class="inline-flex items-center px-4 py-2 rounded border border-gray-200 text-gray-700 hover:bg-gray-50"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </section>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="rounded-3xl bg-gray-50 p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-gray-500">Income Periode</p>
                <p class="mt-2 text-2xl font-bold">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-3xl bg-gray-50 p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-gray-500">Expense Periode</p>
                <p class="mt-2 text-2xl font-bold">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-3xl bg-gray-50 p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-gray-500">Profit Periode</p>
                <p class="mt-2 text-2xl font-bold">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Grafik (lebih bersih): gunakan line untuk mengurangi clutter dibanding bar multi-dataset --}}
        <section class="mb-10">
            <div class="overflow-x-auto">
                <div class="min-w-[760px]">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-bold">Grafik Income (Total per Hari)</h2>
                        <p class="text-xs text-gray-500">Jika banyak outlet, gunakan filter outlet untuk tampilan lebih fokus.</p>
                    </div>
                    <canvas id="chartFinancial" height="260"></canvas>
                </div>
            </div>
        </section>

        {{-- Ranking + Tabel rinci per outlet --}}
        <section>
            <div class="flex items-end justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold">Rincian Performa Tiap Outlet (Ranking Income)</h2>
                    <p class="text-sm text-gray-500">Membantu membandingkan outlet mana pendapatannya paling besar.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] border-collapse">
                    <thead>
                        <tr class="text-left text-sm text-gray-500">
                            <th class="p-3 border-b">#</th>
                            <th class="p-3 border-b">Outlet</th>
                            <th class="p-3 border-b">Income</th>
                            <th class="p-3 border-b">Expense</th>
                            <th class="p-3 border-b">Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($outletTotals as $idx => $outlet)
                            @php
                                $rank = $idx + 1;
                                $isTop = $rank <= 3;
                                $rowBg = $isTop ? 'bg-yellow-50' : '';
                            @endphp
                            <tr class="border-b {{ $rowBg }}">
                                <td class="p-3 font-semibold">{{ $rank }}</td>
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        @if($isTop)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-yellow-200 text-yellow-900">TOP</span>
                                        @endif
                                        <span>{{ $outlet['outlet_name'] }}</span>
                                    </div>
                                </td>
                                <td class="p-3 font-semibold">Rp {{ number_format($outlet['total_income'], 0, ',', '.') }}</td>
                                <td class="p-3">Rp {{ number_format($outlet['total_expense'], 0, ',', '.') }}</td>
                                <td class="p-3 font-semibold">
                                    Rp {{ number_format($outlet['total_profit'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">Belum ada data laporan pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                @foreach ($outletTotals as $outlet)
                    <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
                        <p class="text-sm text-gray-500">{{ $outlet['outlet_name'] }}</p>
                        <p class="mt-3 text-xl font-bold">Rp {{ number_format($outlet['total_income'], 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 mt-1">Profit Rp {{ number_format($outlet['total_profit'], 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($labels) !!};
    const datasets = {!! json_encode($datasets) !!};

    const ctx = document.getElementById('chartFinancial').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: datasets.map(dataset => ({
                label: dataset.label,
                data: dataset.data,
                borderColor: dataset.borderColor,
                backgroundColor: dataset.backgroundColor,
                borderWidth: 2,
                tension: 0.25,
                pointRadius: 0,
                fill: false,
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: { maxRotation: 0, autoSkip: true },
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                    },
                    title: {
                        display: true,
                        text: 'Income'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 10 }
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

