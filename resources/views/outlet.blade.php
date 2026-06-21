@extends('layouts.auth')

@section('title', 'Outlet - Outletin')

@section('content')
@php
    // GUNANYA: Menyelaraskan pencarian agar menangkap parameter "q" langsung dari request URL jika tersedia
    $searchTerm = request('q', $search ?? '');
    $brandList = $brands ?? collect();
@endphp

<section class="mx-auto mb-12 max-w-4xl text-center" data-reveal>
    <span class="mb-4 inline-flex rounded-full border border-linen/70 bg-white/70 px-4 py-2 text-xs font-extrabold uppercase tracking-normal text-oxblood shadow-sm">
        Curated franchise directory
    </span>

    <h1 class="premium-section-title">
        Temukan brand outlet yang siap Anda kembangkan.
    </h1>

    <p class="premium-muted mx-auto mt-5 max-w-2xl">
        Cari brand yang tersedia dan lihat gambaran singkatnya dengan interface yang lebih lapang, fokus, dan mudah dipindai.
    </p>
</section>

<section class="mx-auto mb-10 max-w-4xl" data-reveal>
    <form method="GET" action="{{ route('outlet') }}" class="premium-card flex flex-col gap-3 p-3 sm:flex-row">
        <input
            type="text"
            name="q"
            value="{{ $searchTerm }}"
            placeholder="Cari brand berdasarkan nama atau deskripsi..."
            class="premium-input"
        >

        <div class="flex items-center gap-3">
            <button type="submit" class="premium-button shrink-0">
                Cari
            </button>

            @if ($searchTerm !== '')
                <a href="{{ route('outlet') }}" class="premium-button-soft shrink-0">
                    Reset
                </a>
            @endif
        </div>
    </form>
</section>

@if ($searchTerm !== '')
    <p class="mb-8 text-center text-taupe" data-reveal>
        Hasil pencarian untuk:
        <span class="font-extrabold text-ink">"{{ $searchTerm }}"</span>
    </p>
@endif

<section>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($brandList as $brand)
            @php
                $brandName = $brand->brand_name;
                $description = $brand->description;
                $initial = strtoupper(substr($brandName, 0, 1));
            @endphp

            <article class="premium-card premium-card-hover flex h-full flex-col p-7" data-reveal>
                {{-- SINKRONISASI GAMBAR: Membaca data logo_path hasil upload controller baru --}}
                @if (!empty($brand->logo_path))
                    <img
                        src="{{ asset('storage/' . $brand->logo_path) }}"
                        alt="{{ $brandName }}"
                        class="mb-6 h-20 w-20 rounded-3xl border border-linen/70 object-cover shadow-sm"
                    >
                @else
                    {{-- Inisial huruf sebagai backup jika data gambar kosong --}}
                    <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-oxblood to-taupe shadow-[0_16px_36px_rgb(85,11,20,0.22)]">
                        <span class="text-3xl font-extrabold text-white">
                            {{ $initial }}
                        </span>
                    </div>
                @endif

                <h3 class="text-xl font-extrabold text-ink">
                    {{ $brandName }}
                </h3>

                <p class="premium-muted mt-3 min-h-[112px] flex-1">
                    {{ $description ?? 'Belum ada deskripsi.' }}
                </p>

                <a href="#" class="premium-button mt-6 w-full text-center block">
                    Lihat Brand
                </a>
            </article>

        @empty
            <div class="col-span-full" data-reveal>
                <div class="premium-card p-10 text-center">
                    <h3 class="text-2xl font-extrabold text-ink">
                        Brand tidak ditemukan
                    </h3>

                    <p class="premium-muted mt-3">
                        Coba gunakan kata kunci lain untuk pencarian Anda.
                    </p>
                </div>
            </div>
        @endforelse
    </div>
</section>

@endsection