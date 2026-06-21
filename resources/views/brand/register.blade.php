@extends('layouts.auth')

@section('title', 'Daftar Brand - Outletin')

@section('content')
<main class="container mx-auto px-4 py-12">
    <section class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

        <div class="bg-white border border-gray-200 rounded-3xl p-8 md:p-10 shadow-sm">
            <h1 class="text-4xl md:text-5xl font-bold text-black mb-3">
                Daftarkan Brand Anda
            </h1>

            <p class="text-gray-600 text-base md:text-lg leading-8 mb-8">
                Brand akan diperiksa oleh superadmin sebelum Anda dapat mengakses dashboard pemilik brand.
            </p>

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- WAJIB: Tambahkan enctype="multipart/form-data" agar file bisa terkirim --}}
            <form method="POST" action="{{ route('brand.registration.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="brand_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Brand
                    </label>
                    <input
                        type="text"
                        id="brand_name"
                        name="brand_name"
                        value="{{ old('brand_name') }}"
                        placeholder="Contoh: Kopi Nusantara"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                        required
                    >
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi Brand
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Jelaskan singkat tentang brand Anda"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                    >{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">
                        Logo Brand
                    </label>
                    {{-- Nama field harus 'logo' agar sinkron dengan controller --}}
                    <input
                        type="file"
                        id="logo"
                        name="logo"
                        accept="image/png,image/jpeg,image/jpg,image/webp"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                        required
                    >
                    <p class="text-sm text-gray-500 mt-2">
                        Format: JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-red-700 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-800 transition"
                >
                    Ajukan Verifikasi Brand
                </button>
            </form>
        </div>

        <div class="bg-red-800 rounded-3xl p-8 md:p-10 text-white shadow-xl flex flex-col justify-end">
            <h2 class="text-3xl font-bold mb-4">
                Verifikasi sebelum dashboard aktif
            </h2>
            <p class="text-red-100 leading-8">
                Setelah brand Anda dikirim, superadmin akan memeriksa data dan logo. Dashboard pemilik brand baru terbuka setelah status brand menjadi approved.
            </p>
        </div>

    </section>
</main>
@endsection