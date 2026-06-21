@if ($errors->any())
    <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($brands->isEmpty())
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-4 rounded-xl mb-6">
        Belum ada brand. Buat brand terlebih dahulu sebelum menambahkan produk.
    </div>
@endif

<form action="{{ $action }}" method="POST" class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="mb-5">
        <label for="brand_id" class="block text-gray-700 font-semibold mb-2">Brand</label>
        <select
            id="brand_id"
            name="brand_id"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
            required
            @disabled($brands->isEmpty())
        >
            <option value="">Pilih brand</option>
            @foreach ($brands as $brand)
                <option
                    value="{{ $brand->brand_id }}"
                    @selected((int) old('brand_id', $selectedBrandId ?? $produk->brand_id ?? '') === $brand->brand_id)
                >
                    {{ $brand->brand_name }}
                </option>
            @endforeach
        </select>
        @error('brand_id')
            <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-5">
        <label for="produk_name" class="block text-gray-700 font-semibold mb-2">Nama Produk</label>
        <input
            type="text"
            id="produk_name"
            name="produk_name"
            value="{{ old('produk_name', $produk->produk_name ?? '') }}"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
            placeholder="Contoh: Paket Ayam Crispy"
            required
        >
        @error('produk_name')
            <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-6">
        <label for="Price" class="block text-gray-700 font-semibold mb-2">Harga</label>
        <div class="flex overflow-hidden rounded-lg border border-gray-300 focus-within:ring-2 focus-within:ring-red-500">
            <span class="bg-gray-100 px-4 py-3 text-gray-600 font-semibold">Rp</span>
            <input
                type="number"
                id="Price"
                name="Price"
                value="{{ old('Price', isset($produk) ? number_format((float) $produk->Price, 0, '.', '') : '') }}"
                min="0"
                step="100"
                class="w-full px-4 py-3 focus:outline-none"
                placeholder="25000"
                required
            >
        </div>
        @error('Price')
            <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="flex flex-col sm:flex-row gap-3">
        <a
            href="{{ route('franchisor.produk.index') }}"
            class="flex-1 text-center bg-gray-100 text-gray-800 px-4 py-3 rounded-lg font-semibold hover:bg-gray-200 transition"
        >
            Batal
        </a>
        <button
            type="submit"
            class="flex-1 bg-red-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-800 transition disabled:cursor-not-allowed disabled:opacity-60"
            @disabled($brands->isEmpty())
        >
            {{ $submitLabel }}
        </button>
    </div>
</form>
