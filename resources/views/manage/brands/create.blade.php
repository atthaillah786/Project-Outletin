@extends('layouts.dashboard')

@section('title', 'Tambah Brand - Outletin')

@section('content')
<section class="max-w-3xl mx-auto premium-card p-6 md:p-8" data-reveal>
    <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">Brand setup</p>
    <h1 class="text-3xl font-extrabold text-ink mb-6">Tambah Brand</h1>

    <form method="POST" action="{{ route('manage.brands.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        @if (auth()->user()->role === 'superadmin')
            <div>
                <label class="block text-sm font-bold text-ink mb-2">Franchisor</label>
                <select name="franchisor_id" class="premium-input" required>
                    <option value="">Pilih franchisor</option>
                    @foreach ($franchisors as $franchisor)
                        <option value="{{ $franchisor->user_id }}" @selected(old('franchisor_id') == $franchisor->user_id)>
                            {{ $franchisor->name }} - {{ $franchisor->email }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Nama Brand</label>
            <input type="text" name="brand_name" value="{{ old('brand_name') }}" class="premium-input" required>
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Deskripsi</label>
            <textarea name="description" rows="5" class="premium-input">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Logo Brand</label>
            <input type="file" name="logo" accept="image/*" class="premium-input">
        </div>

        @if (auth()->user()->role === 'superadmin')
            <div>
                <label class="block text-sm font-bold text-ink mb-2">Status</label>
                <select name="status" class="premium-input">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('manage.brands.index') }}" class="premium-button-soft w-full">Batal</a>
            <button class="premium-button w-full">Simpan</button>
        </div>
    </form>
</section>
@endsection
