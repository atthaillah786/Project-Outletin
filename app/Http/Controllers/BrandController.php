<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * GUNANYA: Menampilkan daftar brand milik user yang login ke dalam view premium.
     */
    public function index()
    {
        $brands = auth()->user()->brands;
        
        // Disesuaikan dengan folder layout baru Anda
        return view('manage.brands.index', compact('brands'));
    }

    /**
     * GUNANYA: Menampilkan form pembuatan brand baru.
     */
    public function create()
    {
        return view('manage.brands.create');
    }

    /**
     * GUNANYA: Memvalidasi inputan form, memproses unggahan file gambar, 
     * lalu mencatatkan baris brand baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi disesuaikan dengan key 'logo_path' dari form Blade
        $validated = $request->validate([
            'brand_name' => 'required|string|min:5|unique:brands',
            'description' => 'nullable|string',
            'logo_path'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Batasi 2MB agar ramah storage
        ]);

        $user = auth()->user();

        // 2. SINKRONISASI: Proses upload file menggunakan key 'logo_path'
        if ($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            // Membuat nama file unik: timestamp_namaasli.png
            $filename = time() . '_' . $file->getClientOriginalName();
            // Menyimpan fisik berkas ke: storage/app/public/logos/
            $path = $file->storeAs('logos', $filename, 'public');
            
            // Masukkan path teks ke array untuk disimpan di DB
            $validated['logo_path'] = $path;
        }

        // Ambil primary key user id yang dinamis (user_id)
        $validated['franchisor_id'] = $user->user_id;
        $validated['status'] = 'pending';

        Brand::create($validated);

        // Redirect diarahkan ke rute terproteksi yang baru
        return redirect()->route('manage.brands.index')
            ->with('success', 'Brand berhasil dibuat. Menunggu verifikasi admin.');
    }

    /**
     * GUNANYA: Menampilkan detail brand tertentu (terproteksi policy).
     */
    public function show(Brand $brand)
    {
        $this->authorize('view', $brand);
        
        return view('manage.brands.show', compact('brand'));
    }

    /**
     * GUNANYA: Menampilkan halaman edit profil brand.
     */
    public function edit(Brand $brand)
    {
        $this->authorize('update', $brand);
        
        return view('manage.brands.edit', compact('brand'));
    }

    /**
     * GUNANYA: Memperbarui data teks dan mengganti file logo lama dengan berkas baru jika diunggah.
     */
    public function update(Request $request, Brand $brand)
    {
        $this->authorize('update', $brand);

        // Validasi disesuaikan menggunakan primary key Anda 'brand_id'
        $validated = $request->validate([
            'brand_name' => 'required|string|min:5|unique:brands,brand_name,' . $brand->brand_id . ',brand_id',
            'description' => 'nullable|string',
            'logo_path'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // 3. SINKRONISASI: Penggantian berkas logo lama di method update
        if ($request->hasFile('logo_path')) {
            // Hapus fisik gambar lama dari disk public jika sebelumnya sudah ada logo
            if ($brand->logo_path) {
                Storage::disk('public')->delete($brand->logo_path);
            }

            $file = $request->file('logo_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('logos', $filename, 'public');
            
            $validated['logo_path'] = $path;
        }

        $validated['status'] = 'pending'; // Setel kembali ke pending agar diverifikasi ulang jika nama/logo berubah
        $validated['verified_by'] = null;
        $validated['verified_at'] = null;
        $validated['rejection_note'] = null;

        $brand->update($validated);

        return redirect()->route('manage.brands.index')
            ->with('success', 'Brand berhasil diperbarui.');
    }

    /**
     * GUNANYA: Menghapus data brand beserta berkas gambar logonya dari folder storage.
     */
    public function destroy(Brand $brand)
    {
        $this->authorize('delete', $brand);

        // Cegah eror relasi jika brand masih mengikat menu produk
        if ($brand->produk()->exists()) {
            return redirect()->route('manage.brands.index')
                ->with('error', 'Brand tidak bisa dihapus karena masih memiliki produk. Hapus produk terlebih dahulu.');
        }

        // Hapus file logo dari storage agar tidak menjadi sampah server
        if ($brand->logo_path) {
            Storage::disk('public')->delete($brand->logo_path);
        }

        $brand->delete();

        return redirect()->route('manage.brands.index')
            ->with('success', 'Brand berhasil dihapus.');
    }
}