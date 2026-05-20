@extends('layouts.auth')

@section('title', 'Masuk - Outletin')

@section('content')
<main class="container mx-auto px-4 py-12 flex-1">
    <section class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

        <div class="bg-white border border-gray-200 rounded-3xl p-8 md:p-10 shadow-sm">
            <div class="mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-black mb-3">
                    Masuk ke Outletin
                </h1>

                <p class="text-gray-600 text-base md:text-lg leading-8">
                    Kelola outlet, brand, bahan baku, dan laporan bisnis Anda dari satu dashboard.
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6">
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
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                        required
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-red-700 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-800 transition"
                >
                    Masuk
                </button>
            </form>

            <p class="text-center text-gray-600 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-red-700 font-semibold hover:text-red-800">
                    Daftar sekarang
                </a>
            </p>
        </div>

        <div class="hidden lg:block relative overflow-hidden rounded-3xl bg-red-800 shadow-xl">
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('{{ asset('images/login1.jpg') }}');"
            ></div>

            <div class="absolute inset-0 bg-black/60"></div>

            <div class="relative z-10 h-full p-10 flex flex-col justify-end">
                <div class="bg-red-700/90 rounded-2xl p-6 shadow-md">
                    <h2 class="text-3xl font-bold text-white mb-4">
                        Satu sistem untuk semua kebutuhan outlet
                    </h2>

                    <p class="text-white leading-8">
                        Pantau outlet, bahan baku, transaksi, dan laporan keuangan dengan tampilan yang sederhana dan terpusat.
                    </p>
                </div>
            </div>
        </div>

    </section>
</main>
@endsection