<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Outlet;
use App\Models\FinancialReport;
use App\Models\FranchiseBrand;
use App\Models\OutletDeletionRequest;
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

        $brands = Brand::where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->orderBy('brand_name')
            ->get();

        $brandIds = $brands->pluck('brand_id');

        $applications = Outlet::with(['brand', 'franchise'])
            ->whereIn('brand_id', $brandIds)
            ->orderBy('outlet_id', 'desc')
            ->get();

        $pendingApplications = $applications->where('status', 'pending')->values();

        $outlets = $applications->where('status', 'approved')->values();

        $financialReports = FinancialReport::query()
            ->join('outlets', 'financial_reports.outlet_id', '=', 'outlets.outlet_id')
            ->whereIn('outlets.brand_id', $brandIds)
            ->where('outlets.status', 'approved')
            ->selectRaw("DATE_FORMAT(financial_reports.report_date, '%Y-%m') as month")
            ->selectRaw("SUM(financial_reports.total_income) as total_income")
            ->selectRaw("SUM(financial_reports.total_expense) as total_expense")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chartLabels = $financialReports->pluck('month')->values()->toArray();

        $incomeData = $financialReports
            ->pluck('total_income')
            ->map(fn ($value) => (float) $value)
            ->values()
            ->toArray();

        $expenseData = $financialReports
            ->pluck('total_expense')
            ->map(fn ($value) => (float) $value)
            ->values()
            ->toArray();

        $profitData = $financialReports
            ->map(fn ($report) => (float) $report->total_income - (float) $report->total_expense)
            ->values()
            ->toArray();

        $totalIncome = array_sum($incomeData);
        $totalExpense = array_sum($expenseData);
        $totalProfit = $totalIncome - $totalExpense;

        $deletionRequestCount = OutletDeletionRequest::whereIn('brand_id', $brandIds)
            ->where('status', 'pending')
            ->count();

        return view('dashboard.franchisor', compact(
            'brands',
            'outlets',
            'applications',
            'pendingApplications',
            'chartLabels',
            'incomeData',
            'expenseData',
            'profitData',
            'totalIncome',
            'totalExpense',
            'totalProfit',
            'deletionRequestCount'
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

        $outlet = Outlet::where('outlet_id', $id)
            ->whereIn('brand_id', $brandIds)
            ->first();

        if (!$outlet) {
            return redirect()
                ->route('franchisor.dashboard')
                ->withErrors([
                    'approve' => 'Pengajuan tidak ditemukan untuk brand anda (pastikan brand sudah approved dan pengajuan masih pending).',
                ]);
        }

        // Hanya izinkan approve untuk outlet yang pending
        if ($outlet->status !== 'pending') {
            return redirect()
                ->route('franchisor.dashboard')
                ->withErrors([
                    'approve' => 'Pengajuan ini sudah diproses sebelumnya.',
                ]);
        }

        $outlet->update([
            'status' => 'approved',
        ]);

        $franchiseBrand = FranchiseBrand::where('franchise_id', $outlet->franchise_id)
            ->where('brand_id', $outlet->brand_id)
            ->first();

        if ($franchiseBrand) {
            $franchiseBrand->update([
                'status' => 'approved',
            ]);
        } else {
            FranchiseBrand::create([
                'franchise_id' => $outlet->franchise_id,
                'brand_id' => $outlet->brand_id,
                'status' => 'approved',
            ]);
        }

        return redirect()
            ->route('franchisor.dashboard')
            ->with('success', 'Pengajuan outlet berhasil diterima.');
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

        $outlet = Outlet::where('outlet_id', $id)
            ->whereIn('brand_id', $brandIds)
            ->first();

        if (!$outlet) {
            return redirect()
                ->route('franchisor.dashboard')
                ->withErrors([
                    'reject' => 'Pengajuan tidak ditemukan untuk brand anda (pastikan brand sudah approved dan pengajuan masih pending).',
                ]);
        }

        // Hanya izinkan reject untuk outlet yang pending
        if ($outlet->status !== 'pending') {
            return redirect()
                ->route('franchisor.dashboard')
                ->withErrors([
                    'reject' => 'Pengajuan ini sudah diproses sebelumnya.',
                ]);
        }

        $franchiseBrand = FranchiseBrand::where('franchise_id', $outlet->franchise_id)
            ->where('brand_id', $outlet->brand_id)
            ->first();

        if ($franchiseBrand) {
            $franchiseBrand->update([
                'status' => 'rejected',
            ]);
        }

        $outlet->delete();

        return redirect()
            ->route('franchisor.dashboard')
            ->with('success', 'Pengajuan outlet berhasil ditolak.');
    }
}