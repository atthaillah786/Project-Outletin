<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Brand - Outletin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">

<nav class="bg-red-800 text-white sticky top-0 z-50 shadow-md">
    <div class="container mx-auto flex items-center justify-between px-4 py-4">
        <a href="{{ route('home') }}" class="text-xl font-bold">
            Outletin
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-600 px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition">
                Keluar
            </button>
        </form>
    </div>
</nav>

<main class="container mx-auto px-4 py-12">
    <section class="max-w-3xl mx-auto bg-white border border-gray-200 rounded-3xl p-8 md:p-10 shadow-sm text-center">

        @if ($brand->logo_path)
            <img
                src="{{ asset('storage/' . $brand->logo_path) }}"
                alt="{{ $brand->brand_name }}"
                class="w-28 h-28 rounded-2xl object-cover mx-auto mb-6 border"
            >
        @endif

        <h1 class="text-4xl font-bold text-black mb-3">
            {{ $brand->brand_name }}
        </h1>

        <p class="text-gray-600 leading-8 mb-6">
            {{ $brand->description }}
        </p>

        <div class="inline-block bg-yellow-100 text-yellow-700 px-5 py-2 rounded-full font-semibold">
            Menunggu Verifikasi Superadmin
        </div>

        <p class="text-gray-500 mt-6">
            Dashboard pemilik brand akan terbuka setelah brand Anda disetujui.
        </p>
    </section>
</main>

</body>
</html>