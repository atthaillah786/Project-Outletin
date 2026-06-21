<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Outletin')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="premium-shell min-h-screen flex flex-col">

<nav class="premium-nav mt-3" data-shrink-navbar>
    <div class="relative flex items-center justify-between px-4 py-3 md:px-6">

        <a href="{{ route('home') }}" class="text-xl font-extrabold tracking-normal text-oxblood">
            Outletin
        </a>

        <ul class="absolute left-1/2 -translate-x-1/2 hidden rounded-full border border-linen/60 bg-white/55 px-2 py-1 text-sm font-semibold text-taupe shadow-sm md:flex">
            <li>
                <a href="{{ route('home') }}" class="block rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                    Home
                </a>
            </li>

            <li>
                <a href="{{ route('outlet') }}" class="block rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                    Outlet
                </a>
            </li>

            <li>
                <a href="{{ route('about') }}" class="block rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                    About Us
                </a>
            </li>
        </ul>

        <div class="flex items-center space-x-3">
            @auth
                <span class="hidden text-sm font-semibold text-taupe sm:inline">
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="premium-button px-4 py-2"
                    >
                        Keluar
                    </button>
                </form>
            @else
                <a
                    href="{{ route('login') }}"
                    class="premium-button px-4 py-2"
                >
                    Masuk
                </a>
            @endauth
        </div>

    </div>
</nav>

@if (session('success'))
    <div class="mx-auto mt-6 w-full max-w-7xl px-4">
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
            {{ session('success') }}
        </div>
    </div>
@endif

@yield('content')

<footer class="mt-auto border-t border-linen/50 bg-ink text-ivory py-10">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div>
                <h3 class="text-2xl font-extrabold mb-3">
                    Outletin
                </h3>

                <p class="text-linen leading-7">
                    Solusi modern untuk mengelola outlet, stok, dan laporan keuangan bisnis Anda.
                </p>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-3">
                    Menu
                </h4>

                <ul class="space-y-2 text-linen">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-white transition">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('outlet') }}" class="hover:text-white transition">
                            Outlet
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('about') }}" class="hover:text-white transition">
                            About Us
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-3">
                    Kontak
                </h4>

                <p class="text-linen">Email: info@outletin.com</p>
                <p class="text-linen">Telp: +62 812 3456 7890</p>
            </div>

        </div>

        <div class="border-t border-white/10 mt-8 pt-6 text-center text-linen/70 text-sm">
            <p>&copy; 2026 Outletin. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
