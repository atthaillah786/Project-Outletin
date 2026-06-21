<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Outlet;
use App\Models\FinancialReport;
use App\Models\FranchiseBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FranchiseeDashboardController extends Controller
{
    // Route GET: /dashboard/franchisee/brands/{id}/apply
    // Perbaiki error: method createOutletApplication() dipanggil oleh route, namun sebelumnya tidak ada.
    public function createOutletApplication($id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk franchisee.');
        }

        // Ambil brand yang sudah approved
        $brand = Brand::where('status', 'approved')
            ->where('brand_id', $id)
            ->firstOrFail();

        // Jika sudah pernah apply, kembalikan ke dashboard
        $existing = FranchiseBrand::where('franchise_id', $user->user_id)
            ->where('brand_id', $brand->brand_id)
            ->first();

        if ($existing) {
            return redirect()
                ->route('franchisee.dashboard')
                ->with('success', 'Anda sudah pernah mengajukan ke brand ini.');
        }

        // Tanpa mengubah fungsi utama, GET ini langsung melakukan proses apply yang sebelumnya ada di applyToBrand()
        // karena project belum memiliki blade khusus form aplikasi.
        return $this->applyToBrand($brand->brand_id);
    }

    public function index()
    {

        $user = Auth::user();

        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk franchisee.');
        }

        $applications = FranchiseBrand::with('brand')
            ->where('franchise_id', $user->user_id)
            ->orderBy('franchise_brands_id', 'desc')
            ->get();

        $appliedBrandIds = $applications->pluck('brand_id');

        $availableBrands = Brand::where('status', 'approved')
            ->whereNotIn('brand_id', $appliedBrandIds)
            ->orderBy('brand_name')
            ->get();

        $outlets = Outlet::with('brand')
            ->where('franchise_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $outletIds = $outlets->pluck('outlet_id');

        $financialReports = FinancialReport::whereIn('outlet_id', $outletIds)
            ->selectRaw("DATE_FORMAT(report_date, '%Y-%m') as month")
            ->selectRaw("SUM(total_income) as total_income")
            ->selectRaw("SUM(total_expense) as total_expense")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chartLabels = $financialReports->pluck('month');
        $incomeData = $financialReports->pluck('total_income');
        $expenseData = $financialReports->pluck('total_expense');

        $profitData = $financialReports->map(function ($report) {
            return (float) $report->total_income - (float) $report->total_expense;
        });

        $totalIncome = $financialReports->sum('total_income');
        $totalExpense = $financialReports->sum('total_expense');
        $totalProfit = $totalIncome - $totalExpense;

        return view('dashboard.franchisee', compact(
            'applications',
            'availableBrands',
            'outlets',
            'chartLabels',
            'incomeData',
            'expenseData',
            'profitData',
            'totalIncome',
            'totalExpense',
            'totalProfit'
        ));
    }

    public function applyToBrand($id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk franchisee.');
        }

        $brand = Brand::where('status', 'approved')
            ->where('brand_id', $id)
            ->firstOrFail();

        $existing = FranchiseBrand::where('franchise_id', $user->user_id)
            ->where('brand_id', $brand->brand_id)
            ->first();

        if ($existing) {
            return redirect()
                ->route('franchisee.dashboard')
                ->with('success', 'Anda sudah pernah mengajukan ke brand ini.');
        }

        // Buat outlet (pending) agar tampil di dashboard franchisor.
        // Dashboard franchisor saat ini mengambil pengajuan dari table `outlets`.
        // Ini tidak mengubah proses utama (pengajuan), hanya memastikan data yang dibaca sesuai.
        $outlet = Outlet::create([
            'franchise_id' => $user->user_id,
            'brand_id' => $brand->brand_id,
            'outlet_name' => 'Outlet Pending',
            'address' => null,
            'status' => 'pending',
        ]);

        // Tetap simpan relasi franchise_brand (jika dipakai halaman lain)
        FranchiseBrand::create([
            'franchise_id' => $user->user_id,
            'brand_id' => $brand->brand_id,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('franchisee.dashboard')
            ->with('success', 'Pengajuan franchise berhasil dikirim.');
    }
}