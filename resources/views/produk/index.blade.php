@extends('layouts.auth')

@section('title', 'Produk Brand')

@section('content')
<style>
    .confirm-modal {
        position: fixed;
        inset: 0;
        z-index: 80;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(17, 24, 39, 0.55);
        padding: 20px;
    }

    .confirm-modal.is-open {
        display: flex;
    }
</style>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <p class="text-sm font-semibold text-red-700 mb-2">Pemilik Brand</p>
            <h1 class="text-3xl font-bold text-black">Produk Brand</h1>
            <p class="text-gray-600 mt-2">Kelola menu atau produk untuk setiap brand yang sudah approved.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a
                href="{{ route('franchisor.dashboard') }}"
                class="inline-flex items-center justify-center bg-gray-100 text-gray-800 px-5 py-3 rounded-lg font-semibold hover:bg-gray-200 transition"
            >
                Dashboard
            </a>
            <a
                href="{{ route('franchisor.produk.create') }}"
                class="inline-flex items-center justify-center bg-red-700 text-white px-5 py-3 rounded-lg font-semibold hover:bg-red-800 transition"
            >
                Tambah Produk
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Total Produk</p>
            <p class="text-3xl font-bold text-black">{{ $products->count() }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Brand Aktif</p>
            <p class="text-3xl font-bold text-black">{{ $brands->count() }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <p class="text-gray-500 text-sm mb-2">Harga Rata-rata</p>
            <p class="text-2xl font-bold text-black">
                Rp {{ number_format($products->avg('Price') ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        @if ($products->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-600 mb-4">Belum ada produk.</p>
                <a href="{{ route('franchisor.produk.create') }}" class="text-red-700 font-semibold hover:underline">
                    Tambah produk pertama
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-600 text-sm">
                            <th class="py-3 pr-4">Produk</th>
                            <th class="py-3 pr-4">Brand</th>
                            <th class="py-3 pr-4">Harga</th>
                            <th class="py-3 pr-4">Dibuat</th>
                            <th class="py-3 pr-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 pr-4 font-semibold text-black">{{ $product->produk_name }}</td>
                                <td class="py-4 pr-4 text-gray-700">{{ $product->brand->brand_name ?? '-' }}</td>
                                <td class="py-4 pr-4 font-semibold text-gray-900">
                                    Rp {{ number_format((float) $product->Price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 pr-4 text-gray-600">
                                    {{ $product->created_at?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="py-4 pr-4">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('franchisor.produk.edit', $product) }}"
                                            class="inline-flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition"
                                        >
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('franchisor.produk.destroy', $product) }}" data-confirm="Hapus produk {{ $product->produk_name }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="confirm-modal" data-confirm-modal aria-hidden="true">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <h2 class="text-xl font-bold text-black mb-2">Konfirmasi Hapus</h2>
        <p class="text-gray-600 mb-6" data-confirm-message>Data ini akan dihapus.</p>
        <div class="flex justify-end gap-3">
            <button type="button" class="bg-gray-100 text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-200 transition" data-confirm-cancel>
                Batal
            </button>
            <button type="button" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition" data-confirm-accept>
                Hapus
            </button>
        </div>
    </div>
</div>

<script>
    (() => {
        const modal = document.querySelector('[data-confirm-modal]');
        const message = document.querySelector('[data-confirm-message]');
        const cancelButton = document.querySelector('[data-confirm-cancel]');
        const acceptButton = document.querySelector('[data-confirm-accept]');
        let activeForm = null;

        document.querySelectorAll('form[data-confirm]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                activeForm = form;
                message.textContent = form.dataset.confirm || 'Data ini akan dihapus.';
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
            });
        });

        const closeModal = () => {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            activeForm = null;
        };

        cancelButton.addEventListener('click', closeModal);
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });

        acceptButton.addEventListener('click', () => {
            if (activeForm) {
                activeForm.submit();
            }
        });
    })();
</script>
@endsection
