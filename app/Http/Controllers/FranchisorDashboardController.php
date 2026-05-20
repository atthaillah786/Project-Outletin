<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Outlet;
use App\Models\FinancialReport;
use App\Models\FranchiseBrand;
use Illuminate\Support\Facades\Auth;

class FranchisorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk pemilik brand.');
        }

        $hasApprovedBrand = Brand::where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->exists();

        if (!$hasApprovedBrand) {
            return redirect()
                ->route('brand.registration.create')
                ->with('success', 'Brand Anda belum diverifikasi oleh superadmin.');
        }

        $brandIds = Brand::where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->pluck('brand_id');

        $brands = Brand::where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->orderBy('brand_name')
            ->get();

        $outlets = Outlet::with(['brand', 'franchise'])
            ->whereIn('brand_id', $brandIds)
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingApplications = FranchiseBrand::with(['franchise', 'brand'])
            ->whereIn('brand_id', $brandIds)
            ->where('status', 'pending')
            ->orderBy('franchise_brands_id', 'desc')
            ->get();

        $financialReports = FinancialReport::query()
            ->join('outlets', 'financial_reports.outlet_id', '=', 'outlets.outlet_id')
            ->whereIn('outlets.brand_id', $brandIds)
            ->selectRaw("DATE_FORMAT(financial_reports.report_date, '%Y-%m') as month")
            ->selectRaw("SUM(financial_reports.total_income) as total_income")
            ->selectRaw("SUM(financial_reports.total_expense) as total_expense")
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

        return view('dashboard.franchisor', compact(
            'brands',
            'outlets',
            'pendingApplications',
            'chartLabels',
            'incomeData',
            'expenseData',
            'profitData',
            'totalIncome',
            'totalExpense',
            'totalProfit'
        ));
    }

    public function approveApplication($id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk pemilik brand.');
        }

        $brandIds = Brand::where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->pluck('brand_id');

        $application = FranchiseBrand::where('franchise_brands_id', $id)
            ->whereIn('brand_id', $brandIds)
            ->firstOrFail();

        $application->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('franchisor.dashboard')
            ->with('success', 'Pengajuan franchise berhasil diterima.');
    }

    public function rejectApplication($id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk pemilik brand.');
        }

        $brandIds = Brand::where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->pluck('brand_id');

        $application = FranchiseBrand::where('franchise_brands_id', $id)
            ->whereIn('brand_id', $brandIds)
            ->firstOrFail();

        $application->update([
            'status' => 'rejected',
        ]);

        return redirect()
            ->route('franchisor.dashboard')
            ->with('success', 'Pengajuan franchise berhasil ditolak.');
    }
}