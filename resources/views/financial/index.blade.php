@extends('layouts.dashboard')

@section('title', 'Daftar Laporan Keuangan')

@section('content')
<section class="mb-8">
    <div class="flex items-center justify-between">
        <h1 class="text-5xl font-extrabold text-ink mb-1">Daftar Laporan Keuangan</h1>
        <a href="{{ route('franchisee.financial.create') }}" class="premium-button">
            + Input Baru
        </a>
    </div>
</section>

<main class="container mx-auto px-4 py-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        @if($reports->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="px-4 py-3 text-left font-bold text-ink">Outlet</th>
                            <th class="px-4 py-3 text-left font-bold text-ink">Tanggal</th>
                            <th class="px-4 py-3 text-right font-bold text-ink">Total Item</th>
                            <th class="px-4 py-3 text-right font-bold text-ink">Pendapatan</th>
                            <th class="px-4 py-3 text-right font-bold text-ink">Biaya</th>
                            <th class="px-4 py-3 text-right font-bold text-ink">Keuntungan</th>
                            <th class="px-4 py-3 text-center font-bold text-ink">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3 text-ink">{{ $report->outlet->outlet_name }}</td>
                                <td class="px-4 py-3 text-ink">{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-right font-semibold">{{ $report->total_items }} pcs</td>
                                <td class="px-4 py-3 text-right font-semibold text-emerald-600">Rp {{ number_format($report->total_income, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-red-600">Rp {{ number_format($report->total_expense, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-bold {{ $report->total_income - $report->total_expense >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                    Rp {{ number_format($report->total_income - $report->total_expense, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <a href="{{ route('franchisee.financial.show', $report->financial_id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm" title="Lihat Detail">
                                            Lihat
                                        </a>
                                        <a href="{{ route('franchisee.financial.edit', $report->financial_id) }}" class="text-amber-600 hover:text-amber-800 font-semibold text-sm" title="Edit">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reports->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $reports->links('pagination::tailwind') }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 font-semibold mb-4">Belum ada laporan keuangan</p>
                <a href="{{ route('franchisee.financial.create') }}" class="premium-button">
                    Buat Laporan Pertama
                </a>
            </div>
        @endif
    </div>
</main>

@endsection
