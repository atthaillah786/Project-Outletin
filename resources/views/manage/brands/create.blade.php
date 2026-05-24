@extends('layouts.dashboard')

@section('title', 'Tambah Brand - Outletin')

@section('content')
<section class="max-w-3xl mx-auto bg-white border rounded-2xl p-6 shadow-sm">
    <h1 class="text-3xl font-bold mb-6">Tambah Brand</h1>

    <form method="POST" action="{{ route('manage.brands.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        @if (auth()->user()->role === 'superadmin')
            <div>
                <label class="block text-sm font-semibold mb-2">Franchisor</label>
                <select name="franchisor_id" class="w-full border rounded-xl px-4 py-3" required>
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
            <label class="block text-sm font-semibold mb-2">Nama Brand</label>
            <input type="text" name="brand_name" value="{{ old('brand_name') }}" class="w-full border rounded-xl px-4 py-3" required>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Deskripsi</label>
            <textarea name="description" rows="5" class="w-full border rounded-xl px-4 py-3">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Logo Brand</label>
            <input type="file" name="logo" accept="image/*" class="w-full border rounded-xl px-4 py-3">
        </div>

        @if (auth()->user()->role === 'superadmin')
            <div>
                <label class="block text-sm font-semibold mb-2">Status</label>
                <select name="status" class="w-full border rounded-xl px-4 py-3">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('manage.brands.index') }}" class="w-full text-center bg-gray-200 py-3 rounded-xl font-semibold">Batal</a>
            <button class="w-full bg-red-700 text-white py-3 rounded-xl font-semibold">Simpan</button>
        </div>
    </form>
</section>
@endsection