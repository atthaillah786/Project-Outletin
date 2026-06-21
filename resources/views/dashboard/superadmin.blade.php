@extends('layouts.dashboard')

@section('title', 'Dashboard Superadmin - Outletin')

@section('content')

    <section class="mb-10" data-reveal>
        <p class="mb-3 text-sm font-extrabold uppercase tracking-normal text-oxblood">
            Executive overview
        </p>
        <h1 class="premium-section-title mb-3">
            Dashboard Superadmin
        </h1>

        <p class="premium-muted text-base md:text-lg">
            Kelola verifikasi brand, pantau user, outlet, pengajuan franchise, dan performa keuangan sistem.
        </p>
    </section>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm mb-8">
            {{ session('success') }}
        </div>
    @endif

    <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
        <div class="premium-card premium-card-hover p-6" data-reveal>
            <p class="text-taupe text-sm font-bold mb-2">Total User</p>
            <h2 class="text-3xl font-extrabold text-ink">{{ $totalUsers }}</h2>
        </div>

        <div class="premium-card premium-card-hover p-6" data-reveal>
            <p class="text-taupe text-sm font-bold mb-2">Total Brand</p>
            <h2 class="text-3xl font-extrabold text-ink">{{ $totalBrands }}</h2>
        </div>

        <div class="premium-card premium-card-hover p-6" data-reveal>
            <p class="text-taupe text-sm font-bold mb-2">Total Outlet</p>
            <h2 class="text-3xl font-extrabold text-ink">{{ $totalOutlets }}</h2>
        </div>

        <div class="premium-card premium-card-hover p-6" data-reveal>
            <p class="text-taupe text-sm font-bold mb-2">Brand Pending</p>
            <h2 class="text-3xl font-extrabold text-ink">{{ $pendingBrands }}</h2>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-10">
        <div class="xl:col-span-2 premium-card p-6 md:p-8" data-reveal>
            <h2 class="text-2xl font-extrabold text-ink mb-2">
                Grafik Status Brand
            </h2>

            <p class="premium-muted mb-6">
                Perbandingan brand pending, approved, dan rejected.
            </p>

            <canvas id="brandStatusChart" height="120"></canvas>
        </div>

        <div class="premium-card p-6 md:p-8" data-reveal>
            <h2 class="text-2xl font-extrabold text-ink mb-2">
                Role User
            </h2>

            <p class="premium-muted mb-6">
                Distribusi user berdasarkan role.
            </p>

            <canvas id="userRoleChart" height="220"></canvas>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="premium-card premium-card-hover p-6" data-reveal>
            <p class="text-taupe text-sm font-bold mb-2">Total Income</p>
            <h2 class="text-2xl font-extrabold text-ink">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </h2>
        </div>

        <div class="premium-card premium-card-hover p-6" data-reveal>
            <p class="text-taupe text-sm font-bold mb-2">Total Expense</p>
            <h2 class="text-2xl font-extrabold text-ink">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </h2>
        </div>

        <div class="premium-card premium-card-hover p-6" data-reveal>
            <p class="text-taupe text-sm font-bold mb-2">Total Profit</p>
            <h2 class="text-2xl font-extrabold text-ink">
                Rp {{ number_format($totalProfit, 0, ',', '.') }}
            </h2>
        </div>
    </section>

    <section class="premium-card p-6 md:p-8 mb-10" data-reveal>
        <div class="mb-6">
            <h2 class="text-2xl font-extrabold text-ink">
                Verifikasi Brand
            </h2>

            <p class="premium-muted">
                Brand yang baru didaftarkan oleh pemilik brand dan menunggu persetujuan superadmin.
            </p>
        </div>

        <div class="space-y-5">
            @forelse ($brandsNeedVerification as $brand)
                <div class="rounded-3xl border border-linen/60 bg-white/70 p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_18px_50px_rgb(85,11,20,0.10)]">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

                        <div class="lg:col-span-1">
                            @if ($brand->logo_path)
                                <img
                                    src="{{ asset('storage/' . $brand->logo_path) }}"
                                    alt="{{ $brand->brand_name }}"
                                    class="w-24 h-24 rounded-2xl object-cover border"
                                >
                            @else
                                <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-oxblood to-taupe flex items-center justify-center shadow-[0_16px_36px_rgb(85,11,20,0.18)]">
                                    <span class="text-white text-3xl font-bold">
                                        {{ strtoupper(substr($brand->brand_name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="lg:col-span-2">
                            <h3 class="text-xl font-extrabold text-ink">
                                {{ $brand->brand_name }}
                            </h3>

                            <p class="text-taupe text-sm mt-1">
                                Pemilik: {{ $brand->franchisor->name ?? '-' }}
                            </p>

                            <p class="text-taupe text-sm">
                                Email: {{ $brand->franchisor->email ?? '-' }}
                            </p>

                            <p class="text-taupe mt-3 leading-7">
                                {{ $brand->description ?? 'Belum ada deskripsi.' }}
                            </p>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="flex flex-col gap-3">
                                <form method="POST" action="{{ route('superadmin.brands.approve', $brand->brand_id) }}">
                                    @csrf

                                    <button
                                        type="submit"
                                        class="w-full rounded-full bg-emerald-600 px-4 py-3 font-bold text-white shadow-[0_14px_34px_rgb(5,150,105,0.22)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-emerald-700"
                                    >
                                        Setujui Brand
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('superadmin.brands.reject', $brand->brand_id) }}">
                                    @csrf

                                    <textarea
                                        name="rejection_note"
                                        rows="3"
                                        placeholder="Catatan penolakan, opsional"
                                        class="premium-input"
                                    ></textarea>

                                    <button
                                        type="submit"
                                        class="premium-button w-full mt-3"
                                    >
                                        Tolak Brand
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <p class="text-gray-500">
                        Tidak ada brand yang menunggu verifikasi.
                    </p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-10">

        <div class="premium-card p-6 md:p-8" data-reveal>
            <h2 class="text-2xl font-extrabold text-ink mb-6">
                Brand Terbaru
            </h2>

            <div class="overflow-x-auto">
                <table class="premium-table">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-600 text-sm">
                            <th class="py-3 pr-4">Brand</th>
                            <th class="py-3 pr-4">Pemilik</th>
                            <th class="py-3 pr-4">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($latestBrands as $brand)
                            <tr class="border-b border-gray-100">
                                <td class="py-4 pr-4 font-semibold text-black">
                                    {{ $brand->brand_name }}
                                </td>

                                <td class="py-4 pr-4 text-gray-700">
                                    {{ $brand->franchisor->name ?? '-' }}
                                </td>

                                <td class="py-4 pr-4">
                                    @if ($brand->status === 'approved')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Approved
                                        </span>
                                    @elseif ($brand->status === 'rejected')
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-gray-500">
                                    Belum ada brand.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="premium-card p-6 md:p-8" data-reveal>
            <h2 class="text-2xl font-extrabold text-ink mb-6">
                Pengajuan Franchise Terbaru
            </h2>

            <div class="overflow-x-auto">
                <table class="premium-table">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-600 text-sm">
                            <th class="py-3 pr-4">Franchise</th>
                            <th class="py-3 pr-4">Brand</th>
                            <th class="py-3 pr-4">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($latestFranchiseApplications as $application)
                            <tr class="border-b border-gray-100">
                                <td class="py-4 pr-4 font-semibold text-black">
                                    {{ $application->franchise->name ?? '-' }}
                                </td>

                                <td class="py-4 pr-4 text-gray-700">
                                    {{ $application->brand->brand_name ?? '-' }}
                                </td>

                                <td class="py-4 pr-4">
                                    @if ($application->status === 'approved')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Approved
                                        </span>
                                    @elseif ($application->status === 'rejected')
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-gray-500">
                                    Belum ada pengajuan franchise.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </section>

    <section class="premium-card p-6 md:p-8" data-reveal>
        <h2 class="text-2xl font-extrabold text-ink mb-6">
            Outlet Terbaru
        </h2>

        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-600 text-sm">
                        <th class="py-3 pr-4">Outlet</th>
                        <th class="py-3 pr-4">Brand</th>
                        <th class="py-3 pr-4">Franchise</th>
                        <th class="py-3 pr-4">Status</th>
                        <th class="py-3 pr-4">Alamat</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($latestOutlets as $outlet)
                        <tr class="border-b border-gray-100">
                            <td class="py-4 pr-4 font-semibold text-black">
                                {{ $outlet->outlet_name }}
                            </td>

                            <td class="py-4 pr-4 text-gray-700">
                                {{ $outlet->brand->brand_name ?? '-' }}
                            </td>

                            <td class="py-4 pr-4 text-gray-700">
                                {{ $outlet->franchise->name ?? '-' }}
                            </td>

                            <td class="py-4 pr-4">
                                @if ($outlet->status === 'approved')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Approved
                                    </span>
                                @elseif ($outlet->status === 'rejected')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Rejected
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 pr-4 text-gray-700">
                                {{ $outlet->address ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">
                                Belum ada outlet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</main>

@endsection

@push('scripts')
<script>
    Chart.defaults.color = '#7e6961';
    Chart.defaults.borderColor = 'rgba(203, 192, 178, 0.55)';

    const brandStatusLabels = ['Pending', 'Approved', 'Rejected'];
    const brandStatusData = @json(array_values($brandStatusCounts));

    new Chart(document.getElementById('brandStatusChart'), {
        type: 'bar',
        data: {
            labels: brandStatusLabels,
            datasets: [
                {
                    label: 'Jumlah Brand',
                    data: brandStatusData,
                    borderWidth: 1,
                    backgroundColor: ['#cbc0b2', '#7e6961', '#550b14'],
                    borderRadius: 14
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    const userRoleLabels = ['Franchisor', 'Franchise', 'Admin', 'Superadmin'];
    const userRoleData = @json(array_values($userRoleCounts));

    new Chart(document.getElementById('userRoleChart'), {
        type: 'doughnut',
        data: {
            labels: userRoleLabels,
            datasets: [
                {
                    label: 'Jumlah User',
                    data: userRoleData,
                    borderWidth: 2,
                    backgroundColor: ['#550b14', '#7e6961', '#cbc0b2', '#201717'],
                    borderColor: '#f8f8f7'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
