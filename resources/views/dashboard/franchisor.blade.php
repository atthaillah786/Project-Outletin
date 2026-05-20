<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilik Brand - Outletin</title>

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
            Dashboard Pemilik Brand
        </h1>

        <p class="text-gray-600 text-base md:text-lg">
            Pantau performa outlet, laporan keuangan, dan pengajuan franchise ke brand Anda.
        </p>
    </section>

    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-8">
            {{ session('success') }}
        </div>
    @endif

    <section class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Total Brand</p>
            <h2 class="text-3xl font-bold text-black">{{ $brands->count() }}</h2>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Total Outlet</p>
            <h2 class="text-3xl font-bold text-black">{{ $outlets->count() }}</h2>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Pengajuan Pending</p>
            <h2 class="text-3xl font-bold text-black">{{ $pendingApplications->count() }}</h2>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Total Profit</p>
            <h2 class="text-2xl font-bold text-black">
                Rp {{ number_format($totalProfit, 0, ',', '.') }}
            </h2>
        </div>
    </section>

    <section class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm mb-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-black">
                    Grafik Keuangan Semua Outlet
                </h2>

                <p class="text-gray-600">
                    Menampilkan total pendapatan, pengeluaran, dan profit per bulan.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                <div class="bg-gray-100 rounded-xl px-4 py-3">
                    <p class="text-gray-500">Income</p>
                    <p class="font-bold text-black">
                        Rp {{ number_format($totalIncome, 0, ',', '.') }}
                    </p>
                </div>

                <div class="bg-gray-100 rounded-xl px-4 py-3">
                    <p class="text-gray-500">Expense</p>
                    <p class="font-bold text-black">
                        Rp {{ number_format($totalExpense, 0, ',', '.') }}
                    </p>
                </div>

                <div class="bg-gray-100 rounded-xl px-4 py-3">
                    <p class="text-gray-500">Profit</p>
                    <p class="font-bold text-black">
                        Rp {{ number_format($totalProfit, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full overflow-x-auto">
            <div class="min-w-[700px]">
                <canvas id="financialChart" height="120"></canvas>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        <div class="xl:col-span-2 bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-black">
                    Daftar Semua Outlet
                </h2>

                <p class="text-gray-600">
                    Semua outlet yang terhubung ke brand milik Anda.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
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
                        @forelse ($outlets as $outlet)
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
                                    Belum ada outlet untuk brand Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-black">
                    Pengajuan Franchise
                </h2>

                <p class="text-gray-600">
                    Franchise yang mengajukan daftar ke brand Anda.
                </p>
            </div>

            <div class="space-y-4">
                @forelse ($pendingApplications as $application)
                    <div class="border border-gray-200 rounded-2xl p-5">
                        <div class="mb-4">
                            <h3 class="font-bold text-black text-lg">
                                {{ $application->franchise->name ?? 'Franchise' }}
                            </h3>

                            <p class="text-gray-600 text-sm">
                                {{ $application->franchise->email ?? '-' }}
                            </p>

                            <p class="text-gray-700 text-sm mt-2">
                                Mengajukan ke brand:
                                <span class="font-semibold">
                                    {{ $application->brand->brand_name ?? '-' }}
                                </span>
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <form method="POST" action="{{ route('franchisor.applications.approve', $application->franchise_brands_id) }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition"
                                >
                                    Terima
                                </button>
                            </form>

                            <form method="POST" action="{{ route('franchisor.applications.reject', $application->franchise_brands_id) }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition"
                                >
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="border border-gray-200 rounded-2xl p-6 text-center">
                        <p class="text-gray-500">
                            Belum ada pengajuan franchise.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

    </section>

</main>

<script>
    const labels = @json($chartLabels);
    const incomeData = @json($incomeData);
    const expenseData = @json($expenseData);
    const profitData = @json($profitData);

    const ctx = document.getElementById('financialChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pendapatan',
                    data: incomeData,
                    borderWidth: 3,
                    tension: 0.35
                },
                {
                    label: 'Pengeluaran',
                    data: expenseData,
                    borderWidth: 3,
                    tension: 0.35
                },
                {
                    label: 'Profit',
                    data: profitData,
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
</script>

</body>
</html>