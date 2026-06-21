@extends('layouts.auth')

@section('title', 'Tambah Brand Baru')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Tambah Brand Baru</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('brand.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-lg p-6">
            @csrf

            <div class="mb-6">
                <label for="brand_name" class="block text-gray-700 font-semibold mb-2">Nama Brand</label>
                <input type="text" id="brand_name" name="brand_name" value="{{ old('brand_name') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Masukkan nama brand"
                    required>
                @error('brand_name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea id="description" name="description" rows="5"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Masukkan deskripsi brand">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="logo" class="block text-gray-700 font-semibold mb-2">Logo Brand</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition">
                    <input type="file" id="logo" name="logo" accept="image/*" 
                        class="hidden"
                        onchange="document.getElementById('logo-preview').innerHTML = this.files[0]?.name || ''; document.getElementById('logo-preview-img').src = URL.createObjectURL(this.files[0]); document.getElementById('logo-preview-img').style.display = 'block';">
                    <label for="logo" class="cursor-pointer">
                        <div>
                            <p class="text-gray-600 mb-2">Klik untuk mengunggah atau drag & drop</p>
                            <p class="text-gray-400 text-sm">PNG, JPG, GIF (Max. 5MB)</p>
                        </div>
                        <img id="logo-preview-img" style="display: none; max-width: 200px; margin: 10px auto; border-radius: 8px;">
                        <span id="logo-preview"></span>
                    </label>
                </div>
                @error('logo')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-3">
                <a href="{{ route('brand.index') }}" class="flex-1 text-center bg-gray-500 text-white px-4 py-3 rounded-lg font-semibold hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                    Simpan Brand
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
