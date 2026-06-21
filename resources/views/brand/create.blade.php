@extends('layouts.dashboard')

@section('title', 'Tambah Brand Baru - Outletin')

@section('content')
<div class="container mx-auto px-4 py-8" data-reveal>
    <div class="max-w-2xl mx-auto">
        <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">Brand setup</p>
        <h1 class="text-3xl font-extrabold text-ink mb-6">Tambah Brand Baru</h1>

        @if ($errors->any())
            <div class="bg-oxblood/10 border border-oxblood/30 text-oxblood px-4 py-3 rounded-xl mb-6 text-sm font-medium">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- GUNANYA: Mengarahkan action ke rute resource baru 'manage.brands.store' --}}
        <form action="{{ route('manage.brands.store') }}" method="POST" enctype="multipart/form-data" class="premium-card p-6 md:p-8 space-y-6">
            @csrf

            <div>
                <label for="brand_name" class="block text-sm font-bold text-ink mb-2">Nama Brand</label>
                <input type="text" id="brand_name" name="brand_name" value="{{ old('brand_name') }}" 
                    class="premium-input"
                    placeholder="Masukkan nama brand"
                    required>
                @error('brand_name')
                    <span class="text-oxblood text-xs mt-1 block font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-ink mb-2">Deskripsi</label>
                <textarea id="description" name="description" rows="5"
                    class="premium-input"
                    placeholder="Masukkan deskripsi brand">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-oxblood text-xs mt-1 block font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-ink mb-2">Logo Brand</label>
                <div class="border-2 border-dashed border-linen hover:border-taupe rounded-3xl p-6 text-center transition bg-linen/5">
                    
                    {{-- GUNANYA name="logo_path": Harus diubah dari 'logo' menjadi 'logo_path' agar pas dengan Controller & Database --}}
                    <input type="file" id="logo" name="logo_path" accept="image/png, image/jpeg, image/jpg, image/webp" 
                        class="hidden"
                        onchange="document.getElementById('logo-preview').innerHTML = this.files[0]?.name || ''; document.getElementById('logo-preview-img').src = URL.createObjectURL(this.files[0]); document.getElementById('logo-preview-img').style.display = 'block';">
                    
                    <label for="logo" class="cursor-pointer block">
                        <div class="space-y-1">
                            <p class="text-ink font-bold text-sm">Klik untuk mengunggah atau drag & drop</p>
                            <p class="text-taupe text-xs">PNG, JPG, JPEG, WEBP (Max. 2MB)</p>
                        </div>
                        
                        {{-- Wadah Preview Gambar --}}
                        <img id="logo-preview-img" style="display: none; max-width: 140px; margin: 16px auto; border-radius: 1rem;" class="shadow-md border border-linen">
                        <span id="logo-preview" class="text-xs font-mono text-taupe mt-2 block break-all"></span>
                    </label>
                </div>
                @error('logo_path')
                    <span class="text-oxblood text-xs mt-1 block font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('manage.brands.index') }}" class="premium-button-soft flex-1 text-center py-3">
                    Batal
                </a>
                <button type="submit" class="premium-button flex-1 py-3">
                    Simpan Brand
                </button>
            </div>
        </form>
    </div>
</div>
@endsection