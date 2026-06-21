@extends('layouts.auth')

@section('title', $brand->brand_name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">{{ $brand->brand_name }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('brand.edit', $brand) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Edit
                </a>
                <form action="{{ route('brand.destroy', $brand) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="md:col-span-2">
                @if ($brand->logo_url)
                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->brand_name }}" class="w-full h-64 object-cover rounded-lg mb-6 border border-gray-200">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg mb-6 flex items-center justify-center border border-gray-200">
                        <span class="text-gray-400">No Logo</span>
                    </div>
                @endif

                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Deskripsi</h2>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $brand->description ?: 'Tidak ada deskripsi' }}
                    </p>
                </div>
            </div>

            <div>
                <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-4">
                    <h3 class="font-bold text-lg mb-4">Informasi Brand</h3>

                    <div class="mb-4">
                        <p class="text-gray-600 text-sm">Status</p>
                        <p class="flex items-center gap-2 mt-1">
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
                        </p>
                    </div>

                    <hr class="my-4">

                    <div class="mb-4">
                        <p class="text-gray-600 text-sm">Dibuat</p>
                        <p class="text-gray-800 font-semibold">{{ $brand->created_at->format('d M Y H:i') }}</p>
                    </div>

                    @if ($brand->status === 'approved')
                        <div class="mb-4">
                            <p class="text-gray-600 text-sm">Disetujui Tanggal</p>
                            <p class="text-gray-800 font-semibold">{{ $brand->verified_at?->format('d M Y H:i') ?: '-' }}</p>
                        </div>
                    @endif

                    @if ($brand->status === 'rejected' && $brand->rejection_note)
                        <hr class="my-4">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 font-semibold mb-2">Alasan Penolakan:</p>
                            <p class="text-red-700 text-sm">{{ $brand->rejection_note }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('brand.index') }}" class="text-red-600 hover:underline font-semibold">
                ← Kembali ke Daftar Brand
            </a>
        </div>
    </div>
</div>
@endsection
