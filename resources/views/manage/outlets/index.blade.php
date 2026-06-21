@extends('layouts.dashboard')

@section('title', 'CRUD Outlet - Outletin')

@section('content')
<section class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" data-reveal>
    <div>
        <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">Outlet operations</p>
        <h1 class="text-3xl font-extrabold text-ink">Data Outlet</h1>
        <p class="premium-muted">Kelola data outlet franchise.</p>
    </div>

    <a href="{{ route('manage.outlets.create') }}" class="premium-button">
        Tambah Outlet
    </a>
</section>

<section class="premium-card p-6" data-reveal>
    <div class="overflow-x-auto">
        <table class="premium-table">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-3 pr-4">Outlet</th>
                    <th class="py-3 pr-4">Brand</th>
                    <th class="py-3 pr-4">Franchisee</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3 pr-4">Alamat</th>
                    <th class="py-3 pr-4">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($outlets as $outlet)
                    <tr class="border-b">
                        <td class="py-4 pr-4 font-semibold">{{ $outlet->outlet_name }}</td>
                        <td class="py-4 pr-4">{{ $outlet->brand->brand_name ?? '-' }}</td>
                        <td class="py-4 pr-4">{{ $outlet->franchise->name ?? '-' }}</td>
                        <td class="py-4 pr-4">{{ ucfirst($outlet->status) }}</td>
                        <td class="py-4 pr-4">{{ $outlet->address ?? '-' }}</td>
                        <td class="py-4 pr-4">
                            <div class="flex gap-2">
                                <a href="{{ route('manage.outlets.edit', $outlet->outlet_id) }}" class="rounded-full bg-taupe px-3 py-2 text-sm font-bold text-white transition hover:bg-ink">Edit</a>

                                <form method="POST" action="{{ route('manage.outlets.destroy', $outlet->outlet_id) }}" onsubmit="return confirm('Hapus outlet ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-full bg-oxblood px-3 py-2 text-sm font-bold text-white transition hover:bg-ink">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">Belum ada outlet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
