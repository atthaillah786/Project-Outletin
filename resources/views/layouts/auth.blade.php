<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Outletin')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

<nav class="bg-red-800 text-white sticky top-0 z-50 shadow-md">
    <div class="container mx-auto relative flex items-center justify-between p-4">

        <a href="{{ route('home') }}" class="text-xl font-bold ml-16">
            Outletin
        </a>

        <ul class="absolute left-1/2 -translate-x-1/2 hidden md:flex space-x-6">
            <li>
                <a href="{{ route('home') }}" class="hover:text-red-300">
                    Home
                </a>
            </li>

            <li>
                <a href="{{ route('outlet') }}" class="hover:text-red-300">
                    Outlet
                </a>
            </li>

            <li>
                <a href="{{ route('about') }}" class="hover:text-red-300">
                    About Us
                </a>
            </li>
        </ul>

        <div class="flex items-center space-x-4">
            @auth
                <span class="hidden sm:inline text-sm text-red-100">
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition"
                    >
                        Keluar
                    </button>
                </form>
            @else
                <a
                    href="{{ route('login') }}"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition"
                >
                    Masuk
                </a>
            @endauth
        </div>

    </div>
</nav>

@if (session('success'))
    <div class="container mx-auto px-4 mt-6">
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    </div>
@endif

@yield('content')

<footer class="bg-black text-white py-10 mt-auto">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div>
                <h3 class="text-2xl font-bold mb-3">
                    Outletin
                </h3>

                <p class="text-gray-400 leading-7">
                    Solusi modern untuk mengelola outlet, stok, dan laporan keuangan bisnis Anda.
                </p>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-3">
                    Menu
                </h4>

                <ul class="space-y-2 text-gray-400">
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

                <p class="text-gray-400">Email: info@outletin.com</p>
                <p class="text-gray-400">Telp: +62 812 3456 7890</p>
            </div>

        </div>

        <div class="border-t border-gray-800 mt-8 pt-6 text-center text-gray-500 text-sm">
            <p>&copy; 2026 Outletin. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>