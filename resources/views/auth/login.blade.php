@extends('layouts.auth')

@section('title', 'Masuk - Outletin')

@section('content')
<main class="mx-auto w-full max-w-7xl px-4 py-12 md:py-16 flex-1">
    <section class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

        <div class="premium-card p-8 md:p-10" data-reveal>
            <div class="mb-8">
                <p class="mb-3 text-sm font-extrabold uppercase tracking-normal text-oxblood">
                    Secure workspace
                </p>

                <h1 class="premium-section-title mb-3">
                    Masuk ke Outletin
                </h1>

                <p class="premium-muted text-base md:text-lg">
                    Kelola outlet, brand, bahan baku, dan laporan bisnis Anda dari satu dashboard.
                </p>
            </div>

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50/90 px-4 py-3 text-red-800 shadow-sm mb-6">
                    <p class="font-semibold mb-1">Login gagal</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-ink mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        class="premium-input"
                        required
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-ink mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        class="premium-input"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="premium-button w-full"
                >
                    Masuk
                </button>
            </form>

            <p class="text-center text-taupe mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-oxblood font-extrabold hover:text-taupe transition">
                    Daftar sekarang
                </a>
            </p>
        </div>

        <div class="hidden lg:block relative overflow-hidden rounded-3xl bg-oxblood shadow-[0_24px_80px_rgb(85,11,20,0.22)]" data-reveal>
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('{{ asset('images/login1.jpg') }}');"
            ></div>

            <div class="absolute inset-0 bg-gradient-to-br from-ink/70 via-oxblood/56 to-taupe/40"></div>

            <div class="relative z-10 h-full p-10 flex flex-col justify-end">
                <div class="rounded-3xl border border-white/15 bg-white/12 p-6 shadow-md backdrop-blur-xl">
                    <h2 class="text-3xl font-extrabold text-white mb-4">
                        Satu sistem untuk semua kebutuhan outlet
                    </h2>

                    <p class="text-ivory/85 leading-8">
                        Pantau outlet, bahan baku, transaksi, dan laporan keuangan dengan tampilan yang sederhana dan terpusat.
                    </p>
                </div>
            </div>
        </div>

    </section>
</main>
@endsection
