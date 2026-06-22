@extends('layouts.dashboard')

@section('title', 'Detail Laporan Keuangan')

@section('content')
<section class="mb-8">
    <h1 class="text-center text-5xl font-extrabold text-ink mb-1">Detail Laporan Keuangan</h1>
</section>

<main class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <!-- Info Dasar -->
        <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-200">
            <div>
                <p class="text-sm text-gray-600">Outlet</p>
                <p class="font-bold text-ink">{{ $outlet->outlet_name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Laporan</p>
                <p class="font-bold text-ink">{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Tabel Produk Terjual -->
        <div class="mb-6">
            <h3 class="font-bold text-lg text-ink mb-3">Detail Produk Terjual</h3>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="border border-linen/60 p-3 text-left font-bold">No.</th>
                            <th class="border border-linen/60 p-3 text-left font-bold">Nama Produk</th>
                            <th class="border border-linen/60 p-3 text-right font-bold">Harga</th>
                            <th class="border border-linen/60 p-3 text-right font-bold">Jumlah Terjual</th>
                            <th class="border border-linen/60 p-3 text-right font-bold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productDetails as $index => $item)
                            <tr>
                                <td class="border border-linen/60 p-3">{{ $index + 1 }}</td>
                                <td class="border border-linen/60 p-3">{{ $item['produk_name'] }}</td>
                                <td class="border border-linen/60 p-3 text-right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td class="border border-linen/60 p-3 text-right font-semibold">{{ $item['quantity'] }} pcs</td>
                                <td class="border border-linen/60 p-3 text-right font-semibold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border border-linen/60 p-3 text-center text-gray-500">Tidak ada data produk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-2xl">
            <div>
                <p class="text-sm text-gray-600">Total Item Terjual</p>
                <p class="text-2xl font-bold text-ink">{{ $report->total_items }} pcs</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Pendapatan</p>
                <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($report->total_income, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-2xl">
            <div>
                <p class="text-sm text-gray-600">Total Biaya (Expense)</p>
                <p class="text-2xl font-bold text-red-600">Rp {{ number_format($report->total_expense, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Keuntungan Bersih</p>
                <p class="text-2xl font-bold {{ $report->total_income - $report->total_expense >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                    Rp {{ number_format($report->total_income - $report->total_expense, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex gap-3">
            <a href="{{ route('franchisee.financial.edit', ['report' => $report->financial_id]) }}" class="premium-button">
                Edit Laporan
            </a>
            <a href="{{ route('franchisee.financial.create') }}" class="premium-button-soft">
                Input Baru
            </a>
            <a href="{{ route('franchisee.financial.index') }}" class="premium-button-soft">
                Kembali ke Daftar
            </a>
        </div>
    </div>
</main>

@endsection
