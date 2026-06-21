@extends('layouts.dashboard')

@section('title', 'Edit Outlet - Outletin')

@section('content')
<section class="max-w-3xl mx-auto premium-card p-6 md:p-8" data-reveal>
    <p class="mb-2 text-sm font-extrabold uppercase tracking-normal text-oxblood">Outlet refinement</p>
    <h1 class="text-3xl font-extrabold text-ink mb-6">Edit Outlet</h1>

    <form method="POST" action="{{ route('manage.outlets.update', $outlet->outlet_id) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Brand</label>
            <select name="brand_id" class="premium-input" required>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->brand_id }}" @selected(old('brand_id', $outlet->brand_id) == $brand->brand_id)>
                        {{ $brand->brand_name }}
                    </option>
                @endforeach
            </select>
        </div>

        @if (auth()->user()->role !== 'franchise')
            <div>
                <label class="block text-sm font-bold text-ink mb-2">Franchisee</label>
                <select name="franchise_id" class="premium-input" required>
                    @foreach ($franchises as $franchise)
                        <option value="{{ $franchise->user_id }}" @selected(old('franchise_id', $outlet->franchise_id) == $franchise->user_id)>
                            {{ $franchise->name }} - {{ $franchise->email }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Nama Outlet</label>
            <input type="text" name="outlet_name" value="{{ old('outlet_name', $outlet->outlet_name) }}" class="premium-input" required>
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Alamat</label>
            <textarea name="address" rows="5" class="premium-input">{{ old('address', $outlet->address) }}</textarea>
        </div>

        @if (auth()->user()->role !== 'franchise')
            <div>
                <label class="block text-sm font-bold text-ink mb-2">Status</label>
                <select name="status" class="premium-input">
                    <option value="pending" @selected(old('status', $outlet->status) === 'pending')>Pending</option>
                    <option value="approved" @selected(old('status', $outlet->status) === 'approved')>Approved</option>
                    <option value="rejected" @selected(old('status', $outlet->status) === 'rejected')>Rejected</option>
                </select>
            </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('manage.outlets.index') }}" class="premium-button-soft w-full">Batal</a>
            <button class="premium-button w-full">Update</button>
        </div>
    </form>
</section>
@endsection
