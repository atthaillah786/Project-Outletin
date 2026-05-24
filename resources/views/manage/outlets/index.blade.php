@extends('layouts.dashboard')

@section('title', 'CRUD Outlet - Outletin')

@section('content')
<section class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold">Data Outlet</h1>
        <p class="text-gray-600">Kelola data outlet franchise.</p>
    </div>

    <a href="{{ route('manage.outlets.create') }}" class="bg-red-700 text-white px-5 py-3 rounded-xl font-semibold hover:bg-red-800">
        Tambah Outlet
    </a>
</section>

<section class="bg-white border rounded-2xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
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
                                <a href="{{ route('manage.outlets.edit', $outlet->outlet_id) }}" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm">Edit</a>

                                <form method="POST" action="{{ route('manage.outlets.destroy', $outlet->outlet_id) }}" onsubmit="return confirm('Hapus outlet ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-2 rounded-lg text-sm">Hapus</button>
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