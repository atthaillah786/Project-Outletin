@extends('layouts.dashboard')

@section('title', 'Permintaan Penghapusan Outlet - Outletin')

@section('content')
<section class="mb-8" data-reveal>
    <p class="mb-3 text-sm font-extrabold uppercase tracking-normal text-oxblood">Permintaan Penghapusan Outlet</p>
    <h1 class="premium-section-title mb-2">Daftar Permintaan Penghapusan Outlet</h1>
</section>

<section class="premium-card p-6" data-reveal>
    <div class="overflow-x-auto">
        <table class="premium-table">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-3 pr-4">Outlet</th>
                    <th class="py-3 pr-4">Franchisee</th>
                    <th class="py-3 pr-4">Brand</th>
                    <th class="py-3 pr-4">Alasan</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3 pr-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $request)
                    <tr class="border-b align-top">
                        <td class="py-4 pr-4 font-semibold">{{ $request->outlet_name }}</td>
                        <td class="py-4 pr-4">{{ $request->franchise->name ?? '-' }}</td>
                        <td class="py-4 pr-4">{{ $request->brand->brand_name ?? '-' }}</td>
                        <td class="py-4 pr-4 max-w-xl whitespace-pre-line">{{ $request->reason }}</td>
                        <td class="py-4 pr-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $request->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $request->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="py-4 pr-4">
                            @if ($request->status === 'pending')
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('franchisor.outlet-delete-requests.approve', $request->outlet_deletion_request_id) }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full rounded-full bg-emerald-600 py-2 font-bold text-white hover:bg-emerald-700">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('franchisor.outlet-delete-requests.reject', $request->outlet_deletion_request_id) }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full rounded-full bg-oxblood py-2 font-bold text-white hover:bg-ink">Tolak</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-sm text-gray-500">Tidak ada tindakan</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">Belum ada permintaan penghapusan outlet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
