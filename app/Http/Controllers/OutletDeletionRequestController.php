<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Outlet;
use App\Models\OutletDeletionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutletDeletionRequestController extends Controller
{
    public function create($outletId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk franchisee.');
        }

        $outlet = Outlet::where('outlet_id', $outletId)
            ->where('franchise_id', $user->user_id)
            ->where('status', 'approved')
            ->firstOrFail();

        if (OutletDeletionRequest::where('outlet_id', $outlet->outlet_id)
            ->where('status', 'pending')
            ->exists()) {
            return redirect()
                ->route('franchisee.dashboard')
                ->withErrors(['delete' => 'Anda sudah memiliki permintaan penghapusan untuk outlet ini.']);
        }

        if (!$outlet->hasDependentRecords()) {
            return redirect()
                ->route('franchisee.dashboard')
                ->withErrors(['delete' => 'Outlet tidak memiliki data terkait untuk diajukan penghapusan.']);
        }

        return view('dashboard.franchisee-delete-outlet-request', compact('outlet'));
    }

    public function store(Request $request, $outletId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk franchisee.');
        }

        $outlet = Outlet::where('outlet_id', $outletId)
            ->where('franchise_id', $user->user_id)
            ->where('status', 'approved')
            ->firstOrFail();

        if (OutletDeletionRequest::where('outlet_id', $outlet->outlet_id)
            ->where('status', 'pending')
            ->exists()) {
            return back()
                ->withErrors(['delete' => 'Permintaan penghapusan untuk outlet ini sedang diproses.'])
                ->withInput();
        }

        if (!$outlet->hasDependentRecords()) {
            return back()
                ->withErrors(['delete' => 'Outlet tidak memiliki data terkait untuk diajukan penghapusan.'])
                ->withInput();
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        OutletDeletionRequest::create([
            'outlet_id' => $outlet->outlet_id,
            'franchise_id' => $user->user_id,
            'brand_id' => $outlet->brand_id,
            'outlet_name' => $outlet->outlet_name,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('franchisee.dashboard')
            ->with('success', 'Permintaan penghapusan outlet berhasil dikirim ke franchisor.');
    }

    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk franchisor.');
        }

        $brandIds = Brand::where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->pluck('brand_id');

        $requests = OutletDeletionRequest::with(['outlet', 'franchise', 'brand'])
            ->whereIn('brand_id', $brandIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.franchisor-delete-outlet-requests', compact('requests'));
    }

    public function approve($id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk franchisor.');
        }

        $request = OutletDeletionRequest::with('outlet')
            ->where('outlet_deletion_request_id', $id)
            ->firstOrFail();

        if ($request->status !== 'pending') {
            return redirect()
                ->route('franchisor.dashboard')
                ->withErrors(['delete' => 'Permintaan penghapusan ini sudah diproses.']);
        }

        if (!$request->outlet || $request->outlet->status !== 'approved') {
            return redirect()
                ->route('franchisor.dashboard')
                ->withErrors(['delete' => 'Outlet tidak ditemukan atau tidak lagi valid.']);
        }

        $request->outlet->deleteWithDependencies();

        $request->update(['status' => 'approved']);

        return redirect()
            ->route('franchisor.dashboard')
            ->with('success', 'Permintaan penghapusan outlet disetujui.');
    }

    public function reject($id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk franchisor.');
        }

        $request = OutletDeletionRequest::where('outlet_deletion_request_id', $id)
            ->firstOrFail();

        if ($request->status !== 'pending') {
            return redirect()
                ->route('franchisor.dashboard')
                ->withErrors(['delete' => 'Permintaan penghapusan ini sudah diproses.']);
        }

        $request->update(['status' => 'rejected']);

        return redirect()
            ->route('franchisor.dashboard')
            ->with('success', 'Permintaan penghapusan outlet ditolak.');
    }
}
