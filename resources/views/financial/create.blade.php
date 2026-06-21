@extends('layouts.dashboard')

@section('title', 'Input Laporan Keuangan Harian')

@section('content')
<section class="mb-8">
    <h1 class="premium-section-title">Input Laporan Keuangan Harian</h1>
</section>
<main class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Input Laporan Keuangan Harian</h1>

        @if(session('success'))
            <div class="bg-green-100 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('franchisee.financial.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold">Pilih Outlet</label>
                <select id="outlet_select" name="outlet_id" class="w-full border rounded p-2">
                    <option value="">-- Pilih Outlet --</option>
                    @foreach($outlets as $o)
                        <option value="{{ $o->outlet_id }}" {{ isset($selectedOutlet) && $selectedOutlet == $o->outlet_id ? 'selected' : '' }}>{{ $o->outlet_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold">Tanggal Laporan</label>
                <input type="date" name="report_date" value="{{ date('Y-m-d') }}" class="w-full border rounded p-2" required>
            </div>

            <div id="products_section" style="display:none">
                <h3 class="font-semibold">Produk & Jumlah Terjual</h3>
                <table class="w-full mt-2 border-collapse">
                    <thead>
                        <tr>
                            <th class="border p-2 text-left">Produk</th>
                            <th class="border p-2 text-left">Harga</th>
                            <th class="border p-2 text-left">Jumlah Terjual</th>
                        </tr>
                    </thead>
                    <tbody id="products_table"></tbody>
                </table>

                <div class="mt-4">
                    <label class="block text-sm font-semibold">Total Biaya (Expense)</label>
                    <input type="number" step="0.01" name="total_expense" class="w-full border rounded p-2" placeholder="0.00">
                </div>

                <div class="mt-3">
                    <p>Total Items: <span id="total_items">0</span></p>
                    <p>Total Income: Rp <span id="total_income">0.00</span></p>
                </div>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Laporan</button>
            </div>
        </form>
    </div>
</main>

<script>
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
                tr.innerHTML = `
                    <td class="border p-2">${p.produk_name}</td>
                    <td class="border p-2">${parseFloat(p.Price).toFixed(2)}</td>
                    <td class="border p-2"><input type="number" min="0" name="quantities[${p.produk_id}]" value="0" class="w-24 border rounded p-1 qty-input" data-price="${p.Price}"></td>
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
                document.getElementById('total_income').innerText = totalIncome.toFixed(2);
            }

            document.querySelectorAll('.qty-input').forEach(i => i.addEventListener('input', recalc));
        });
}

document.getElementById('outlet_select').addEventListener('change', loadProductsForSelectedOutlet);

// If a selected outlet was provided from the server, trigger loading after DOM.
document.addEventListener('DOMContentLoaded', function(){
    const selected = '{{ $selectedOutlet ?? '' }}';
    if(selected){
        const sel = document.getElementById('outlet_select');
        sel.value = selected;
        loadProductsForSelectedOutlet();
    }
});
</script>
</script>

@endsection
