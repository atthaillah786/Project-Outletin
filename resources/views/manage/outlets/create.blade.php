@extends('layouts.dashboard')

@section('title', 'Tambah Outlet - Outletin')

@section('content')
<section class="max-w-3xl mx-auto premium-card p-6 md:p-8" data-reveal>
    <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">Outlet setup</p>
    <h1 class="text-3xl font-extrabold text-ink mb-6">Tambah Outlet</h1>

    <form method="POST" action="{{ route('manage.outlets.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Brand</label>
            <select name="brand_id" class="premium-input" required>
                <option value="">Pilih brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->brand_id }}" @selected(old('brand_id') == $brand->brand_id)>
                        {{ $brand->brand_name }}
                    </option>
                @endforeach
            </select>
        </div>

        @if (auth()->user()->role !== 'franchise')
            <div>
                <label class="block text-sm font-bold text-ink mb-2">Franchisee</label>
                <select name="franchise_id" class="premium-input" required>
                    <option value="">Pilih franchisee</option>
                    @foreach ($franchises as $franchise)
                        <option value="{{ $franchise->user_id }}" @selected(old('franchise_id') == $franchise->user_id)>
                            {{ $franchise->name }} - {{ $franchise->email }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Nama Outlet</label>
            <input type="text" name="outlet_name" value="{{ old('outlet_name') }}" class="premium-input" required>
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Alamat</label>
            <textarea name="address" rows="5" class="premium-input">{{ old('address') }}</textarea>
        </div>

        @if (auth()->user()->role !== 'franchise')
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
            <a href="{{ route('manage.outlets.index') }}" class="premium-button-soft w-full">Batal</a>
            <button class="premium-button w-full">Simpan</button>
        </div>
    </form>
</section>
@endsection
