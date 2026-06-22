@extends('layouts.dashboard')

@section('title', $existingReport ?? false ? 'Edit Laporan Keuangan' : 'Input Laporan Keuangan Harian')

@section('content')
<section class="mb-8">
    <h1 class="text-center text-5xl font-extrabold text-ink mb-1">
        {{ isset($existingReport) && $existingReport ? 'Edit Laporan Keuangan' : 'Input Laporan Keuangan Harian' }}
    </h1>
</section>

<main class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        @if(isset($existingReport) && $existingReport)
            <div class="flex items-start gap-3 rounded-2xl border-2 border-amber-400/40 bg-amber-50/90 px-5 py-4 text-sm font-semibold text-amber-900 shadow-sm mb-6" role="alert">
                <svg class="w-5 h-5 shrink-0 mt-0.5 text-amber-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="font-bold">Edit Laporan</p>
                    <p class="text-xs font-normal mt-0.5">Anda sedang mengedit laporan untuk {{ $existingReport->outlet->outlet_name ?? 'outlet ini' }} pada tanggal {{ \Carbon\Carbon::parse($existingReport->report_date)->format('d M Y') }}. Data sebelumnya akan diperbarui.</p>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="premium-alert-success mb-4" role="alert">
                <svg class="w-5 h-5 shrink-0 mt-0.5 text-emerald-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="premium-alert-error mb-4" role="alert">
                <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ (isset($existingReport) && $existingReport) ? route('franchisee.financial.update', $existingReport->financial_id) : route('franchisee.financial.store') }}" class="space-y-4">
            @csrf
            @if(isset($existingReport) && $existingReport)
                @method('PUT')
            @endif

            <div>
                <label for="outlet_select" class="premium-label">Pilih Outlet</label>
                <select id="outlet_select" name="outlet_id" class="premium-input" {{ isset($existingReport) && $existingReport ? 'disabled' : '' }}>
                    <option value="">-- Pilih Outlet --</option>
                    @foreach($outlets as $o)
                        <option value="{{ $o->outlet_id }}" {{ isset($selectedOutlet) && $selectedOutlet == $o->outlet_id ? 'selected' : '' }}>{{ $o->outlet_name }}</option>
                    @endforeach
                </select>
                @if(isset($existingReport) && $existingReport)
                    <input type="hidden" name="outlet_id" value="{{ $selectedOutlet }}">
                @endif
            </div>

            <div>
                <label for="report_date" class="premium-label">Tanggal Laporan</label>
                <input id="report_date" type="date" name="report_date" value="{{ $selectedDate ?? date('Y-m-d') }}" class="premium-input" {{ isset($existingReport) && $existingReport ? 'disabled' : '' }} required>
                @if(isset($existingReport) && $existingReport)
                    <input type="hidden" name="report_date" value="{{ $selectedDate }}">
                @endif
            </div>

            <div id="products_section" style="{{ $selectedOutlet ? 'display:block' : 'display:none' }}">
                <h3 class="font-bold text-ink mb-2">Produk & Jumlah Terjual</h3>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse premium-table">
                        <caption class="sr-only">Daftar produk dan jumlah terjual</caption>
                        <thead>
                            <tr>
                                <th scope="col" class="border border-linen/60 p-2 text-left">Produk</th>
                                <th scope="col" class="border border-linen/60 p-2 text-left">Harga</th>
                                <th scope="col" class="border border-linen/60 p-2 text-left">Jumlah Terjual</th>
                            </tr>
                        </thead>
                        <tbody id="products_table"></tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <label for="total_expense" class="premium-label">Total Biaya (Expense)</label>
                    <input id="total_expense" type="number" step="0.01" name="total_expense" class="premium-input" placeholder="0.00" value="{{ (isset($existingReport) && $existingReport) ? $existingReport->total_expense : '0' }}">
                </div>

                <div class="mt-3 p-4 bg-gray-50 rounded-2xl">
                    <p class="text-sm text-ink">Total Items: <span id="total_items" class="font-bold">{{ isset($existingReport) && $existingReport ? $existingReport->total_items : 0 }}</span></p>
                    <p class="text-sm text-ink">Total Income: <span id="total_income" class="font-bold">{{ isset($existingReport) && $existingReport ? 'Rp ' . number_format($existingReport->total_income, 0, ',', '.') : 'Rp 0' }}</span></p>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="premium-button">
                    {{ (isset($existingReport) && $existingReport) ? 'Perbarui Laporan' : 'Simpan Laporan' }}
                </button>
                @if(isset($existingReport) && $existingReport)
                    <a href="{{ route('franchisee.financial.create') }}" class="premium-button-soft">
                        Input Baru
                    </a>
                @endif
            </div>
        </form>
    </div>
</main>

<script>
const existingMode = {{ isset($existingReport) && $existingReport ? 'true' : 'false' }};
const existingExpense = {{ (isset($existingReport) && $existingReport) ? number_format($existingReport->total_expense, 2, '.', '') : '0' }};
const existingTotalItems = {{ isset($existingReport) && $existingReport ? $existingReport->total_items : 0 }};
const existingTotalIncome = {{ isset($existingReport) && $existingReport ? $existingReport->total_income : 0 }};
const existingProductDetails = @json($existingReport->product_details ?? []);

function loadProductsForSelectedOutlet(){
    const select = document.getElementById('outlet_select');
    const outletId = select.value;
    const section = document.getElementById('products_section');
    const table = document.getElementById('products_table');
    table.innerHTML = '';
    if(!outletId){ section.style.display = 'none'; return; }
    fetch('/dashboard/franchisee/outlets/' + outletId + '/products')
        .then(r => r.json())
        .then(data => {
            section.style.display = 'block';
            data.forEach(p => {
                const tr = document.createElement('tr');
                
                const existingItem = existingProductDetails.find(item => item.produk_id == p.produk_id || item.produk_id == parseInt(p.produk_id));
                const existingQty = existingItem ? existingItem.quantity : 0;
                
                tr.innerHTML = `
                    <td class="border border-linen/60 p-2">${p.produk_name}</td>
                    <td class="border border-linen/60 p-2">${parseFloat(p.Price).toFixed(2)}</td>
                    <td class="border border-linen/60 p-2"><input type="number" min="0" name="quantities[${p.produk_id}]" value="${existingQty}" class="w-24 premium-input qty-input" data-price="${p.Price}"></td>
                `;
                table.appendChild(tr);
            });

            function recalc(){
                let totalItems = 0;
                let totalIncome = 0;
                document.querySelectorAll('.qty-input').forEach(inp => {
                    const q = parseInt(inp.value) || 0;
                    const price = parseFloat(inp.dataset.price) || 0;
                    totalItems += q;
                    totalIncome += q * price;
                });
                document.getElementById('total_items').innerText = totalItems;
                document.getElementById('total_income').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalIncome);
            }

            document.querySelectorAll('.qty-input').forEach(i => i.addEventListener('input', recalc));
            
            recalc();
        });
}

document.getElementById('outlet_select').addEventListener('change', loadProductsForSelectedOutlet);

document.addEventListener('DOMContentLoaded', function(){
    const selected = '{{ $selectedOutlet ?? '' }}';
    if(selected){
        const sel = document.getElementById('outlet_select');
        sel.value = selected;
        loadProductsForSelectedOutlet();
    }

    if (existingMode) {
        document.getElementById('total_items').innerText = existingTotalItems;
        document.getElementById('total_income').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(existingTotalIncome);
    }
});
</script>

@endsection