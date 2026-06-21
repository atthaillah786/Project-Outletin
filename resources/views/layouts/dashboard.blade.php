<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Outletin')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="premium-shell min-h-screen">

<nav class="premium-nav mt-3" data-shrink-navbar>
    <div class="px-4 py-3 md:px-6 flex items-center justify-between">

        <div class="flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-normal text-oxblood">
                Outletin
            </a>

            @auth
                <div class="hidden md:flex items-center gap-2 rounded-full border border-linen/60 bg-white/55 px-2 py-1 text-sm font-semibold text-taupe shadow-sm">

                    @if (auth()->user()->role === 'superadmin')
                        <a href="{{ route('superadmin.dashboard') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                            Dashboard
                        </a>

                        <a href="{{ route('superadmin.brand.verification') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                            Verifikasi Brand
                        </a>

                        <a href="{{ route('manage.brands.index') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                            Brand
                        </a>

                        <a href="{{ route('manage.outlets.index') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                            Outlet
                        </a>
                    @endif


                    @if (auth()->user()->role === 'franchisor')
                        <a href="{{ route('franchisor.dashboard') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                            Dashboard
                        </a>

                        <a href="{{ route('manage.brands.index') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                            Brand Saya
                        </a>

                        <a href="{{ route('manage.outlets.index') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                            Outlet
                        </a>

                        <a href="{{ route('manage.produk.index') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                            Produk
                        </a>
                    @endif


                    @if (auth()->user()->role === 'franchise')
                        <a href="{{ route('franchisee.dashboard') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                            Dashboard
                        </a>

                        <a href="{{ route('manage.outlets.index') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
                            Outlet Saya
                        </a>

                        <a href="{{ route('franchisee.produk.index') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
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

                    <p class="text-xs text-taupe">
                        {{ ucfirst(auth()->user()->role) }}
                    </p>
                </div>

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
                <a href="{{ route('login') }}" class="premium-button px-4 py-2">
                    Masuk
                </a>
            @endauth
        </div>
    </div>


    @auth
        <div class="md:hidden border-t border-linen/60">
            <div class="px-4 py-3 flex flex-wrap gap-3 text-sm">

                @if (auth()->user()->role === 'superadmin')
                    <a href="{{ route('superadmin.dashboard') }}" class="rounded-full bg-white/70 px-3 py-2 font-semibold text-oxblood shadow-sm">
                        Dashboard
                    </a>

                    <a href="{{ route('superadmin.brand.verification') }}" class="rounded-full bg-white/70 px-3 py-2 font-semibold text-oxblood shadow-sm">
                        Verifikasi Brand
                    </a>

                    <a href="{{ route('manage.brands.index') }}" class="rounded-full bg-white/70 px-3 py-2 font-semibold text-oxblood shadow-sm">
                        Brand
                    </a>

                    <a href="{{ route('manage.outlets.index') }}" class="rounded-full bg-white/70 px-3 py-2 font-semibold text-oxblood shadow-sm">
                        Outlet
                    </a>
                @endif


                @if (auth()->user()->role === 'franchisor')
                    <a href="{{ route('franchisor.dashboard') }}" class="rounded-full bg-white/70 px-3 py-2 font-semibold text-oxblood shadow-sm">
                        Dashboard
                    </a>

                    <a href="{{ route('manage.brands.index') }}" class="rounded-full bg-white/70 px-3 py-2 font-semibold text-oxblood shadow-sm">
                        Brand Saya
                    </a>

                    <a href="{{ route('manage.outlets.index') }}" class="rounded-full bg-white/70 px-3 py-2 font-semibold text-oxblood shadow-sm">
                        Outlet
                    </a>

                    <a href="{{ route('manage.produk.index') }}" class="rounded-full bg-white/70 px-3 py-2 font-semibold text-oxblood shadow-sm">
                        Produk
                    </a>
                @endif


                @if (auth()->user()->role === 'franchise')
                    <a href="{{ route('franchisee.dashboard') }}" class="rounded-full bg-white/70 px-3 py-2 font-semibold text-oxblood shadow-sm">
                        Dashboard
                    </a>

                    <a href="{{ route('manage.outlets.index') }}" class="rounded-full bg-white/70 px-3 py-2 font-semibold text-oxblood shadow-sm">
                        Outlet Saya
                    </a>

                    <a href="{{ route('franchisee.produk.index') }}" class="rounded-full bg-white/70 px-3 py-2 font-semibold text-oxblood shadow-sm">
                        Produk Brand
                    </a>
                @endif

            </div>
        </div>
    @endauth
</nav>


<main class="max-w-7xl mx-auto px-4 py-10 md:py-12">

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50/90 px-5 py-4 text-sm font-semibold text-red-800 shadow-sm mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50/90 px-5 py-4 text-sm font-semibold text-red-800 shadow-sm mb-6">
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


<footer class="border-t border-linen/50 bg-white/45 mt-10">
    <div class="max-w-7xl mx-auto px-4 py-6 text-sm text-taupe flex flex-col md:flex-row md:items-center md:justify-between gap-2">
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
