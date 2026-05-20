<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class OutletPageController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('q', ''));

        $brands = Brand::query()
    ->select('brand_id', 'brand_name', 'description', 'logo_path')
    ->where('status', 'approved')
    ->when($search !== '', function ($query) use ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('brand_name', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    })
    ->orderBy('brand_name', 'asc')
    ->get();

        return view('outlet', compact('brands', 'search'));
    }
}