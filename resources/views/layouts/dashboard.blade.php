<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Outletin')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-50 min-h-screen text-gray-900">

<nav class="bg-red-800 text-white sticky top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">

        <div class="flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tight">
                Outletin
            </a>

            @auth
                <div class="hidden md:flex items-center gap-5 text-sm font-medium">

                    @if (auth()->user()->role === 'superadmin')
                        <a href="{{ route('superadmin.dashboard') }}" class="hover:text-red-200 transition">
                            Dashboard
                        </a>

                        <a href="{{ route('superadmin.brand.verification') }}" class="hover:text-red-200 transition">
                            Verifikasi Brand
                        </a>

                        <a href="{{ route('manage.brands.index') }}" class="hover:text-red-200 transition">
                            Brand
                        </a>

                        <a href="{{ route('manage.outlets.index') }}" class="hover:text-red-200 transition">
                            Outlet
                        </a>
                    @endif


                    @if (auth()->user()->role === 'franchisor')
                        <a href="{{ route('franchisor.dashboard') }}" class="hover:text-red-200 transition">
                            Dashboard
                        </a>

                        <a href="{{ route('manage.brands.index') }}" class="hover:text-red-200 transition">
                            Brand Saya
                        </a>

                        <a href="{{ route('manage.outlets.index') }}" class="hover:text-red-200 transition">
                            Outlet
                        </a>

                        <a href="{{ route('manage.produk.index') }}" class="hover:text-red-200 transition">
                            Produk
                        </a>
                    @endif


                    @if (auth()->user()->role === 'franchise')
                        <a href="{{ route('franchisee.dashboard') }}" class="hover:text-red-200 transition">
                            Dashboard
                        </a>

                        <a href="{{ route('manage.outlets.index') }}" class="hover:text-red-200 transition">
                            Outlet Saya
                        </a>

                        <a href="{{ route('franchisee.produk.index') }}" class="hover:text-red-200 transition">
                            Produk Brand
                        </a>
                    @endif

                </div>
            @endauth
        </div>


        <div class="flex items-center gap-4">
            @auth
                <div class="hidden sm:block text-right">
                    <p class="text-sm font-semibold">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-red-100">
                        {{ ucfirst(auth()->user()->role) }}
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="bg-red-600 px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition"
                    >
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-white text-red-800 px-4 py-2 rounded-lg font-semibold hover:bg-red-100 transition">
                    Masuk
                </a>
            @endauth
        </div>
    </div>


    @auth
        <div class="md:hidden border-t border-red-700">
            <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap gap-3 text-sm">

                @if (auth()->user()->role === 'superadmin')
                    <a href="{{ route('superadmin.dashboard') }}" class="bg-red-700 px-3 py-2 rounded-lg">
                        Dashboard
                    </a>

                    <a href="{{ route('superadmin.brand.verification') }}" class="bg-red-700 px-3 py-2 rounded-lg">
                        Verifikasi Brand
                    </a>

                    <a href="{{ route('manage.brands.index') }}" class="bg-red-700 px-3 py-2 rounded-lg">
                        Brand
                    </a>

                    <a href="{{ route('manage.outlets.index') }}" class="bg-red-700 px-3 py-2 rounded-lg">
                        Outlet
                    </a>
                @endif


                @if (auth()->user()->role === 'franchisor')
                    <a href="{{ route('franchisor.dashboard') }}" class="bg-red-700 px-3 py-2 rounded-lg">
                        Dashboard
                    </a>

                    <a href="{{ route('manage.brands.index') }}" class="bg-red-700 px-3 py-2 rounded-lg">
                        Brand Saya
                    </a>

                    <a href="{{ route('manage.outlets.index') }}" class="bg-red-700 px-3 py-2 rounded-lg">
                        Outlet
                    </a>

                    <a href="{{ route('manage.produk.index') }}" class="bg-red-700 px-3 py-2 rounded-lg">
                        Produk
                    </a>
                @endif


                @if (auth()->user()->role === 'franchise')
                    <a href="{{ route('franchisee.dashboard') }}" class="bg-red-700 px-3 py-2 rounded-lg">
                        Dashboard
                    </a>

                    <a href="{{ route('manage.outlets.index') }}" class="bg-red-700 px-3 py-2 rounded-lg">
                        Outlet Saya
                    </a>

                    <a href="{{ route('franchisee.produk.index') }}" class="bg-red-700 px-3 py-2 rounded-lg">
                        Produk Brand
                    </a>
                @endif

            </div>
        </div>
    @endauth
</nav>


<main class="max-w-7xl mx-auto px-4 py-8">

    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

</main>


<footer class="border-t bg-white mt-10">
    <div class="max-w-7xl mx-auto px-4 py-6 text-sm text-gray-500 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <p>
            © {{ date('Y') }} Outletin. All rights reserved.
        </p>

        @auth
            <p>
                Login sebagai {{ ucfirst(auth()->user()->role) }}
            </p>
        @endauth
    </div>
</footer>


@stack('scripts')

</body>
</html>