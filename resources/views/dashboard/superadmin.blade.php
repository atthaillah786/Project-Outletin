<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - Outletin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-50 min-h-screen">

<nav class="bg-red-800 text-white sticky top-0 z-50 shadow-md">
    <div class="container mx-auto flex items-center justify-between px-4 py-4">
        <a href="{{ route('home') }}" class="text-xl font-bold">
            Outletin
        </a>

        <div class="flex items-center gap-4">
            <span class="hidden md:inline text-sm text-red-100">
                {{ auth()->user()->name }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition"
                >
                    Keluar
                </button>
            </form>
        </div>
    </div>
</nav>

<main class="container mx-auto px-4 py-10">

    <section class="mb-10">
        <h1 class="text-4xl md:text-5xl font-bold text-black mb-3">
            Dashboard Superadmin
        </h1>

        <p class="text-gray-600 text-base md:text-lg">
            Kelola verifikasi brand, pantau user, outlet, pengajuan franchise, dan performa keuangan sistem.
        </p>
    </section>

    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-8">
            {{ session('success') }}
        </div>
    @endif

    <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Total User</p>
            <h2 class="text-3xl font-bold text-black">{{ $totalUsers }}</h2>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Total Brand</p>
            <h2 class="text-3xl font-bold text-black">{{ $totalBrands }}</h2>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Total Outlet</p>
            <h2 class="text-3xl font-bold text-black">{{ $totalOutlets }}</h2>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Brand Pending</p>
            <h2 class="text-3xl font-bold text-black">{{ $pendingBrands }}</h2>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-10">
        <div class="xl:col-span-2 bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-black mb-2">
                Grafik Status Brand
            </h2>

            <p class="text-gray-600 mb-6">
                Perbandingan brand pending, approved, dan rejected.
            </p>

            <canvas id="brandStatusChart" height="120"></canvas>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-black mb-2">
                Role User
            </h2>

            <p class="text-gray-600 mb-6">
                Distribusi user berdasarkan role.
            </p>

            <canvas id="userRoleChart" height="220"></canvas>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Total Income</p>
            <h2 class="text-2xl font-bold text-black">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Total Expense</p>
            <h2 class="text-2xl font-bold text-black">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Total Profit</p>
            <h2 class="text-2xl font-bold text-black">
                Rp {{ number_format($totalProfit, 0, ',', '.') }}
            </h2>
        </div>
    </section>

    <section class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm mb-10">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-black">
                Verifikasi Brand
            </h2>

            <p class="text-gray-600">
                Brand yang baru didaftarkan oleh pemilik brand dan menunggu persetujuan superadmin.
            </p>
        </div>

        <div class="space-y-5">
            @forelse ($brandsNeedVerification as $brand)
                <div class="border border-gray-200 rounded-2xl p-5">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

                        <div class="lg:col-span-1">
                            @if ($brand->logo_path)
                                <img
                                    src="{{ asset('storage/' . $brand->logo_path) }}"
                                    alt="{{ $brand->brand_name }}"
                                    class="w-24 h-24 rounded-2xl object-cover border"
                                >
                            @else
                                <div class="w-24 h-24 rounded-2xl bg-black flex items-center justify-center">
                                    <span class="text-white text-3xl font-bold">
                                        {{ strtoupper(substr($brand->brand_name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="lg:col-span-2">
                            <h3 class="text-xl font-bold text-black">
                                {{ $brand->brand_name }}
                            </h3>

                            <p class="text-gray-600 text-sm mt-1">
                                Pemilik: {{ $brand->franchisor->name ?? '-' }}
                            </p>

                            <p class="text-gray-600 text-sm">
                                Email: {{ $brand->franchisor->email ?? '-' }}
                            </p>

                            <p class="text-gray-700 mt-3 leading-7">
                                {{ $brand->description ?? 'Belum ada deskripsi.' }}
                            </p>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="flex flex-col gap-3">
                                <form method="POST" action="{{ route('superadmin.brands.approve', $brand->brand_id) }}">
                                    @csrf

                                    <button
                                        type="submit"
                                        class="w-full bg-green-600 text-white px-4 py-3 rounded-xl font-semibold hover:bg-green-700 transition"
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
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                                    ></textarea>

                                    <button
                                        type="submit"
                                        class="w-full mt-3 bg-red-600 text-white px-4 py-3 rounded-xl font-semibold hover:bg-red-700 transition"
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

        <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-black mb-6">
                Brand Terbaru
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
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

        <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-black mb-6">
                Pengajuan Franchise Terbaru
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
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

    <section class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
        <h2 class="text-2xl font-bold text-black mb-6">
            Outlet Terbaru
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
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

<script>
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
                    borderWidth: 1
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
                    borderWidth: 1
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

</body>
</html>