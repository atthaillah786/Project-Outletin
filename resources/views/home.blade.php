<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Outletin - Home</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">

<!-- Navbar -->
<nav class="bg-red-800 text-white sticky top-0 z-50">
  <div class="container mx-auto relative flex items-center justify-between p-4">

    <div class="text-xl font-bold ml-16">
      Outletin
    </div>

    <!-- Menu Tengah -->
    <ul class="absolute left-1/2 -translate-x-1/2 flex space-x-6">
      <li>
        <a href="{{ url('/') }}" class="text-red-300 font-semibold">
          Home
        </a>
      </li>
      <li>
        <a href="{{ url('/outlet') }}" class="hover:text-red-300">
          Outlet
        </a>
      </li>
      <li>
        <a href="{{ url('/about') }}" class="hover:text-red-300">
          About Us
        </a>
      </li>
    </ul>

    <!-- Menu Kanan -->
    <ul class="flex space-x-4">
      <li>
        <a href="{{ url('/login') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition">
          Masuk
        </a>
      </li>
    </ul>

  </div>
</nav>

<!-- Hero -->
<header
  class="bg-cover bg-center text-white text-center py-20"
  style="background-image: url('{{ asset('images/home.jpg') }}')"
>
  <div class="bg-black/50 py-32 px-4">
    <h1 class="text-4xl font-bold mb-4">
      Selamat Datang di Outletin
    </h1>

    <p class="text-lg mb-6">
      Sistem manajemen waralaba terbaik dan modern untuk bisnis Anda.
    </p>

    <a href="{{ url('/login') }}" class="bg-red-600 px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
      Coba Sekarang
    </a>
  </div>
</header>

<!-- Features -->
<section class="container mx-auto px-4 py-16">
  <div class="text-center mb-12">
    <h2 class="text-4xl md:text-5xl font-bold text-black mb-3">
      Semua yang Anda butuhkan
    </h2>

    <p class="text-gray-600 text-base md:text-lg">
      Fitur lengkap untuk mengelola bisnis outlet Anda dengan efisien
    </p>
  </div>

  <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

    <!-- Card 1 -->
    <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm hover:shadow-md transition max-w-sm mx-auto w-full">
      <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L5 5h14l2 5.5M4 10.5h16M5 10.5V19h14v-8.5M9 19v-4h6v4" />
        </svg>
      </div>

      <h3 class="text-xl font-bold text-black mb-3">
        Manajemen Outlet
      </h3>

      <p class="text-gray-600 text-base leading-8 mb-6">
        Kelola multiple outlet dari satu dashboard. Pantau performa setiap cabang secara real-time.
      </p>

      <ul class="space-y-3 text-gray-800 text-base">
        <li class="flex items-center gap-3">✓ Multi-outlet support</li>
        <li class="flex items-center gap-3">✓ Real-time monitoring</li>
        <li class="flex items-center gap-3">✓ Performance analytics</li>
      </ul>
    </div>

    <!-- Card 2 -->
    <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm hover:shadow-md transition max-w-sm mx-auto w-full">
      <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
        </svg>
      </div>

      <h3 class="text-xl font-bold text-black mb-3">
        Manajemen Bahan Baku
      </h3>

      <p class="text-gray-600 text-base leading-8 mb-6">
        Kontrol stok bahan baku dengan sistem inventori cerdas. Hindari kehabisan stok atau pemborosan.
      </p>

      <ul class="space-y-3 text-gray-800 text-base">
        <li class="flex items-center gap-3">✓ Auto stock alerts</li>
        <li class="flex items-center gap-3">✓ Supplier management</li>
        <li class="flex items-center gap-3">✓ Waste tracking</li>
      </ul>
    </div>

    <!-- Card 3 -->
    <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm hover:shadow-md transition max-w-sm mx-auto w-full">
      <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v13H7z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M14 3v5h5M9 13h6M9 17h6M9 9h2" />
        </svg>
      </div>

      <h3 class="text-xl font-bold text-black mb-3">
        Laporan Keuangan
      </h3>

      <p class="text-gray-600 text-base leading-8 mb-6">
        Buat laporan keuangan lengkap dengan satu klik. Pantau profit, expense, dan cash flow dengan mudah.
      </p>

      <ul class="space-y-3 text-gray-800 text-base">
        <li class="flex items-center gap-3">✓ Automated reports</li>
        <li class="flex items-center gap-3">✓ Profit & loss tracking</li>
        <li class="flex items-center gap-3">✓ Export to Excel</li>
      </ul>
    </div>

  </div>
</section>

<!-- CTA -->
<section class="container mx-auto px-4 pb-12">
  <div class="relative overflow-hidden rounded-3xl bg-red-800 px-8 py-12 md:px-12 lg:px-16">

    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">

      <div>
        <h2 class="text-4xl md:text-5xl font-bold text-white leading-tight mb-6">
          Siap membangun<br>
          masa depan bisnis<br>
          Anda?
        </h2>

        <p class="text-white text-base leading-relaxed mb-8 max-w-lg">
          Bergabunglah dengan ratusan brand dan mitra yang telah mentransformasi
          strategi ekspansi mereka melalui Outletin.
        </p>

        <div class="space-y-4 max-w-md">
          <div class="bg-red-700 rounded-xl p-5 shadow-md">
            <h4 class="text-white font-bold mb-2">
              Untuk Pemilik Brand
            </h4>

            <p class="text-white text-sm mb-4">
              Digitalisasi model waralaba Anda dan mulai penskalaan dengan mitra yang terverifikasi.
            </p>

            <a href="{{ url('/login') }}" class="block w-full text-center bg-red-500 text-white font-semibold py-3 rounded-lg hover:bg-white hover:text-red-700 transition">
              Mulai Sekarang
            </a>
          </div>

          <div class="bg-red-700 rounded-xl p-5 shadow-md">
            <h4 class="text-white font-bold mb-2">
              Untuk Mitra
            </h4>

            <p class="text-white text-sm mb-4">
              Telusuri peluang investasi premium dan kelola portofolio Anda.
            </p>

            <a href="{{ url('/outlet') }}" class="block w-full text-center border border-white text-white font-semibold py-3 rounded-lg hover:bg-white hover:text-red-700 transition">
              Telusuri Brand
            </a>
          </div>
        </div>
      </div>

      <!-- Kanan -->
      <div class="flex justify-center lg:justify-end">
        <div class="bg-red-700 p-4 rounded-2xl shadow-xl">
          <img
            src="{{ asset('images/home1.jpg') }}"
            alt="Outletin"
            class="w-full max-w-sm rounded-xl shadow-2xl object-cover"
          >
        </div>
      </div>

    </div>
  </div>
</section>

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
          <li><a href="{{ url('/') }}" class="hover:text-white transition">Home</a></li>
          <li><a href="{{ url('/outlet') }}" class="hover:text-white transition">Outlet</a></li>
          <li><a href="{{ url('/about') }}" class="hover:text-white transition">About Us</a></li>
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