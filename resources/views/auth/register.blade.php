@extends('layouts.auth')

@section('title', 'Daftar - Outletin')

@section('content')
<main class="container mx-auto px-4 py-12 flex-1">
    <section class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

        <div class="hidden lg:block relative overflow-hidden rounded-3xl bg-red-800 shadow-xl">
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('{{ asset('images/login1.jpg') }}');"
            ></div>

            <div class="absolute inset-0 bg-black/60"></div>

            <div class="relative z-10 h-full p-10 flex flex-col justify-end">
                <div class="bg-red-700/90 rounded-2xl p-6 shadow-md">
                    <h2 class="text-3xl font-bold text-white mb-4">
                        Mulai bangun jaringan bisnis Anda
                    </h2>

                    <p class="text-white leading-8">
                        Daftar sebagai pemilik brand atau mitra franchise untuk mengelola peluang dan operasional bisnis dengan lebih rapi.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-3xl p-8 md:p-10 shadow-sm">
            <div class="mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-black mb-3">
                    Buat Akun Outletin
                </h1>

                <p class="text-gray-600 text-base md:text-lg leading-8">
                    Daftar untuk mulai menggunakan sistem manajemen franchise Outletin.
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6">
                    <p class="font-semibold mb-1">Registrasi gagal</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.process') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                        required
                    >
                </div>

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
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                        Daftar Sebagai
                    </label>

                    <select
                        id="role"
                        name="role"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                        required
                    >
                        <option value="franchise" {{ old('role') === 'franchise' ? 'selected' : '' }}>
                            Mitra Franchise
                        </option>

                        <option value="franchisor" {{ old('role') === 'franchisor' ? 'selected' : '' }}>
                            Pemilik Brand
                        </option>
                    </select>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 6 karakter"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                        required
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-red-700 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-800 transition"
                >
                    Daftar
                </button>
            </form>

            <p class="text-center text-gray-600 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-red-700 font-semibold hover:text-red-800">
                    Masuk di sini
                </a>
            </p>
        </div>

    </section>
</main>
@endsection