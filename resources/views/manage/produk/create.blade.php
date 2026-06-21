@extends('layouts.dashboard')

@section('title', 'Tambah Produk - Outletin')

@section('content')
<section class="max-w-3xl mx-auto premium-card p-6 md:p-8" data-reveal>
    <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">Product setup</p>
    <h1 class="text-3xl font-extrabold text-ink mb-6">Tambah Produk</h1>

    <form method="POST" action="{{ route('manage.produk.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Brand</label>

            <select name="brand_id" class="premium-input" required>
                <option value="">Pilih brand</option>

                @foreach ($brands as $brand)
                    <option value="{{ $brand->brand_id }}" @selected(old('brand_id') == $brand->brand_id)>
                        {{ $brand->brand_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Nama Produk</label>

            <input
                type="text"
                name="produk_name"
                value="{{ old('produk_name') }}"
                class="premium-input"
                required
            >
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Harga</label>

            <input
                type="number"
                name="Price"
                value="{{ old('Price', 0) }}"
                min="0"
                step="0.01"
                class="premium-input"
                required
            >
        </div>

        <div class="flex gap-3">
            <a href="{{ route('manage.produk.index') }}" class="premium-button-soft w-full">
                Batal
            </a>

            <button class="premium-button w-full">
                Simpan
            </button>
        </div>
    </form>
</section>
@endsection
