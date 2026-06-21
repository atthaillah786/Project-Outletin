@extends('layouts.dashboard')

@section('title', 'Dashboard Franchisee - Outletin')

@section('content')
<section class="mb-8" data-reveal>
    <p class="mb-3 text-sm font-extrabold uppercase tracking-normal text-oxblood">
        Franchisee workspace
    </p>
    <h1 class="premium-section-title mb-2">
        Dashboard Franchisee
    </h1>
    <p class="premium-muted">
        Kelola pengajuan brand, pantau outlet, dan lihat performa keuangan outlet Anda.
    </p>
</section>

<section class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
    <div class="premium-card premium-card-hover p-6" data-reveal>
        <p class="text-taupe text-sm font-bold">Pengajuan</p>
        <h2 class="text-3xl font-extrabold text-ink">{{ $applications->count() }}</h2>
    </div>

    <div class="premium-card premium-card-hover p-6" data-reveal>
        <p class="text-taupe text-sm font-bold">Outlet Saya</p>
        <h2 class="text-3xl font-extrabold text-ink">{{ $outlets->count() }}</h2>
    </div>

    <div class="premium-card premium-card-hover p-6" data-reveal>
        <p class="text-taupe text-sm font-bold">Total Income</p>
        <h2 class="text-2xl font-extrabold text-ink">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
    </div>

    <div class="premium-card premium-card-hover p-6" data-reveal>
        <p class="text-taupe text-sm font-bold">Total Profit</p>
        <h2 class="text-2xl font-extrabold text-ink">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h2>
    </div>
</section>

<section class="premium-card p-6 mb-8" data-reveal>
    <h2 class="text-2xl font-extrabold text-ink mb-1">Grafik Keuangan Outlet Saya</h2>
    <p class="premium-muted mb-6">Pendapatan, pengeluaran, dan profit per bulan.</p>
    <canvas id="financeChart" height="110"></canvas>
</section>

<section id="brands" class="mb-8">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-2xl font-extrabold text-ink">Brand Tersedia</h2>
            <p class="premium-muted">Ajukan kerja sama ke brand yang sudah diverifikasi.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @forelse ($availableBrands as $brand)
        <div class="premium-card premium-card-hover p-6" data-reveal>
            @if ($brand->logo_path)
            <img src="{{ asset('storage/' . $brand->logo_path) }}" class="w-16 h-16 rounded-2xl object-cover mb-4 border" alt="{{ $brand->brand_name }}">
            @else
            <div class="w-16 h-16 bg-gradient-to-br from-oxblood to-taupe rounded-3xl flex items-center justify-center mb-4 shadow-[0_16px_36px_rgb(85,11,20,0.18)]">
                <span class="text-white text-2xl font-bold">{{ strtoupper(substr($brand->brand_name, 0, 1)) }}</span>
            </div>
            @endif

            <h3 class="font-extrabold text-xl text-ink mb-2">{{ $brand->brand_name }}</h3>
            <p class="premium-muted mb-5">{{ $brand->description ?? 'Belum ada deskripsi.' }}</p>

            <a
                href="{{ route('franchisee.outlets.create', $brand->brand_id) }}"
                class="premium-button w-full">
                Ajukan Outlet
            </a>
        </div>
        @empty
        <div class="col-span-full premium-card p-8 text-center text-taupe">
            Tidak ada brand baru yang tersedia.
        </div>
        @endforelse
    </div>
</section>

<section id="applications" class="premium-card p-6 mb-8" data-reveal>
    <h2 class="text-2xl font-extrabold text-ink mb-5">Status Pengajuan Saya</h2>

    <div class="overflow-x-auto">
        <table class="premium-table">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-3">Brand</th>
                    <th class="py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applications as $application)
                <tr class="border-b">
                    <td class="py-4 font-semibold">{{ $application->brand->brand_name ?? '-' }}</td>
                    <td class="py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $application->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $application->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="py-6 text-center text-gray-500">Belum ada pengajuan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section id="outlets" class="premium-card p-6" data-reveal>
    <h2 class="text-2xl font-extrabold text-ink mb-5">Outlet Saya</h2>

    <div class="overflow-x-auto">
        <table class="premium-table">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-3">Outlet</th>
                    <th class="py-3">Brand</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($outlets as $outlet)
                <tr class="border-b">
                    <td class="py-4 font-semibold">{{ $outlet->outlet_name }}</td>
                    <td class="py-4">{{ $outlet->brand->brand_name ?? '-' }}</td>
                    <td class="py-4">{{ ucfirst($outlet->status) }}</td>
                    <td class="py-4">{{ $outlet->address ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-500">Belum ada outlet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

@push('scripts')
<script>
    Chart.defaults.color = '#7e6961';
    Chart.defaults.borderColor = 'rgba(203, 192, 178, 0.55)';

    new Chart(document.getElementById('financeChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                    label: 'Income',
                    data: @json($incomeData),
                    borderColor: '#7e6961',
                    backgroundColor: 'rgba(126, 105, 97, 0.12)',
                    borderWidth: 3,
                    tension: 0.35
                },
                {
                    label: 'Expense',
                    data: @json($expenseData),
                    borderColor: '#cbc0b2',
                    backgroundColor: 'rgba(203, 192, 178, 0.18)',
                    borderWidth: 3,
                    tension: 0.35
                },
                {
                    label: 'Profit',
                    data: @json($profitData),
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
                        callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                    }
                }
            }
        }
    });
</script>
@endpush
