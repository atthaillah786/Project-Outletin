@extends('layouts.dashboard')

@section('title', 'Edit Brand - Outletin')

@section('content')
<section class="max-w-3xl mx-auto bg-white border rounded-2xl p-6 shadow-sm">
    <h1 class="text-3xl font-bold mb-6">Edit Brand</h1>

    <form method="POST" action="{{ route('manage.brands.update', $brand->brand_id) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        @if (auth()->user()->role === 'superadmin')
            <div>
                <label class="block text-sm font-semibold mb-2">Franchisor</label>
                <select name="franchisor_id" class="w-full border rounded-xl px-4 py-3" required>
                    @foreach ($franchisors as $franchisor)
                        <option value="{{ $franchisor->user_id }}" @selected(old('franchisor_id', $brand->franchisor_id) == $franchisor->user_id)>
                            {{ $franchisor->name }} - {{ $franchisor->email }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div>
            <label class="block text-sm font-semibold mb-2">Nama Brand</label>
            <input type="text" name="brand_name" value="{{ old('brand_name', $brand->brand_name) }}" class="w-full border rounded-xl px-4 py-3" required>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Deskripsi</label>
            <textarea name="description" rows="5" class="w-full border rounded-xl px-4 py-3">{{ old('description', $brand->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Logo Brand</label>
            @if ($brand->logo_path)
                <img src="{{ asset('storage/' . $brand->logo_path) }}" class="w-20 h-20 object-cover rounded-xl border mb-3">
            @endif
            <input type="file" name="logo" accept="image/*" class="w-full border rounded-xl px-4 py-3">
        </div>

        @if (auth()->user()->role === 'superadmin')
            <div>
                <label class="block text-sm font-semibold mb-2">Status</label>
                <select name="status" class="w-full border rounded-xl px-4 py-3">
                    <option value="pending" @selected(old('status', $brand->status) === 'pending')>Pending</option>
                    <option value="approved" @selected(old('status', $brand->status) === 'approved')>Approved</option>
                    <option value="rejected" @selected(old('status', $brand->status) === 'rejected')>Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Catatan Penolakan</label>
                <textarea name="rejection_note" rows="3" class="w-full border rounded-xl px-4 py-3">{{ old('rejection_note', $brand->rejection_note) }}</textarea>
            </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('manage.brands.index') }}" class="w-full text-center bg-gray-200 py-3 rounded-xl font-semibold">Batal</a>
            <button class="w-full bg-red-700 text-white py-3 rounded-xl font-semibold">Update</button>
        </div>
    </form>
</section>
@endsection