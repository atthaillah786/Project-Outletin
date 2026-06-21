@extends('layouts.dashboard')

@section('title', 'CRUD Brand - Outletin')

@section('content')
<section class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" data-reveal>
    <div>
        <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">Brand library</p>
        <h1 class="text-3xl font-extrabold text-ink">Data Brand</h1>
        <p class="premium-muted">Kelola data brand franchise.</p>
    </div>

    <a href="{{ route('manage.brands.create') }}" class="premium-button">
        Tambah Brand
    </a>
</section>

<section class="premium-card p-6" data-reveal>
    <div class="overflow-x-auto">
        <table class="premium-table">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-3 pr-4">Logo</th>
                    <th class="py-3 pr-4">Brand</th>
                    <th class="py-3 pr-4">Franchisor</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3 pr-4">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($brands as $brand)
                    <tr class="border-b">
                        <td class="py-4 pr-4">
                            @if ($brand->logo_path)
                                <img src="{{ asset('storage/' . $brand->logo_path) }}" class="w-14 h-14 rounded-xl object-cover border">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-black text-white flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($brand->brand_name, 0, 1)) }}
                                </div>
                            @endif
                        </td>

                        <td class="py-4 pr-4">
                            <p class="font-semibold">{{ $brand->brand_name }}</p>
                            <p class="text-sm text-gray-500">{{ Str::limit($brand->description, 60) }}</p>
                        </td>

                        <td class="py-4 pr-4">{{ $brand->franchisor->name ?? '-' }}</td>

                        <td class="py-4 pr-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $brand->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $brand->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $brand->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($brand->status) }}
                            </span>
                        </td>

                        <td class="py-4 pr-4">
                            <div class="flex gap-2">
                                <a href="{{ route('manage.brands.edit', $brand->brand_id) }}" class="rounded-full bg-taupe px-3 py-2 text-sm font-bold text-white transition hover:bg-ink">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('manage.brands.destroy', $brand->brand_id) }}" onsubmit="return confirm('Hapus brand ini?')">
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
                        <td colspan="5" class="py-8 text-center text-gray-500">Belum ada brand.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
