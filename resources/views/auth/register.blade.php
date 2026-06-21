@extends('layouts.auth')

@section('title', 'Daftar - Outletin')

@section('content')
<main class="mx-auto w-full max-w-7xl px-4 py-12 md:py-16 flex-1">
    <section class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

        <div class="hidden lg:block relative overflow-hidden rounded-3xl bg-oxblood shadow-[0_24px_80px_rgb(85,11,20,0.22)]" data-reveal>
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('{{ asset('images/login1.jpg') }}');"
            ></div>

            <div class="absolute inset-0 bg-gradient-to-br from-ink/70 via-oxblood/56 to-taupe/40"></div>

            <div class="relative z-10 h-full p-10 flex flex-col justify-end">
                <div class="rounded-3xl border border-white/15 bg-white/12 p-6 shadow-md backdrop-blur-xl">
                    <h2 class="text-3xl font-extrabold text-white mb-4">
                        Mulai bangun jaringan bisnis Anda
                    </h2>

                    <p class="text-ivory/85 leading-8">
                        Daftar sebagai pemilik brand atau mitra franchise untuk mengelola peluang dan operasional bisnis dengan lebih rapi.
                    </p>
                </div>
            </div>
        </div>

        <div class="premium-card p-8 md:p-10" data-reveal>
            <div class="mb-8">
                <p class="mb-3 text-sm font-extrabold uppercase tracking-normal text-oxblood">
                    Premium onboarding
                </p>

                <h1 class="premium-section-title mb-3">
                    Buat Akun Outletin
                </h1>

                <p class="premium-muted text-base md:text-lg">
                    Daftar untuk mulai menggunakan sistem manajemen franchise Outletin.
                </p>
            </div>

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50/90 px-4 py-3 text-red-800 shadow-sm mb-6">
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
                    <label for="name" class="block text-sm font-bold text-ink mb-2">
                        Nama
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama"
                        class="premium-input"
                        required
                    >
                </div>

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
                    <label for="role" class="block text-sm font-bold text-ink mb-2">
                        Daftar Sebagai
                    </label>

                    <select
                        id="role"
                        name="role"
                        class="premium-input"
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
                    <label for="password" class="block text-sm font-bold text-ink mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 6 karakter"
                        class="premium-input"
                        required
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-ink mb-2">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        class="premium-input"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="premium-button w-full"
                >
                    Daftar
                </button>
            </form>

            <p class="text-center text-taupe mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-oxblood font-extrabold hover:text-taupe transition">
                    Masuk di sini
                </a>
            </p>
        </div>

    </section>
</main>
@endsection
