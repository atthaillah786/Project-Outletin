# Panduan Aksesibilitas UI — Outletin (WCAG 2.2 AA)

## 1. Warna yang Telah Disesuaikan

| Token | Asli | Baru | Perubahan |
|-------|------|------|-----------|
| `taupe` | `#7e6961` | `#6b5850` | Digelapkan +16% |
| `linen` | `#cbc0b2` | (tetap) | Hanya untuk bg/border |
| `oxblood` | `#550b14` | (tetap) | ✅ Kontras tinggi |
| `ink` | `#201717` | (tetap) | ✅ Kontras tinggi |

### Rasio Kontras (Setelah Penyesuaian)

| Foreground | Background | Rasio | Status |
|------------|-----------|-------|--------|
| taupe `#6b5850` | mist `#efeae4` | **5.1:1** | ✅ AA |
| taupe `#6b5850` | ivory `#f8f8f7` | **5.6:1** | ✅ AA |
| taupe `#6b5850` | linen `#cbc0b2` | 3.6:1 | ❌ Jangan dipakai |
| oxblood `#550b14` | ivory `#f8f8f7` | **14.6:1** | ✅ AAA |
| ink `#201717` | white `#ffffff` | **17.6:1** | ✅ AAA |

## 2. Aturan Pakai Warna

| Elemen | Warna | Keterangan |
|--------|-------|------------|
| Body text | `ink` | Teks paragraf, heading |
| Muted text | `taupe` | Label, helper text, breadcrumb |
| Tombol primer | `oxblood` di atas `white` | Gunakan `premium-button` |
| Tombol sekunder | `oxblood` border di atas `transparent` | Gunakan `premium-button-soft` |
| Background cards | `white` / `ivory` | Aman untuk semua teks |
| Background tabel | `white` dengan `mist` hover | Aman |
| Border | `linen` | Hanya border, bukan teks |

## 3. Pola yang WAJIB Diterapkan

### 3.1 Setiap Form Input

```blade
{{-- WAJIB: label eksplisit + aria-describedby + error message --}}
<label for="field-id" class="premium-label">Nama Field</label>
<input id="field-id" aria-describedby="field-id-error" class="premium-input">
@error('field')
    <p id="field-id-error" role="alert">...</p>
@enderror
```

### 3.2 Setiap Tombol Ikon

```blade
{{-- WAJIB: aria-label jika hanya ikon --}}
<button aria-label="Hapus">
    <svg aria-hidden="true">...</svg>
</button>
```

### 3.3 Setiap Link External

```blade
{{-- WAJIB: rel="noopener noreferrer" + indikator --}}
<a href="https://..." target="_blank" rel="noopener noreferrer">
    Buka <span class="sr-only">(tab baru)</span>
</a>
```

### 3.4 Setiap Alert/Notifikasi

```blade
{{-- SUCCESS: role="alert" --}}
<div class="premium-alert-success" role="alert">
    <svg aria-hidden="true">✅</svg>
    <span>Pesan sukses</span>
</div>

{{-- ERROR: role="alert" --}}
<div class="premium-alert-error" role="alert">
    <svg aria-hidden="true">❌</svg>
    <span>Pesan error</span>
</div>

{{-- INFO: role="status" (bukan alert biar gak interupsi) --}}
<div class="premium-alert-info" role="status">
    <svg aria-hidden="true">ℹ</svg>
    <span>Pesan info</span>
</div>
```

### 3.5 Setiap Tabel Data

```blade
{{-- WAJIB: <caption> + scope="col"/"row" --}}
<table class="premium-table">
    <caption class="sr-only">Deskripsi tabel</caption>
    <thead>
        <tr>
            <th scope="col">Nama</th>
            <th scope="col" class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">Baris 1</th>
            <td class="text-right">Rp 1000</td>
        </tr>
    </tbody>
</table>
```

### 3.6 Setiap Chart/Grafik

```blade
{{-- WAJIB: role="img" + aria-label + <figcaption> --}}
<figure>
    <canvas role="img" aria-label="Grafik batang income Juni 2026"></canvas>
    <figcaption>Grafik 1: Income Juni 2026 (data tersedia di tabel)</figcaption>
</figure>
```

### 3.7 Skip to Content Link

```blade
{{-- Letakkan sebagai elemen PERTAMA di dalam <body> --}}
<a href="#main-content" class="skip-to-content">
    Langsung ke konten utama
</a>

{{-- dan di <main> --}}
<main id="main-content">
    ...
</main>
```

## 4. Yang Perlu Dicek Manual

- [ ] Apakah semua `<img>` punya `alt` text?
- [ ] Apakah semua `aria-hidden="true"` digunakan dengan benar?
- [ ] Apakah navigasi keyboard berfungsi (Tab, Shift+Tab, Enter)?
- [ ] Apakah focus ring terlihat di semua elemen interaktif?
- [ ] Apakah pesan error terhubung ke input via `aria-describedby`?
- [ ] Apakah warna tidak menjadi satu-satunya indikator (misal: error pakai ikon + teks + warna)?