@extends('layouts.auth')

@section('title', 'Daftar Brand')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-4">Brand Saya</h1>
        <a href="{{ route('brand.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition">
            Tambah Brand Baru
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($brands->isEmpty())
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <p class="text-gray-600 mb-4">Anda belum memiliki brand.</p>
            <a href="{{ route('brand.create') }}" class="text-red-600 font-semibold hover:underline">
                Buat brand pertama Anda
            </a>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($brands as $brand)
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
                    @if ($brand->logo_url)
                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->brand_name }}" class="w-full h-40 object-cover rounded mb-4">
                    @else
                        <div class="w-full h-40 bg-gray-200 rounded mb-4 flex items-center justify-center">
                            <span class="text-gray-400">No Logo</span>
                        </div>
                    @endif
                    
                    <h3 class="text-lg font-bold mb-2">{{ $brand->brand_name }}</h3>
                    
                    <p class="text-gray-600 text-sm mb-3">
                        {{ Str::limit($brand->description, 100) }}
                    </p>

                    <div class="mb-4">
                        <span class="inline-block px-3 py-1 text-sm rounded-full 
                            @if ($brand->status === 'approved')
                                bg-green-100 text-green-800
                            @elseif ($brand->status === 'rejected')
                                bg-red-100 text-red-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif
                        ">
                            {{ ucfirst($brand->status) }}
                        </span>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('brand.edit', $brand) }}" class="flex-1 text-center bg-blue-600 text-white px-3 py-2 rounded font-semibold text-sm hover:bg-blue-700 transition">
                            Edit
                        </a>
                        <form action="{{ route('brand.destroy', $brand) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 text-white px-3 py-2 rounded font-semibold text-sm hover:bg-red-700 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
