@extends('layouts.dashboard')

@section('title', 'Dashboard Pemilik Brand - Outletin')

@section('content')
<section class="mb-8" data-reveal>
    <p class="mb-3 text-sm font-extrabold uppercase tracking-normal text-oxblood">
        Brand command center
    </p>
    <h1 class="premium-section-title mb-2">
        Dashboard Pemilik Brand
    </h1>

    <p class="premium-muted">
        Pantau outlet, keuangan, dan pengajuan franchise dalam satu halaman.
    </p>
</section>

<section class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
    <div class="premium-card premium-card-hover p-6" data-reveal>
        <p class="text-taupe text-sm font-bold">Brand Aktif</p>
        <h2 class="text-3xl font-extrabold text-ink">{{ $brands->count() }}</h2>
    </div>

    <div class="premium-card premium-card-hover p-6" data-reveal>
        <p class="text-taupe text-sm font-bold">Total Outlet</p>
        <h2 class="text-3xl font-extrabold text-ink">{{ $outlets->count() }}</h2>
    </div>

    <div class="premium-card premium-card-hover p-6" data-reveal>
        <p class="text-taupe text-sm font-bold">Pengajuan Pending</p>
        <h2 class="text-3xl font-extrabold text-ink">{{ $pendingApplications->count() }}</h2>
    </div>

    <div class="premium-card premium-card-hover p-6" data-reveal>
        <p class="text-taupe text-sm font-bold">Total Profit</p>
        <h2 class="text-2xl font-extrabold text-ink">
            Rp {{ number_format($totalProfit, 0, ',', '.') }}
        </h2>
    </div>
</section>

<section class="premium-card p-6 mb-8" data-reveal>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-ink">
                Grafik Keuangan Semua Outlet
            </h2>

            <p class="premium-muted">
                Income, expense, dan profit per bulan.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
            <div class="rounded-2xl bg-mist/70 px-4 py-3">
                <p class="text-taupe">Income</p>
                <p class="font-extrabold text-ink">
                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-2xl bg-mist/70 px-4 py-3">
                <p class="text-taupe">Expense</p>
                <p class="font-extrabold text-ink">
                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-2xl bg-mist/70 px-4 py-3">
                <p class="text-taupe">Profit</p>
                <p class="font-extrabold text-ink">
                    Rp {{ number_format($totalProfit, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <div class="w-full overflow-x-auto">
        <div class="min-w-[700px]">
            <canvas id="financeChart" height="110"></canvas>
        </div>
    </div>
</section>

<section id="applications" class="premium-card p-6 mb-8" data-reveal>
    <div class="mb-5">
            <h2 class="text-2xl font-extrabold text-ink">
            Pengajuan Outlet
        </h2>

            <p class="premium-muted">
            Lihat data outlet yang dikirim oleh franchisee sebelum menerima atau menolak pengajuan.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($applications as $application)
            <div class="rounded-3xl border border-linen/60 bg-white/70 p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_18px_50px_rgb(85,11,20,0.10)]">
                <div class="mb-4">
                    <p class="text-sm font-bold text-taupe">Nama Outlet</p>

                    <h3 class="text-xl font-extrabold text-ink">
                        {{ $application->outlet_name }}
                    </h3>

                    <p class="text-gray-700 mt-3">
                        Brand:
                        <span class="font-semibold">
                            {{ $application->brand->brand_name ?? '-' }}
                        </span>
                    </p>

                    <p class="text-gray-700 mt-2">
                        Franchisee:
                        <span class="font-semibold">
                            {{ $application->franchise->name ?? '-' }}
                        </span>
                    </p>

                    <p class="text-gray-600 text-sm">
                        {{ $application->franchise->email ?? '-' }}
                    </p>

                    <p class="text-gray-700 mt-3">
                        Alamat:
                    </p>

                    <p class="text-gray-600 leading-7">
                        {{ $application->address ?? '-' }}
                    </p>

                    <span class="inline-block mt-4 px-3 py-1 rounded-full text-xs font-semibold
                        {{ $application->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $application->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>

                @if ($application->status === 'pending')
                    <div class="flex gap-3">
                        <form
                            method="POST"
                            action="{{ route('franchisor.applications.approve', $application->outlet_id) }}"
                            class="w-full"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-full bg-emerald-600 py-2 font-bold text-white transition hover:bg-emerald-700"
                            >
                                Terima
                            </button>
                        </form>

                        <form
                            method="POST"
                            action="{{ route('franchisor.applications.reject', $application->outlet_id) }}"
                            class="w-full"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="premium-button w-full py-2"
                            >
                                Tolak
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-8">
                Belum ada pengajuan outlet.
            </div>
        @endforelse
    </div>
</section>

<section id="outlets" class="premium-card p-6" data-reveal>
    <h2 class="text-2xl font-extrabold text-ink mb-5">
        Daftar Outlet
    </h2>

    <div class="overflow-x-auto">
        <table class="premium-table">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-3 pr-4">Outlet</th>
                    <th class="py-3 pr-4">Brand</th>
                    <th class="py-3 pr-4">Franchisee</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3 pr-4">Alamat</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($outlets as $outlet)
                    <tr class="border-b">
                        <td class="py-4 pr-4 font-semibold">
                            {{ $outlet->outlet_name }}
                        </td>

                        <td class="py-4 pr-4">
                            {{ $outlet->brand->brand_name ?? '-' }}
                        </td>

                        <td class="py-4 pr-4">
                            {{ $outlet->franchise->name ?? '-' }}
                        </td>

                        <td class="py-4 pr-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $outlet->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $outlet->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $outlet->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($outlet->status) }}
                            </span>
                        </td>

                        <td class="py-4 pr-4">
                            {{ $outlet->address ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-500">
                            Belum ada outlet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

@push('scripts')
<script>
const financeChartElement = document.getElementById('financeChart');

if (financeChartElement) {
    Chart.defaults.color = '#7e6961';
    Chart.defaults.borderColor = 'rgba(203, 192, 178, 0.55)';

    new Chart(financeChartElement, {
        type: 'line',
        data: {
            labels: {{ \Illuminate\Support\Js::from($chartLabels) }},
            datasets: [
                {
                    label: 'Income',
                    data: {{ \Illuminate\Support\Js::from($incomeData) }},
                    borderColor: '#7e6961',
                    backgroundColor: 'rgba(126, 105, 97, 0.12)',
                    borderWidth: 3,
                    tension: 0.35
                },
                {
                    label: 'Expense',
                    data: {{ \Illuminate\Support\Js::from($expenseData) }},
                    borderColor: '#cbc0b2',
                    backgroundColor: 'rgba(203, 192, 178, 0.18)',
                    borderWidth: 3,
                    tension: 0.35
                },
                {
                    label: 'Profit',
                    data: {{ \Illuminate\Support\Js::from($profitData) }},
                    borderColor: '#550b14',
                    backgroundColor: 'rgba(85, 11, 20, 0.12)',
                    borderWidth: 3,
                    tension: 0.35
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
}
</script>
@endpush
