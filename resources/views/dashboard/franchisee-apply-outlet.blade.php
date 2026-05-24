@extends('layouts.dashboard')

@section('title', 'Ajukan Outlet - Outletin')

@section('content')
<section class="max-w-3xl mx-auto">
    <div class="bg-white border rounded-2xl p-6 shadow-sm">
        <h1 class="text-3xl font-bold text-black mb-2">
            Ajukan Outlet
        </h1>

        <p class="text-gray-600 mb-6">
            Lengkapi data outlet untuk mengajukan kerja sama dengan brand
            <span class="font-semibold text-black">{{ $brand->brand_name }}</span>.
        </p>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('franchisee.outlets.store', $brand->brand_id) }}" class="space-y-5">
            @csrf

            <div>
                <label for="outlet_name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Outlet
                </label>

                <input
                    type="text"
                    id="outlet_name"
                    name="outlet_name"
                    value="{{ old('outlet_name') }}"
                    placeholder="Contoh: Outlet Mixue Panam"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-600"
                    required
                >
            </div>

            <div>
                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                    Alamat Outlet
                </label>

                <textarea
                    id="address"
                    name="address"
                    rows="5"
                    placeholder="Masukkan alamat outlet"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-600"
                >{{ old('address') }}</textarea>
            </div>

            <div class="flex gap-3">
                <a
                    href="{{ route('franchisee.dashboard') }}"
                    class="w-full text-center bg-gray-200 text-gray-800 py-3 rounded-xl font-semibold hover:bg-gray-300 transition"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="w-full bg-red-700 text-white py-3 rounded-xl font-semibold hover:bg-red-800 transition"
                >
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</section>
@endsection