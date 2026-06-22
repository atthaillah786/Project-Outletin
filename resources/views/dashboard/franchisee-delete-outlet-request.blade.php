@extends('layouts.dashboard')

@section('title', 'Ajukan Penghapusan Outlet - Outletin')

@section('content')
<section class="max-w-3xl mx-auto premium-card p-6 md:p-8" data-reveal>
    <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">Ajukan Penghapusan Outlet</p>
    <h1 class="text-3xl font-extrabold text-ink mb-6">{{ $outlet->outlet_name }}</h1>

    <form method="POST" action="{{ route('franchisee.outlet-delete-requests.store', $outlet->outlet_id) }}" class="space-y-5">
        @csrf

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50/90 px-4 py-3 text-sm text-red-800">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Nama Outlet</label>
            <input type="text" value="{{ $outlet->outlet_name }}" disabled class="premium-input bg-gray-100" />
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Alasan Penghapusan</label>
            <textarea name="reason" rows="6" class="premium-input" required minlength="10" maxlength="1000">{{ old('reason') }}</textarea>
            @error('reason')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3">
            <a href="{{ route('franchisee.dashboard') }}" class="premium-button-soft w-full">Batal</a>
            <button class="premium-button w-full">Kirim Permintaan</button>
        </div>
    </form>
</section>
@endsection
