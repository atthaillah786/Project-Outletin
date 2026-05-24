<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukCrudController extends Controller
{
    private function authorizeFranchisor()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk pemilik brand.');
        }

        return $user;
    }

    private function ownedBrandIds($user)
    {
        return Brand::where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->pluck('brand_id');
    }

    public function index()
    {
        $user = $this->authorizeFranchisor();

        $brandIds = $this->ownedBrandIds($user);

        $produk = Produk::with('brand')
            ->whereIn('brand_id', $brandIds)
            ->orderBy('produk_id', 'desc')
            ->get();

        return view('manage.produk.index', compact('produk'));
    }

    public function create()
    {
        $user = $this->authorizeFranchisor();

        $brands = Brand::where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->orderBy('brand_name')
            ->get();

        return view('manage.produk.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $user = $this->authorizeFranchisor();

        $validated = $request->validate([
            'brand_id' => ['required', 'exists:brands,brand_id'],
            'produk_name' => ['required', 'string', 'max:100'],
            'Price' => ['required', 'numeric', 'min:0'],
        ]);

        $isOwnBrand = Brand::where('brand_id', $validated['brand_id'])
            ->where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->exists();

        if (!$isOwnBrand) {
            abort(403, 'Brand tidak valid untuk akun ini.');
        }

        Produk::create([
            'brand_id' => $validated['brand_id'],
            'produk_name' => $validated['produk_name'],
            'Price' => $validated['Price'],
        ]);

        return redirect()
            ->route('manage.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->authorizeFranchisor();

        $brandIds = $this->ownedBrandIds($user);

        $item = Produk::with('brand')
            ->whereIn('brand_id', $brandIds)
            ->where('produk_id', $id)
            ->firstOrFail();

        $brands = Brand::where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->orderBy('brand_name')
            ->get();

        return view('manage.produk.edit', compact('item', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $user = $this->authorizeFranchisor();

        $brandIds = $this->ownedBrandIds($user);

        $item = Produk::whereIn('brand_id', $brandIds)
            ->where('produk_id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'brand_id' => ['required', 'exists:brands,brand_id'],
            'produk_name' => ['required', 'string', 'max:100'],
            'Price' => ['required', 'numeric', 'min:0'],
        ]);

        $isOwnBrand = Brand::where('brand_id', $validated['brand_id'])
            ->where('franchisor_id', $user->user_id)
            ->where('status', 'approved')
            ->exists();

        if (!$isOwnBrand) {
            abort(403, 'Brand tidak valid untuk akun ini.');
        }

        $item->update([
            'brand_id' => $validated['brand_id'],
            'produk_name' => $validated['produk_name'],
            'Price' => $validated['Price'],
        ]);

        return redirect()
            ->route('manage.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = $this->authorizeFranchisor();

        $brandIds = $this->ownedBrandIds($user);

        $item = Produk::whereIn('brand_id', $brandIds)
            ->where('produk_id', $id)
            ->firstOrFail();

        $item->delete();

        return redirect()
            ->route('manage.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}