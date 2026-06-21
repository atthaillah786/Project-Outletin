@extends('layouts.dashboard')

@section('title', 'Produk Brand - Outletin')

@section('content')
<section class="mb-6" data-reveal>
    <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">Connected catalog</p>
    <h1 class="text-3xl font-extrabold text-ink">Data Produk Brand</h1>
    <p class="premium-muted">
        Produk yang tersedia dari brand yang sudah terhubung dengan outlet Anda.
    </p>
</section>

<section class="premium-card p-6" data-reveal>
    <div class="overflow-x-auto">
        <table class="premium-table">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-3 pr-4">ID</th>
                    <th class="py-3 pr-4">Nama Produk</th>
                    <th class="py-3 pr-4">Brand</th>
                    <th class="py-3 pr-4">Harga</th>
                    <th class="py-3 pr-4">Dibuat</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($produk as $item)
                    <tr class="border-b">
                        <td class="py-4 pr-4">
                            {{ $item->produk_id }}
                        </td>

                        <td class="py-4 pr-4 font-semibold">
                            {{ $item->produk_name }}
                        </td>

                        <td class="py-4 pr-4">
                            {{ $item->brand->brand_name ?? '-' }}
                        </td>

                        <td class="py-4 pr-4">
                            Rp {{ number_format($item->Price, 0, ',', '.') }}
                        </td>

                        <td class="py-4 pr-4">
                            {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">
                            Belum ada produk yang bisa ditampilkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
