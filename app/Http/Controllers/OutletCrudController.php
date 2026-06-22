<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OutletCrudController extends Controller
{
    private function authorizeAccess(): void
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['superadmin', 'franchisor', 'franchise'])) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }

    private function scopedOutletQuery()
    {
        $user = Auth::user();

        $query = Outlet::with(['brand', 'franchise']);

        if ($user->role === 'franchisor') {
            $brandIds = Brand::where('franchisor_id', $user->user_id)
                ->pluck('brand_id');

            $query->whereIn('brand_id', $brandIds);
        }

        if ($user->role === 'franchise') {
            $query->where('franchise_id', $user->user_id);
        }

        return $query;
    }

    private function selectableBrands()
    {
        $user = Auth::user();

        $query = Brand::where('status', 'approved')->orderBy('brand_name');

        if ($user->role === 'franchisor') {
            $query->where('franchisor_id', $user->user_id);
        }

        return $query->get();
    }

    public function index()
    {
        $this->authorizeAccess();

        $outlets = $this->scopedOutletQuery()
            ->orderBy('outlet_id', 'desc')
            ->get();

        return view('manage.outlets.index', compact('outlets'));
    }

    public function create()
    {
        $this->authorizeAccess();

        $brands = $this->selectableBrands();

        $franchises = User::where('role', 'franchise')
            ->orderBy('name')
            ->get();

        return view('manage.outlets.create', compact('brands', 'franchises'));
    }

    public function store(Request $request)
    {
        $this->authorizeAccess();

        $user = Auth::user();

        $rules = [
            'brand_id' => ['required', 'exists:brands,brand_id'],
            'outlet_name' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
        ];

        if ($user->role !== 'franchise') {
            $rules['franchise_id'] = ['required', 'exists:users,user_id'];
        }

        $validated = $request->validate($rules);

        if ($user->role === 'franchisor') {
            $isOwnBrand = Brand::where('brand_id', $validated['brand_id'])
                ->where('franchisor_id', $user->user_id)
                ->exists();

            if (!$isOwnBrand) {
                abort(403, 'Brand tidak valid untuk akun ini.');
            }
        }

        Outlet::create([
            'franchise_id' => $user->role === 'franchise'
                ? $user->user_id
                : $validated['franchise_id'],
            'brand_id' => $validated['brand_id'],
            'outlet_name' => $validated['outlet_name'],
            'address' => $validated['address'] ?? null,
            'status' => $user->role === 'franchise'
                ? 'pending'
                : ($validated['status'] ?? 'pending'),
        ]);

        return redirect()
            ->route('manage.outlets.index')
            ->with('success', 'Outlet berhasil ditambahkan.');
    }

    public function show($id)
    {
        $this->authorizeAccess();

        $outlet = $this->scopedOutletQuery()->findOrFail($id);

        return view('manage.outlets.show', compact('outlet'));
    }

    public function edit($id)
    {
        $this->authorizeAccess();

        $outlet = $this->scopedOutletQuery()->findOrFail($id);

        $brands = $this->selectableBrands();

        $franchises = User::where('role', 'franchise')
            ->orderBy('name')
            ->get();

        return view('manage.outlets.edit', compact('outlet', 'brands', 'franchises'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAccess();

        $user = Auth::user();

        $outlet = $this->scopedOutletQuery()->findOrFail($id);

        $rules = [
            'brand_id' => ['required', 'exists:brands,brand_id'],
            'outlet_name' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
        ];

        if ($user->role !== 'franchise') {
            $rules['franchise_id'] = ['required', 'exists:users,user_id'];
        }

        $validated = $request->validate($rules);

        if ($user->role === 'franchisor') {
            $isOwnBrand = Brand::where('brand_id', $validated['brand_id'])
                ->where('franchisor_id', $user->user_id)
                ->exists();

            if (!$isOwnBrand) {
                abort(403, 'Brand tidak valid untuk akun ini.');
            }
        }

        $outlet->brand_id = $validated['brand_id'];
        $outlet->outlet_name = $validated['outlet_name'];
        $outlet->address = $validated['address'] ?? null;

        if ($user->role !== 'franchise') {
            $outlet->franchise_id = $validated['franchise_id'];
            $outlet->status = $validated['status'] ?? $outlet->status;
        } else {
            $outlet->status = 'pending';
        }

        $outlet->save();

        return redirect()
            ->route('manage.outlets.index')
            ->with('success', 'Outlet berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $this->authorizeAccess();

        $outlet = $this->scopedOutletQuery()->findOrFail($id);
        $outlet->deleteWithDependencies();

        return redirect()
            ->route('manage.outlets.index')
            ->with('success', 'Outlet berhasil dihapus beserta data terkait.');
    }
}