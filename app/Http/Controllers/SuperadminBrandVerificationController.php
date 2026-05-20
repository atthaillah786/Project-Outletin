<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperadminBrandVerificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'superadmin') {
            abort(403, 'Akses hanya untuk superadmin.');
        }

        $brands = Brand::with('franchisor')
            ->where('status', 'pending')
            ->orderBy('brand_id', 'desc')
            ->get();

        return view('superadmin.brand-verification', compact('brands'));
    }

    public function approve($id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'superadmin') {
            abort(403, 'Akses hanya untuk superadmin.');
        }

        $brand = Brand::findOrFail($id);

        $brand->update([
            'status' => 'approved',
            'verified_by' => $user->user_id,
            'verified_at' => now(),
            'rejection_note' => null,
        ]);

        return back()->with('success', 'Brand berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'superadmin') {
            abort(403, 'Akses hanya untuk superadmin.');
        }

        $validated = $request->validate([
            'rejection_note' => ['nullable', 'string'],
        ]);

        $brand = Brand::findOrFail($id);

        $brand->update([
            'status' => 'rejected',
            'verified_by' => $user->user_id,
            'verified_at' => now(),
            'rejection_note' => $validated['rejection_note'] ?? 'Brand belum memenuhi syarat.',
        ]);

        return back()->with('success', 'Brand berhasil ditolak.');
    }
}