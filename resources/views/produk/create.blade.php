@extends('layouts.auth')

@section('title', 'Tambah Produk')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <p class="text-sm font-semibold text-red-700 mb-2">Produk Brand</p>
            <h1 class="text-3xl font-bold text-black">Tambah Produk</h1>
        </div>

        @include('produk._form', [
            'action' => route('franchisor.produk.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan Produk',
            'produk' => null,
            'selectedBrandId' => $selectedBrandId,
        ])
    </div>
</div>
@endsection
