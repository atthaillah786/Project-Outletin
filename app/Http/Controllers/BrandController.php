<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Menampilkan daftar brand milik user yang login
     */
    public function index()
    {
        $brands = auth()->user()->brands;
        
        return view('brand.index', compact('brands'));
    }

    /**
     * Form untuk membuat brand baru
     */
    public function create()
    {
        return view('brand.create');
    }

    /**
     * Menyimpan brand baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => 'required|string|min:5|unique:brands',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $user = auth()->user();

        // Handle upload logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('logos', $filename, 'public');
            $validated['logo_path'] = $path;
        }

        unset($validated['logo']);
        $validated['franchisor_id'] = $user->user_id;
        $validated['status'] = 'pending';

        $brand = Brand::create($validated);

        return redirect()->route('brand.index')
            ->with('success', 'Brand berhasil dibuat. Menunggu verifikasi admin.');
    }

    /**
     * Menampilkan detail brand
     */
    public function show(Brand $brand)
    {
        $this->authorize('view', $brand);
        
        return view('brand.show', compact('brand'));
    }

    /**
     * Form untuk edit brand
     */
    public function edit(Brand $brand)
    {
        $this->authorize('update', $brand);
        
        return view('brand.edit', compact('brand'));
    }

    /**
     * Update brand di database
     */
    public function update(Request $request, Brand $brand)
    {
        $this->authorize('update', $brand);

        $validated = $request->validate([
            'brand_name' => 'required|string|min:5|unique:brands,brand_name,' . $brand->brand_id . ',brand_id',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle upload logo baru
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($brand->logo_path) {
                Storage::disk('public')->delete($brand->logo_storage_path);
            }

            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('logos', $filename, 'public');
            $validated['logo_path'] = $path;
        }

        unset($validated['logo']);
        $validated['status'] = 'pending';
        $validated['verified_by'] = null;
        $validated['verified_at'] = null;
        $validated['rejection_note'] = null;

        $brand->update($validated);

        return redirect()->route('brand.index')
            ->with('success', 'Brand berhasil diperbarui.');
    }

    /**
     * Hapus brand dari database
     */
    public function destroy(Brand $brand)
    {
        $this->authorize('delete', $brand);

        // Prevent SQL integrity constraint error when this brand still has produk
        if ($brand->produk()->exists()) {
            return redirect()->route('brand.index')
                ->with('error', 'Brand tidak bisa dihapus karena masih memiliki produk. Hapus produk terlebih dahulu.');
        }

        // Hapus logo jika ada
        if ($brand->logo_path) {
            Storage::disk('public')->delete($brand->logo_storage_path);
        }

        $brand->delete();

        return redirect()->route('brand.index')
            ->with('success', 'Brand berhasil dihapus.');
    }
}
