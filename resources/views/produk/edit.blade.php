@extends('layouts.auth')

@section('title', 'Edit Produk')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <p class="text-sm font-semibold text-red-700 mb-2">Produk Brand</p>
            <h1 class="text-3xl font-bold text-black">Edit Produk</h1>
        </div>

        @include('produk._form', [
            'action' => route('franchisor.produk.update', $produk),
            'method' => 'PUT',
            'submitLabel' => 'Perbarui Produk',
            'selectedBrandId' => $produk->brand_id,
        ])
    </div>
</div>
@endsection
