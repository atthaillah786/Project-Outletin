<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Produk;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FranchisorProdukController extends Controller
{
    public function index()
    {
        $brands = $this->ownedBrands();
        $products = Produk::with('brand')
            ->whereIn('brand_id', $brands->pluck('brand_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('produk.index', compact('brands', 'products'));
    }

    public function create(Request $request)
    {
        $brands = $this->ownedBrands();
        $selectedBrandId = (int) $request->query('brand_id');

        return view('produk.create', compact('brands', 'selectedBrandId'));
    }

    public function store(Request $request)
    {
        $brands = $this->ownedBrands();

        $validated = $request->validate([
            'brand_id' => ['required', 'integer', Rule::in($brands->pluck('brand_id')->all())],
            'produk_name' => ['required', 'string', 'max:100'],
            'Price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ]);

        Produk::create($validated);

        return redirect()
            ->route('franchisor.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        $this->ensureOwnsProduct($produk);

        $brands = $this->ownedBrands();

        return view('produk.edit', compact('brands', 'produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $this->ensureOwnsProduct($produk);

        $brands = $this->ownedBrands();

        $validated = $request->validate([
            'brand_id' => ['required', 'integer', Rule::in($brands->pluck('brand_id')->all())],
            'produk_name' => ['required', 'string', 'max:100'],
            'Price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ]);

        $produk->update($validated);

        return redirect()
            ->route('franchisor.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $this->ensureOwnsProduct($produk);

        try {
            $produk->delete();
        } catch (QueryException $exception) {
            return redirect()
                ->route('franchisor.produk.index')
                ->with('error', 'Produk tidak bisa dihapus karena sudah dipakai di stok atau permintaan material.');
        }

        return redirect()
            ->route('franchisor.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function ownedBrands()
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'franchisor') {
            abort(403, 'Akses hanya untuk pemilik brand.');
        }

        return Brand::where('franchisor_id', $user->user_id)
            ->orderBy('brand_name')
            ->get();
    }

    private function ensureOwnsProduct(Produk $produk): void
    {
        $brandIds = $this->ownedBrands()->pluck('brand_id');

        if (! $brandIds->contains($produk->brand_id)) {
            abort(403, 'Produk ini bukan milik brand Anda.');
        }
    }
}
