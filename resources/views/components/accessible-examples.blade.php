{{-- 
╔═══════════════════════════════════════════════════════════════════════════════╗
║   CONTOH KOMPONEN AKSESIBEL (WCAG 2.2 AA)                                   ║
║   Copy-paste cuplikan yang Anda butuhkan ke view mana pun                   ║
╚═══════════════════════════════════════════════════════════════════════════════╝
--}}

{{-- ═══════════════════════════════════════════════════════════════════════════
     1. SKIP TO CONTENT LINK (letakkan di awal <body> setiap halaman) 
     ═══════════════════════════════════════════════════════════════════════════ --}}
<a href="#main-content" class="skip-to-content">
    Langsung ke konten utama
</a>

<main id="main-content">
    {{-- Konten halaman di sini --}}
</main>


{{-- ═══════════════════════════════════════════════════════════════════════════
     2. TOMBOL PRIMER — dengan ikon, kontras tinggi, focus ring jelas
     ═══════════════════════════════════════════════════════════════════════════ --}}
<button type="submit" class="premium-button">
    {{-- Ikon SVG inline — tidak pudar, skala sesuai font --}}
    <svg class="w-4 h-4 shrink-0" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    <span>Simpan Laporan</span>
</button>

{{-- Tombol dengan aria-label (jika ikon saja tanpa teks) --}}
<button type="button" class="premium-button" aria-label="Hapus data">
    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
    </svg>
</button>


{{-- ═══════════════════════════════════════════════════════════════════════════
     3. TOMBOL SEKUNDER (outline)
     ═══════════════════════════════════════════════════════════════════════════ --}}
<a href="{{ route('home') }}" class="premium-button-soft">
    <svg class="w-4 h-4 shrink-0" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    <span>Kembali</span>
</a>


{{-- ═══════════════════════════════════════════════════════════════════════════
     4. FORM INPUT — label eksplisit, pesan error jelas
     ═══════════════════════════════════════════════════════════════════════════ --}}
<div class="space-y-1">
    <label for="outlet-name" class="premium-label">
        Nama Outlet <span class="text-red-600" aria-hidden="true">*</span>
    </label>

    <input
        id="outlet-name"
        name="outlet_name"
        type="text"
        required
        aria-required="true"
        aria-describedby="outlet-name-hint outlet-name-error"
        class="premium-input @error('outlet_name') border-red-500 @enderror"
        placeholder="Masukkan nama outlet"
    >

    {{-- Hint text (opsional) --}}
    <p id="outlet-name-hint" class="text-xs text-taupe">
        Minimal 3 karakter. Gunakan nama yang unik.
    </p>

    {{-- Error message — terhubung via aria-describedby --}}
    @error('outlet_name')
        <p id="outlet-name-error" class="flex items-center gap-1.5 text-sm font-semibold text-red-700" role="alert">
            <svg class="w-4 h-4 shrink-0" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>


{{-- ═══════════════════════════════════════════════════════════════════════════
     5. SELECT DROPDOWN — aksesibel dengan label
     ═══════════════════════════════════════════════════════════════════════════ --}}
<div class="space-y-1">
    <label for="outlet-select" class="premium-label">Pilih Outlet</label>

    <select id="outlet-select" name="outlet_id" class="premium-input appearance-none">
        <option value="">— Semua Outlet —</option>
        <option value="1">Outlet Cabang 1</option>
        <option value="2">Outlet Cabang 2</option>
    </select>
</div>


{{-- ═══════════════════════════════════════════════════════════════════════════
     6. ALERT / NOTIFIKASI — dengan ikon untuk konteks visual
     ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- Sukses --}}
<div class="premium-alert-success" role="alert">
    <svg class="w-5 h-5 shrink-0 mt-0.5 text-emerald-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Laporan keuangan berhasil disimpan.</span>
</div>

{{-- Error --}}
<div class="premium-alert-error" role="alert">
    <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Terjadi kesalahan. Silakan coba lagi.</span>
</div>

{{-- Informasi --}}
<div class="premium-alert-info" role="status">
    <svg class="w-5 h-5 shrink-0 mt-0.5 text-blue-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Halaman ini dimuat dalam {{ number_format(microtime(true) - LARAVEL_START, 2) }} detik.</span>
</div>


{{-- ═══════════════════════════════════════════════════════════════════════════
     7. TABEL DATA — dengan <caption> untuk konteks
     ═══════════════════════════════════════════════════════════════════════════ --}}
<div class="overflow-x-auto">
    <table class="premium-table">
        <caption class="sr-only">
            Daftar outlet dan total pendapatan periode Juni 2026
        </caption>
        <thead>
            <tr>
                <th scope="col" class="p-3">Outlet</th>
                <th scope="col" class="p-3 text-right">Income</th>
                <th scope="col" class="p-3 text-right">Expense</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row" class="p-3 font-semibold text-ink">Outlet Cabang 1</th>
                <td class="p-3 text-right font-semibold">Rp 1.489.000</td>
                <td class="p-3 text-right">Rp 0</td>
            </tr>
        </tbody>
    </table>
</div>


{{-- ═══════════════════════════════════════════════════════════════════════════
     8. LINK — underline jelas, target blank aman
     ═══════════════════════════════════════════════════════════════════════════ --}}
<a href="{{ route('franchisor.financial.daily') }}" class="premium-link">
    Lihat Grafik Harian
</a>

{{-- Link ke external dengan peringatan --}}
<a href="https://example.com" target="_blank" rel="noopener noreferrer" class="premium-link">
    Buka di tab baru
    <span class="sr-only">(tautan eksternal, membuka tab baru)</span>
    <svg class="w-3.5 h-3.5 inline ml-1" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
    </svg>
</a>


{{-- ═══════════════════════════════════════════════════════════════════════════
     9. BADGE / LABEL STATUS
     ═══════════════════════════════════════════════════════════════════════════ --}}
<span class="premium-badge bg-emerald-100 text-emerald-800">
    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" aria-hidden="true"></span>
    Active
</span>

<span class="premium-badge bg-amber-100 text-amber-800">
    <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0" aria-hidden="true"></span>
    Pending
</span>

<span class="premium-badge bg-red-100 text-red-800">
    <span class="w-2 h-2 rounded-full bg-red-500 shrink-0" aria-hidden="true"></span>
    Rejected
</span>


{{-- ═══════════════════════════════════════════════════════════════════════════
     10. CHART AKSESIBEL — fallback teks untuk screen reader
     ═══════════════════════════════════════════════════════════════════════════ --}}
<figure>
    <div class="overflow-x-auto">
        <div class="min-w-[700px]">
            <canvas id="incomeChart" height="240" role="img" aria-label="Grafik batang income harian per outlet periode 14 Jun - 21 Jun 2026"></canvas>
        </div>
    </div>
    <figcaption class="mt-2 text-xs text-taupe">
        Grafik 1: Perbandingan income harian per outlet — periode 14–21 Juni 2026.
        Data tersedia dalam tabel di bawah.
    </figcaption>
</figure>