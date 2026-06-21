@extends('layouts.dashboard')

@section('title', 'Ajukan Outlet - Outletin')

@section('content')
<section class="max-w-3xl mx-auto">
    <div class="premium-card p-6 md:p-8" data-reveal>
        <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">
            Outlet application
        </p>
        <h1 class="text-3xl font-extrabold text-ink mb-2">
            Ajukan Outlet
        </h1>

        <p class="premium-muted mb-6">
            Lengkapi data outlet untuk mengajukan kerja sama dengan brand
            <span class="font-semibold text-black">{{ $brand->brand_name }}</span>.
        </p>

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50/90 px-4 py-3 text-red-800 shadow-sm mb-6">
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
                <label for="outlet_name" class="block text-sm font-bold text-ink mb-2">
                    Nama Outlet
                </label>

                <input
                    type="text"
                    id="outlet_name"
                    name="outlet_name"
                    value="{{ old('outlet_name') }}"
                    placeholder="Contoh: Outlet Mixue Panam"
                    class="premium-input"
                    required
                >
            </div>

            <div>
                <label for="address" class="block text-sm font-bold text-ink mb-2">
                    Alamat Outlet
                </label>

                <textarea
                    id="address"
                    name="address"
                    rows="5"
                    placeholder="Masukkan alamat outlet"
                    class="premium-input"
                >{{ old('address') }}</textarea>
            </div>

            <div class="flex gap-3">
                <a
                    href="{{ route('franchisee.dashboard') }}"
                    class="premium-button-soft w-full"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="premium-button w-full"
                >
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
