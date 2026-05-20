<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\User;
use App\Models\Outlet;
use App\Models\FranchiseBrand;
use App\Models\FinancialReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperadminDashboardController extends Controller
{
    private function authorizeSuperadmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses hanya untuk superadmin.');
        }
    }

    public function index()
    {
        $this->authorizeSuperadmin();

        $totalUsers = User::count();
        $totalFranchisors = User::where('role', 'franchisor')->count();
        $totalFranchises = User::where('role', 'franchise')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        $totalBrands = Brand::count();
        $pendingBrands = Brand::where('status', 'pending')->count();
        $approvedBrands = Brand::where('status', 'approved')->count();
        $rejectedBrands = Brand::where('status', 'rejected')->count();

        $totalOutlets = Outlet::count();

        $pendingFranchiseApplications = FranchiseBrand::where('status', 'pending')->count();

        $totalIncome = FinancialReport::sum('total_income');
        $totalExpense = FinancialReport::sum('total_expense');
        $totalProfit = $totalIncome - $totalExpense;

        $brandStatusCounts = [
            'pending' => $pendingBrands,
            'approved' => $approvedBrands,
            'rejected' => $rejectedBrands,
        ];

        $userRoleCounts = [
            'franchisor' => $totalFranchisors,
            'franchise' => $totalFranchises,
            'admin' => $totalAdmins,
            'superadmin' => User::where('role', 'superadmin')->count(),
        ];

        $brandsNeedVerification = Brand::with('franchisor')
            ->where('status', 'pending')
            ->orderBy('brand_id', 'desc')
            ->get();

        $latestBrands = Brand::with('franchisor')
            ->orderBy('brand_id', 'desc')
            ->limit(8)
            ->get();

        $latestOutlets = Outlet::with(['brand', 'franchise'])
            ->orderBy('outlet_id', 'desc')
            ->limit(8)
            ->get();

        $latestFranchiseApplications = FranchiseBrand::with(['franchise', 'brand'])
            ->orderBy('franchise_brands_id', 'desc')
            ->limit(8)
            ->get();

        return view('dashboard.superadmin', compact(
            'totalUsers',
            'totalFranchisors',
            'totalFranchises',
            'totalAdmins',
            'totalBrands',
            'pendingBrands',
            'approvedBrands',
            'rejectedBrands',
            'totalOutlets',
            'pendingFranchiseApplications',
            'totalIncome',
            'totalExpense',
            'totalProfit',
            'brandStatusCounts',
            'userRoleCounts',
            'brandsNeedVerification',
            'latestBrands',
            'latestOutlets',
            'latestFranchiseApplications'
        ));
    }

    public function approveBrand($id)
    {
        $this->authorizeSuperadmin();

        $brand = Brand::findOrFail($id);

        $brand->update([
            'status' => 'approved',
            'verified_by' => Auth::user()->user_id,
            'verified_at' => now(),
            'rejection_note' => null,
        ]);

        return redirect()
            ->route('superadmin.dashboard')
            ->with('success', 'Brand berhasil disetujui.');
    }

    public function rejectBrand(Request $request, $id)
    {
        $this->authorizeSuperadmin();

        $validated = $request->validate([
            'rejection_note' => ['nullable', 'string'],
        ]);

        $brand = Brand::findOrFail($id);

        $brand->update([
            'status' => 'rejected',
            'verified_by' => Auth::user()->user_id,
            'verified_at' => now(),
            'rejection_note' => $validated['rejection_note'] ?? 'Brand belum memenuhi syarat.',
        ]);

        return redirect()
            ->route('superadmin.dashboard')
            ->with('success', 'Brand berhasil ditolak.');
    }
}