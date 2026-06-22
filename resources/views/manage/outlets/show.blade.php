@extends('layouts.dashboard')

@section('title', 'Detail Outlet - Outletin')

@section('content')
<section class="max-w-3xl mx-auto premium-card p-6 md:p-8" data-reveal>
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">Outlet details</p>
            <h1 class="text-3xl font-extrabold text-ink">{{ $outlet->outlet_name }}</h1>
        </div>
    </div>

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-ink mb-2">Brand</label>
                <p class="text-gray-700">{{ $outlet->brand->brand_name ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-ink mb-2">Status</label>
                <p class="text-gray-700 capitalize">{{ $outlet->status }}</p>
            </div>

            @if (auth()->user()->role !== 'franchise')
                <div>
                    <label class="block text-sm font-bold text-ink mb-2">Franchisee</label>
                    <p class="text-gray-700">{{ $outlet->franchise->name ?? '-' }}</p>
                </div>
            @endif

            <div>
                <label class="block text-sm font-bold text-ink mb-2">Email</label>
                <p class="text-gray-700">{{ $outlet->franchise->email ?? '-' }}</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Alamat</label>
            <p class="text-gray-700 whitespace-pre-line">{{ $outlet->address ?? '-' }}</p>
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Dibuat</label>
            <p class="text-gray-700">{{ $outlet->created_at ? $outlet->created_at->format('d M Y H:i') : '-' }}</p>
        </div>
    </div>

    <div class="flex gap-3 mt-8">
        <a href="{{ route('manage.outlets.index') }}" class="premium-button-soft w-full">Kembali</a>
        <a href="{{ route('manage.outlets.edit', $outlet->outlet_id) }}" class="premium-button w-full">Edit</a>
    </div>
</section>
@endsection
