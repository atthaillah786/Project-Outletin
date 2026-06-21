@extends('layouts.auth')

@section('title', 'About Us - Outletin')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-10">
    <h1 class="text-3xl font-extrabold text-ink mb-4">About Us</h1>
    <p class="premium-muted mb-6">
        Outletin adalah solusi modern untuk mengelola outlet, produk, serta laporan keuangan bisnis Anda.
        Dibangun agar proses pengajuan brand dan pengelolaan outlet bisa berjalan lebih rapi, cepat, dan terpantau.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="premium-card p-6">
            <h2 class="text-lg font-extrabold text-ink mb-2">Pengajuan Mudah</h2>
            <p class="premium-muted">
                Franchisee dapat mengajukan outlet ke brand yang tersedia, sementara franchisor dapat memproses statusnya.
            </p>
        </div>

        <div class="premium-card p-6">
            <h2 class="text-lg font-extrabold text-ink mb-2">Kelola Produk</h2>
            <p class="premium-muted">
                Produk dapat dikelola sesuai kebutuhan outlet dan brand yang telah disetujui.
            </p>
        </div>

        <div class="premium-card p-6">
            <h2 class="text-lg font-extrabold text-ink mb-2">Laporan Keuangan</h2>
            <p class="premium-muted">
                Pantau pemasukan, pengeluaran, dan profit per bulan agar keputusan bisnis lebih berbasis data.
            </p>
        </div>
    </div>
</section>
@endsection

