@extends('layouts.dashboard')

@section('title', 'Data Produk - Outletin')

@section('content')
<section class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" data-reveal>
    <div>
        <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">Product catalog</p>
        <h1 class="text-3xl font-extrabold text-ink">Data Produk</h1>
        <p class="premium-muted">Kelola produk milik brand Anda.</p>
    </div>

    <a href="{{ route('manage.produk.create') }}" class="premium-button">
        Tambah Produk
    </a>
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
                    <th class="py-3 pr-4">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($produk as $item)
                    <tr class="border-b">
                        <td class="py-4 pr-4">{{ $item->produk_id }}</td>

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

                        <td class="py-4 pr-4">
                            <div class="flex gap-2">
                                <a href="{{ route('manage.produk.edit', $item->produk_id) }}" class="rounded-full bg-taupe px-3 py-2 text-sm font-bold text-white transition hover:bg-ink">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('manage.produk.destroy', $item->produk_id) }}" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="rounded-full bg-oxblood px-3 py-2 text-sm font-bold text-white transition hover:bg-ink">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            Belum ada produk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
