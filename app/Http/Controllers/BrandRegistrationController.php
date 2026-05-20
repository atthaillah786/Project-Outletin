<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BrandRegistrationController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk pemilik brand.');
        }

        $brand = Brand::where('franchisor_id', $user->user_id)
            ->latest('brand_id')
            ->first();

        if ($brand && $brand->status === 'approved') {
            return redirect()->route('franchisor.dashboard');
        }

        if ($brand && $brand->status === 'pending') {
            return view('brand.status', compact('brand'));
        }

        return view('brand.register', compact('brand'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk pemilik brand.');
        }

        $validated = $request->validate([
            'brand_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'logo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $existingBrand = Brand::where('franchisor_id', $user->user_id)
            ->latest('brand_id')
            ->first();

        if ($existingBrand && $existingBrand->status === 'approved') {
            return redirect()->route('franchisor.dashboard');
        }

        if ($existingBrand && $existingBrand->status === 'pending') {
            return redirect()
                ->route('brand.registration.create')
                ->with('success', 'Brand Anda masih menunggu verifikasi superadmin.');
        }

        $logoPath = $request->file('logo')->store('brand-logos', 'public');

        if ($existingBrand && $existingBrand->status === 'rejected') {
            if ($existingBrand->logo_path) {
                Storage::disk('public')->delete($existingBrand->logo_path);
            }

            $existingBrand->update([
                'brand_name' => $validated['brand_name'],
                'description' => $validated['description'] ?? null,
                'logo_path' => $logoPath,
                'status' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
                'rejection_note' => null,
            ]);
        } else {
            Brand::create([
                'franchisor_id' => $user->user_id,
                'brand_name' => $validated['brand_name'],
                'description' => $validated['description'] ?? null,
                'logo_path' => $logoPath,
                'status' => 'pending',
            ]);
        }

        return redirect()
            ->route('brand.registration.create')
            ->with('success', 'Brand berhasil didaftarkan. Silakan tunggu verifikasi superadmin.');
    }
}