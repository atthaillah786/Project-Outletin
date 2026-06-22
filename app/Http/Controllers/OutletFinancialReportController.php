<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Produk;
use App\Models\FinancialReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutletFinancialReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk pemilik outlet.');
        }

        $outlets = Outlet::where('franchise_id', $user->user_id)->get();
        
        // Ambil semua laporan untuk outlet milik user
        $reports = FinancialReport::whereIn('outlet_id', $outlets->pluck('outlet_id'))
            ->with('outlet')
            ->orderBy('report_date', 'desc')
            ->paginate(15);

        return view('financial.index', compact('reports', 'outlets'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk pemilik outlet.');
        }

        $outlets = Outlet::where('franchise_id', $user->user_id)->get();

        $selectedOutlet = $request->query('outlet');
        $selectedDate = $request->query('report_date') ?: date('Y-m-d');

        // Cek apakah sudah ada laporan untuk outlet + tanggal dipilih
        $existingReport = null;
        if ($selectedOutlet) {
            $existingReport = FinancialReport::where('outlet_id', $selectedOutlet)
                ->where('report_date', $selectedDate)
                ->first();
        }

        return view('financial.create', compact('outlets', 'selectedOutlet', 'selectedDate', 'existingReport'));
    }

    public function outletProducts($id)
    {
        $user = Auth::user();

        $outlet = Outlet::findOrFail($id);
        if ($outlet->franchise_id !== $user->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $products = Produk::where('brand_id', $outlet->brand_id)->get(['produk_id', 'produk_name', 'Price']);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk pemilik outlet.');
        }

        $data = $request->validate([
            'outlet_id' => ['required', 'integer'],
            'report_date' => ['required', 'date'],
            'quantities' => ['nullable', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0'],
            'total_expense' => ['nullable', 'numeric'],
        ]);

        $outlet = Outlet::findOrFail($data['outlet_id']);
        if ($outlet->franchise_id !== $user->user_id) {
            abort(403, 'Outlet tidak ditemukan atau tidak dimiliki.');
        }

        // CEK: apakah laporan sudah ada?
        $existing = FinancialReport::where('outlet_id', $outlet->outlet_id)
            ->where('report_date', $data['report_date'])
            ->first();

        if ($existing) {
            // Alihkan ke halaman edit dengan pesan
            return redirect()->route('franchisee.financial.edit', [
                'report' => $existing->financial_id,
            ])->with('error', 'Laporan untuk tanggal ini sudah ada. Silakan edit laporan yang ada.');
        }

        $quantities = $data['quantities'] ?? [];

        $totalItems = 0;
        $totalIncome = 0;
        $productDetails = [];

        foreach ($quantities as $produkId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) continue;
            $produk = Produk::find($produkId);
            if (!$produk) continue;
            $totalItems += $qty;
            $price = (float) $produk->Price;
            $totalIncome += $price * $qty;
            
            $productDetails[] = [
                'produk_id' => $produkId,
                'produk_name' => $produk->produk_name,  
                'price' => $price,
                'quantity' => $qty,
                'subtotal' => $price * $qty,
            ];
        }

        $report = FinancialReport::create([
            'outlet_id' => $outlet->outlet_id,
            'report_date' => $data['report_date'],
            'total_items' => $totalItems,
            'total_income' => $totalIncome,
            'total_expense' => $data['total_expense'] ?? 0,
            'product_details' => $productDetails,
        ]);

        return redirect()->route('franchisee.financial.create', [
            'outlet' => $outlet->outlet_id,
            'report_date' => $data['report_date'],
        ])->with('success', 'Laporan keuangan harian berhasil disimpan. Total item: ' . $totalItems);
    }

    /**
     * Tampilkan form edit laporan yang sudah ada.
     */
    public function edit($reportId)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk pemilik outlet.');
        }

        $report = FinancialReport::findOrFail($reportId);
        $outlet = Outlet::findOrFail($report->outlet_id);

        if ($outlet->franchise_id !== $user->user_id) {
            abort(403, 'Outlet tidak dimiliki oleh Anda.');
        }

        $outlets = Outlet::where('franchise_id', $user->user_id)->get();
        $selectedOutlet = $outlet->outlet_id;
        $selectedDate = $report->report_date->toDateString();
        $existingReport = $report;

        return view('financial.create', compact('outlets', 'selectedOutlet', 'selectedDate', 'existingReport'));
    }

    /**
     * Update laporan yang sudah ada.
     */
    public function update(Request $request, $reportId)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk pemilik outlet.');
        }

        $report = FinancialReport::findOrFail($reportId);
        $outlet = Outlet::findOrFail($report->outlet_id);

        if ($outlet->franchise_id !== $user->user_id) {
            abort(403, 'Outlet tidak dimiliki oleh Anda.');
        }

        $data = $request->validate([
            'quantities' => ['nullable', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0'],
            'total_expense' => ['nullable', 'numeric'],
        ]);

        $quantities = $data['quantities'] ?? [];

        $totalItems = 0;
        $totalIncome = 0;
        $productDetails = [];

        foreach ($quantities as $produkId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) continue;
            $produk = Produk::find($produkId);
            if (!$produk) continue;
            $totalItems += $qty;
            $price = (float) $produk->Price;
            $totalIncome += $price * $qty;
            
            $productDetails[] = [
                'produk_id' => $produkId,
                'produk_name' => $produk->produk_name,
                'price' => $price,
                'quantity' => $qty,
                'subtotal' => $price * $qty,
            ];
        }

        $report->update([
            'total_items' => $totalItems,
            'total_income' => $totalIncome,
            'total_expense' => $data['total_expense'] ?? 0,
            'product_details' => $productDetails,
        ]);

        return redirect()->route('franchisee.financial.edit', [
            'report' => $report->financial_id,
        ])->with('success', 'Laporan keuangan harian berhasil diperbarui. Total item: ' . $totalItems);
    }

    /**
     * Tampilkan detail laporan dengan breakdown produk terjual.
     */
    public function show($reportId)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk pemilik outlet.');
        }

        $report = FinancialReport::findOrFail($reportId);
        $outlet = Outlet::findOrFail($report->outlet_id);

        if ($outlet->franchise_id !== $user->user_id) {
            abort(403, 'Laporan tidak dimiliki oleh Anda.');
        }

        $productDetails = $report->product_details ?? [];
        
        return view('financial.show', compact('report', 'outlet', 'productDetails'));
    }
}