<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BrandCrudController extends Controller
{
    private function authorizeAccess(): void
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['superadmin', 'franchisor'])) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }

    private function scopedBrandQuery()
    {
        $user = Auth::user();

        $query = Brand::with('franchisor');

        if ($user->role === 'franchisor') {
            $query->where('franchisor_id', $user->user_id);
        }

        return $query;
    }

    public function index()
    {
        $this->authorizeAccess();

        $brands = $this->scopedBrandQuery()
            ->orderBy('brand_id', 'desc')
            ->get();

        return view('manage.brands.index', compact('brands'));
    }

    public function create()
    {
        $this->authorizeAccess();

        $franchisors = User::where('role', 'franchisor')
            ->orderBy('name')
            ->get();

        return view('manage.brands.create', compact('franchisors'));
    }

    public function store(Request $request)
    {
        $this->authorizeAccess();

        $user = Auth::user();

        $rules = [
            'brand_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
        ];

        if ($user->role === 'superadmin') {
            $rules['franchisor_id'] = ['required', 'exists:users,user_id'];
        }

        $validated = $request->validate($rules);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brand-logos', 'public');
        }

        Brand::create([
            'franchisor_id' => $user->role === 'franchisor'
                ? $user->user_id
                : $validated['franchisor_id'],
            'brand_name' => $validated['brand_name'],
            'description' => $validated['description'] ?? null,
            'logo_path' => $logoPath,
            'status' => $user->role === 'superadmin'
                ? ($validated['status'] ?? 'pending')
                : 'pending',
            'verified_by' => $user->role === 'superadmin' && ($validated['status'] ?? null) === 'approved'
                ? $user->user_id
                : null,
            'verified_at' => $user->role === 'superadmin' && ($validated['status'] ?? null) === 'approved'
                ? now()
                : null,
        ]);

        return redirect()
            ->route('manage.brands.index')
            ->with('success', 'Brand berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->authorizeAccess();

        $brand = $this->scopedBrandQuery()->findOrFail($id);

        $franchisors = User::where('role', 'franchisor')
            ->orderBy('name')
            ->get();

        return view('manage.brands.edit', compact('brand', 'franchisors'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAccess();

        $user = Auth::user();

        $brand = $this->scopedBrandQuery()->findOrFail($id);

        $rules = [
            'brand_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
            'rejection_note' => ['nullable', 'string'],
        ];

        if ($user->role === 'superadmin') {
            $rules['franchisor_id'] = ['required', 'exists:users,user_id'];
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('logo')) {
            if ($brand->logo_path) {
                Storage::disk('public')->delete($brand->logo_path);
            }

            $brand->logo_path = $request->file('logo')->store('brand-logos', 'public');
        }

        $brand->brand_name = $validated['brand_name'];
        $brand->description = $validated['description'] ?? null;

        if ($user->role === 'superadmin') {
            $brand->franchisor_id = $validated['franchisor_id'];
            $brand->status = $validated['status'] ?? $brand->status;
            $brand->rejection_note = $validated['rejection_note'] ?? null;

            if ($brand->status === 'approved') {
                $brand->verified_by = $user->user_id;
                $brand->verified_at = now();
                $brand->rejection_note = null;
            }
        } else {
            $brand->status = 'pending';
            $brand->verified_by = null;
            $brand->verified_at = null;
            $brand->rejection_note = null;
        }

        $brand->save();

        return redirect()
            ->route('manage.brands.index')
            ->with('success', 'Brand berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->authorizeAccess();

        $brand = $this->scopedBrandQuery()->findOrFail($id);

        if ($brand->outlets()->exists()) {
            return back()->withErrors([
                'delete' => 'Brand tidak dapat dihapus karena masih memiliki outlet.',
            ]);
        }

        if ($brand->logo_path) {
            Storage::disk('public')->delete($brand->logo_path);
        }

        $brand->delete();

        return redirect()
            ->route('manage.brands.index')
            ->with('success', 'Brand berhasil dihapus.');
    }
}