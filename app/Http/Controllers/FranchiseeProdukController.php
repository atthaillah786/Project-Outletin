<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class FranchiseeProdukController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'franchise') {
            abort(403, 'Akses hanya untuk pemilik outlet.');
        }

        $brandIds = Outlet::where('franchise_id', $user->user_id)
            ->where('status', 'approved')
            ->pluck('brand_id')
            ->unique();

        $produk = Produk::with('brand')
            ->whereIn('brand_id', $brandIds)
            ->orderBy('produk_id', 'desc')
            ->get();

        return view('dashboard.franchisee-produk', compact('produk'));
    }
}