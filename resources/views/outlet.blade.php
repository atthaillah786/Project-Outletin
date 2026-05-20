<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Outletin - Outlet</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">

<!-- Navbar -->
<nav class="bg-red-800 text-white sticky top-0 z-50 shadow-md">
  <div class="container mx-auto relative flex items-center justify-between p-4">
    
    <div class="text-xl font-bold ml-16">
      Outletin
    </div>

    <!-- Menu Tengah -->
    <ul class="absolute left-1/2 -translate-x-1/2 flex space-x-6">
      <li>
        <a href="{{ route('home') }}" class="hover:text-red-300">
          Home
        </a>
      </li>

      <li>
        <a href="{{ route('outlet') }}" class="text-red-200 font-semibold">
          Outlet
        </a>
      </li>

      <li>
        <a href="{{ route('about') }}" class="hover:text-red-300">
          About Us
        </a>
      </li>
    </ul>

    <!-- Menu Kanan -->
    <ul class="flex space-x-4">
      <li>
        <a href="{{ route('login') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition">
          Masuk
        </a>
      </li>
    </ul>
  </div>
</nav>

<main class="container mx-auto px-4 py-12">

  <!-- Heading -->
  <section class="text-center mb-12">
    <h1 class="text-4xl md:text-5xl font-bold text-black mb-3">
      Temukan Brand Outlet Anda
    </h1>

    <p class="text-gray-600 text-base md:text-lg">
      Cari brand yang tersedia dan temukan informasi singkatnya dengan mudah
    </p>
  </section>

  <!-- Search -->
  <section class="max-w-3xl mx-auto mb-12">
    <form method="GET" action="{{ route('outlet') }}" class="flex flex-col sm:flex-row gap-3">
      <input
        type="text"
        name="q"
        value="{{ $search }}"
        placeholder="Cari brand berdasarkan nama atau deskripsi..."
        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
      >

      <button
        type="submit"
        class="bg-red-700 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-800 transition"
      >
        Cari
      </button>

      @if ($search !== '')
        <a
          href="{{ route('outlet') }}"
          class="bg-gray-200 text-gray-800 px-6 py-3 rounded-xl font-semibold text-center hover:bg-gray-300 transition"
        >
          Reset
        </a>
      @endif
    </form>
  </section>

  <!-- Result Info -->
  @if ($search !== '')
    <p class="text-center text-gray-600 mb-8">
      Hasil pencarian untuk:
      <span class="font-semibold text-black">"{{ $search }}"</span>
    </p>
  @endif

  <!-- Brand Cards -->
  <section class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">

      @forelse ($brands as $brand)
        @php
  $brandName = $brand->brand_name;
  $description = $brand->description;
  $initial = strtoupper(substr($brand->brand_name, 0, 1));
@endphp

<div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm hover:shadow-md transition max-w-sm mx-auto w-full">

  @if (!empty($brand->logo_path))
    <img
      src="{{ asset('storage/' . $brand->logo_path) }}"
      alt="{{ $brandName }}"
      class="w-20 h-20 rounded-2xl object-cover mb-6 border border-gray-200"
    >
  @else
    <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center mb-6">
      <span class="text-white text-2xl font-bold">
        {{ $initial }}
      </span>
    </div>
  @endif

          <h3 class="text-xl font-extrabold text-black mb-3">
            {{ $brandName }}
          </h3>

          <p class="text-gray-600 text-base leading-8 mb-6 min-h-[120px]">
            {{ $description ?? 'Belum ada deskripsi.' }}
          </p>

          <a href="#" class="inline-block bg-red-700 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-red-800 transition">
            Lihat Brand
          </a>
        </div>

      @empty
        <div class="col-span-full">
          <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center shadow-sm">
            <h3 class="text-2xl font-bold text-black mb-2">
              Brand tidak ditemukan
            </h3>

            <p class="text-gray-600">
              Coba gunakan kata kunci lain untuk pencarian Anda.
            </p>
          </div>
        </div>
      @endforelse

    </div>
  </section>

</main>

<footer class="bg-black text-white py-10 mt-12">
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

        <p class="text-gray-400">
          Email: info@outletin.com
        </p>

        <p class="text-gray-400">
          Telp: +62 812 3456 7890
        </p>
      </div>

    </div>

    <div class="border-t border-gray-800 mt-8 pt-6 text-center text-gray-500 text-sm">
      <p>&copy; 2026 Outletin. All rights reserved.</p>
    </div>
  </div>
</footer>

</body>
</html>