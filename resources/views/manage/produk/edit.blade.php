@extends('layouts.dashboard')

@section('title', 'Edit Produk - Outletin')

@section('content')
<section class="max-w-3xl mx-auto bg-white border rounded-2xl p-6 shadow-sm">
    <h1 class="text-3xl font-bold mb-6">Edit Produk</h1>

    <form method="POST" action="{{ route('manage.produk.update', $item->produk_id) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold mb-2">Brand</label>

            <select name="brand_id" class="w-full border rounded-xl px-4 py-3" required>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->brand_id }}" @selected(old('brand_id', $item->brand_id) == $brand->brand_id)>
                        {{ $brand->brand_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Nama Produk</label>

            <input
                type="text"
                name="produk_name"
                value="{{ old('produk_name', $item->produk_name) }}"
                class="w-full border rounded-xl px-4 py-3"
                required
            >
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Harga</label>

            <input
                type="number"
                name="Price"
                value="{{ old('Price', $item->Price) }}"
                min="0"
                step="0.01"
                class="w-full border rounded-xl px-4 py-3"
                required
            >
        </div>

        <div class="flex gap-3">
            <a href="{{ route('manage.produk.index') }}" class="w-full text-center bg-gray-200 py-3 rounded-xl font-semibold">
                Batal
            </a>

            <button class="w-full bg-red-700 text-white py-3 rounded-xl font-semibold">
                Update
            </button>
        </div>
    </form>
</section>
@endsection