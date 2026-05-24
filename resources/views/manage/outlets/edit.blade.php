@extends('layouts.dashboard')

@section('title', 'Edit Outlet - Outletin')

@section('content')
<section class="max-w-3xl mx-auto bg-white border rounded-2xl p-6 shadow-sm">
    <h1 class="text-3xl font-bold mb-6">Edit Outlet</h1>

    <form method="POST" action="{{ route('manage.outlets.update', $outlet->outlet_id) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold mb-2">Brand</label>
            <select name="brand_id" class="w-full border rounded-xl px-4 py-3" required>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->brand_id }}" @selected(old('brand_id', $outlet->brand_id) == $brand->brand_id)>
                        {{ $brand->brand_name }}
                    </option>
                @endforeach
            </select>
        </div>

        @if (auth()->user()->role !== 'franchise')
            <div>
                <label class="block text-sm font-semibold mb-2">Franchisee</label>
                <select name="franchise_id" class="w-full border rounded-xl px-4 py-3" required>
                    @foreach ($franchises as $franchise)
                        <option value="{{ $franchise->user_id }}" @selected(old('franchise_id', $outlet->franchise_id) == $franchise->user_id)>
                            {{ $franchise->name }} - {{ $franchise->email }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div>
            <label class="block text-sm font-semibold mb-2">Nama Outlet</label>
            <input type="text" name="outlet_name" value="{{ old('outlet_name', $outlet->outlet_name) }}" class="w-full border rounded-xl px-4 py-3" required>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Alamat</label>
            <textarea name="address" rows="5" class="w-full border rounded-xl px-4 py-3">{{ old('address', $outlet->address) }}</textarea>
        </div>

        @if (auth()->user()->role !== 'franchise')
            <div>
                <label class="block text-sm font-semibold mb-2">Status</label>
                <select name="status" class="w-full border rounded-xl px-4 py-3">
                    <option value="pending" @selected(old('status', $outlet->status) === 'pending')>Pending</option>
                    <option value="approved" @selected(old('status', $outlet->status) === 'approved')>Approved</option>
                    <option value="rejected" @selected(old('status', $outlet->status) === 'rejected')>Rejected</option>
                </select>
            </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('manage.outlets.index') }}" class="w-full text-center bg-gray-200 py-3 rounded-xl font-semibold">Batal</a>
            <button class="w-full bg-red-700 text-white py-3 rounded-xl font-semibold">Update</button>
        </div>
    </form>
</section>
@endsection