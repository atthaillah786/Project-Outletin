{{-- ═══════════════════════════════════════════════════════════════════════════════════════
    DOKUMENTASI LENGKAP SELURUH PROJECT — Outletin
    Per-folder, per-file: "Ini gunanya untuk apa, isinya apa, bisa ngapain aja"
    ═══════════════════════════════════════════════════════════════════════════════════════ --}}



{{-- ═══════════════════════════════════════════════════════════════════════════
    📁 STRUKTUR FOLDER UTAMA
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- ============================================================
    📁 app/
    ───────────────────────────────────────────────────────
    Folder utama tempat logic aplikasi (backend).
    Isinya: Models, Controllers, Policies, Providers.
    Kalau ada bug perhitungan, query, atau data — cari di sini.
    ============================================================ --}}

{{-- ============================================================
    📁 app/Models/
    ───────────────────────────────────────────────────────
    Fungsi: Representasi tabel database dalam bentuk Class PHP.
    Isinya: Relasi, casts, fillable — ATURAN bagaimana data
    tabel bisa diakses dan dimanipulasi.
    Bisa ngapain: Ubah relasi, tambah kolom, ubah tipe data.
    ============================================================ --}}

{{-- 📄 app/Models/Brand.php --}}
📄 Brand.php
→ FUNGSINYA untuk: Model tabel 'brands' — data brand/franchise
   Isinya: Relasi ke User (franchisor), relasi ke Outlet
   Bisa ngapain: Tambah kolom baru (misal logo brand, deskripsi)
   Kalau error "Brand not found" → cek Primary Key atau relasi di sini

{{-- 📄 app/Models/Outlet.php --}}
📄 Outlet.php
→ FUNGSINYA untuk: Model tabel 'outlets' — data outlet franchisee
   Isinya: Relasi ke Brand, User (franchisee), FinancialReport, Produk, Transaction
   Bisa ngapain: Ubah cara hitung total items, tambah field outlet
   PENTING: Relasi financialReports() dipakai di GRAFIK FRANCHISOR

{{-- 📄 app/Models/FinancialReport.php --}}
📄 FinancialReport.php
→ FUNGSINYA untuk: Model tabel 'financial_reports' — LAPORAN KEUANGAN HARIAN
   Isinya: Casts (report_date jadi Carbon), fillable, relasi ke Outlet
   Bisa ngapain: Ubah format tanggal, tambah kolom laporan
   PENTING: Casts['report_date'] BISA BIKIN BUG kalau pake keyBy()

{{-- 📄 app/Models/FranchiseBrand.php --}}
📄 FranchiseBrand.php
→ FUNGSINYA untuk: Model tabel pivot brand vs franchise
   Isinya: Relasi Brand dan User franchise
   Bisa ngapain: Atur status approval franchisee

{{-- 📄 app/Models/Transaction.php --}}
📄 Transaction.php
→ FUNGSINYA untuk: Model tabel 'transactions' — transaksi detail
   Isinya: Relasi ke Outlet dan User karyawan
   Bisa ngapain: Catat transaksi per-item (SAAT INI TIDAK DIPAKAI di grafik)

{{-- 📄 app/Models/Produk.php --}}
📄 Produk.php
→ FUNGSINYA untuk: Model tabel 'produk' — daftar produk brand
   Isinya: Nama produk, harga (Price), relasi ke Brand
   Bisa ngapain: Tambah produk, ubah harga — MENGARUHI hitungan income


{{-- ============================================================
    📁 app/Http/Controllers/
    ───────────────────────────────────────────────────────
    Fungsi: Otak aplikasi — semua logika bisnis ada di sini.
    Setiap file = 1 Controller yang nanganin 1 grup fitur.
    Bisa ngapain: Ubah cara hitung, ubah query, ubah filtering.
    KALAU GRAFIK KOSONG atau DATA SALAH → cek CONTROLLER dulu.
    ============================================================ --}}

{{-- 📄 FranchisorFinancialController.php --}}
📄 FranchisorFinancialController.php
→ FUNGSINYA untuk: MENAMPILKAN DATA KEUANGAN ke FRANCHISOR
   Isinya 2 method:
   1. todayPerOutlet()   → "Pendapatan Hari Ini" (summary + bar chart + tabel)
   2. dailyPerOutlet()   → "Grafik Harian" (bar chart + line chart + ranking)
   Bisa ngapain: Ubah DEFAULT tanggal grafik, ubah warna chart,
                 ubah cara urut ranking, tambah filter baru
   TEMPAT YANG SERING DIEDIT kalau data grafik salah

{{-- 📄 BrandFinancialController.php --}}
📄 BrandFinancialController.php
→ FUNGSINYA untuk: Laporan keuangan PER BRAND (halaman brand_report)
   Isinya 2 method:
   1. index()    → Tampilkan chart + tabel per brand
   2. download() → Download CSV laporan brand
   Bisa ngapain: Ubah filter, ubah format CSV, ubah range tanggal default

{{-- 📄 FranchisorDashboardController.php --}}
📄 FranchisorDashboardController.php
→ FUNGSINYA untuk: DASHBOARD UTAMA franchisor (halaman pertama login)
   Isinya 3 method:
   1. index()              → Tampilkan brand, outlet, grafik bulanan
   2. approveApplication() → Setujui pengajuan outlet
   3. rejectApplication()  → Tolak pengajuan outlet
   Bisa ngapain: Ubah tampilan dashboard, tambah statistik baru

{{-- 📄 FranchiseeDashboardController.php --}}
📄 FranchiseeDashboardController.php
→ FUNGSINYA untuk: DASHBOARD UTAMA franchisee (pemilik outlet)
   Isinya: Daftar outlet milik franchisee, status approval
   Bisa ngapain: Ubah tampilan dashboard franchisee

{{-- 📄 OutletFinancialReportController.php --}}
📄 OutletFinancialReportController.php
→ FUNGSINYA untuk: INPUT & EDIT LAPORAN KEUANGAN (oleh franchisee)
   Isinya 4 method:
   1. create()  → Form input laporan baru
   2. store()   → Simpan laporan (dengan CEK DUPLIKAT)
   3. edit()    → Form edit laporan yang sudah ada
   4. update()  → Update laporan yang diedit
   5. outletProducts() → API ambil produk via AJAX
   Bisa ngapain: Ubah cara hitung total income, ubah validasi

{{-- 📄 OutletManagerController.php --}}
📄 OutletManagerController.php
→ FUNGSINYA untuk: Manajemen outlet — weekly trend
   Isinya: weeklyTrend() → grafik mingguan satu outlet

{{-- 📄 BrandRegistrationController.php --}}
📄 BrandRegistrationController.php
→ FUNGSINYA untuk: PENDAFTARAN BRAND baru oleh franchisor
   Isinya: create() → tampilkan form, store() → simpan brand baru

{{-- 📄 BrandCrudController.php --}}
📄 BrandCrudController.php (di resource)
→ FUNGSINYA untuk: CRUD Brand (Create, Read, Update, Delete)
   Bisa ngapain: Tambah/edit/hapus brand dari panel manajemen

{{-- 📄 OutletCrudController.php --}}
📄 OutletCrudController.php (di resource)
→ FUNGSINYA untuk: CRUD Outlet (Create, Read, Update, Delete)
   Bisa ngapain: Tambah/edit/hapus outlet dari panel manajemen

{{-- 📄 ProdukCrudController.php --}}
📄 ProdukCrudController.php (di resource)
→ FUNGSINYA untuk: CRUD Produk (Create, Read, Update, Delete)
   Bisa ngapain: Tambah/edit/hapus produk, ubah harga

{{-- 📄 AuthController.php --}}
📄 AuthController.php
→ FUNGSINYA untuk: LOGIN & REGISTRASI user
   Isinya: showLogin, login, showRegister, register, logout
   Bisa ngapain: Ubah aturan login, tambah validasi

{{-- 📄 SuperadminDashboardController.php --}}
📄 SuperadminDashboardController.php
→ FUNGSINYA untuk: Dashboard SUPERADMIN
   Isinya: Verifikasi brand, manage semua data

{{-- 📄 SuperadminBrandVerificationController.php --}}
📄 SuperadminBrandVerificationController.php
→ FUNGSINYA untuk: Verifikasi brand oleh superadmin
   Isinya: Approve/reject brand baru


{{-- ============================================================
    📁 routes/web.php
    ───────────────────────────────────────────────────────
    FUNGSINYA untuk: DAFTAR SEMUA URL/ENDPOINT aplikasi.
    Isinya: Route::get(), Route::post(), Route::put()
    Bisa ngapain: Tambah halaman baru, ubah URL, ubah method.
    KALAU "Route not found" → cek SINI dulu.
    ============================================================ --}}

📄 web.php
→ FUNGSINYA untuk: Mendefinisikan SEMUA rute/URL aplikasi
   Dibagi jadi beberapa bagian:
   1. PUBLIC ROUTES → halaman home, tentang, outlet (tanpa login)
   2. AUTH ROUTES → login, register, logout
   3. DASHBOARD REDIRECT → setelah login, arahkan sesuai role
   4. AUTHENTICATED ROUTES (butuh login):
      a. SUPERADMIN → dashboard + verifikasi brand
      b. FRANCHISOR → dashboard + approve outlet + laporan keuangan
         → Route khusus: /dashboard/franchisor/harian (GRAFIK HARIAN)
         → Route khusus: /dashboard/franchisor/outlets-today (HARI INI)
      c. FRANCHISEE → dashboard + apply outlet + input laporan
         → Route khusus: /dashboard/franchisee/financial-report/create (INPUT)
         → Route khusus: /dashboard/franchisee/financial-report/{id}/edit (EDIT)
      d. CRUD MANAGEMENT → brands, outlets, produk
   Bisa ngapain:
   - TAMBAH MENU BARU: tambah Route::get() baru
   - UBAH URL: ganti '/dashboard/franchisor/harian' jadi '/grafik'
   - TAMBAH MIDDLEWARE: kasih proteksi halaman


{{-- ============================================================
    📁 resources/views/
    ───────────────────────────────────────────────────────
    Fungsi: TAMPILAN / HTML — apa yang dilihat user.
    File .blade.php = template Laravel (HTML + PHP).
    Bisa ngapain: Ubah teks, warna, layout, tambah tombol,
    ubah tabel, ubah grafik — SEMUA TAMPILAN ADA DI SINI.
    ============================================================ --}}

{{-- 📁 resources/views/layouts/ --}}
📁 layouts/
→ FUNGSINYA untuk: TEMPLATE INDUK — semua halaman pakai layout ini
   Isinya:
   - dashboard.blade.php → layout utama setelah login
                          (navbar, alert sukses/error, footer)
   - auth.blade.php → layout halaman login/register
   Bisa ngapain:
   - TAMBAH MENU NAVBAR: tambah <a href=""> di bagian franchisor/franchisee
   - UBAH WARNA NAVBAR: edit class Tailwind di <nav>
   - TAMBAH ALERT BARU: tambah @if(session('warning')) di <main>
   - UBAH FOOTER: edit teks di bagian <footer>

{{-- 📁 resources/views/dashboard/ --}}
📁 dashboard/
→ FUNGSINYA untuk: Halaman-halaman dashboard (setelah login)

📄 franchisor.blade.php
→ FUNGSINYA untuk: Dashboard utama FRANCHISOR
   Isinya: Ringkasan brand, daftar pengajuan outlet, grafik bulanan
   Bisa ngapain: Ubah ringkasan, tambah statistik, ubah warna grafik

📄 franchisee.blade.php
→ FUNGSINYA untuk: Dashboard utama FRANCHISEE
   Isinya: Daftar outlet milik franchisee, status

📄 superadmin.blade.php
→ FUNGSINYA untuk: Dashboard SUPERADMIN
   Isinya: Daftar brand, user, verifikasi

📄 franchisor_outlets_today.blade.php
→ FUNGSINYA untuk: Halaman "PENDAPATAN HARI INI"
   Isinya: Summary cards + Bar Chart + Tabel rincian
   Bisa ngapain: Ubah warna chart, tambah kolom tabel, ubah format Rupiah

📄 franchisor_daily_transactions.blade.php
→ FUNGSINYA untuk: Halaman "GRAFIK HARIAN"
   Isinya: Filter form + Summary cards + Income Bar Chart + Expense Line Chart
           + Tabel Ranking + Mini Cards
   Bisa ngapain: Ubah chart type, ubah filter, ubah ranking

📄 franchisee-apply-outlet.blade.php
→ FUNGSINYA untuk: Form pengajuan outlet baru oleh franchisee

📄 franchisee-produk.blade.php
→ FUNGSINYA untuk: Daftar produk brand (dilihat franchisee)

📄 outlet_weekly.blade.php
→ FUNGSINYA untuk: Grafik mingguan SATU outlet

{{-- 📁 resources/views/brand/ --}}
📁 brand/
→ FUNGSINYA untuk: Halaman manajemen BRAND
   Isinya: 4 file CRUD lengkap

📄 index.blade.php
→ FUNGSINYA untuk: DAFTAR SEMUA BRAND
   Isinya: Tabel semua brand (nama, status, action)
   Bisa ngapain: Tambah kolom (misal logo), ubah tombol action

📄 create.blade.php
→ FUNGSINYA untuk: FORM TAMBAH BRAND BARU
   Isinya: Input form (nama brand, deskripsi, dll)
   Bisa ngapain: Tambah field input baru, ubah validasi

📄 edit.blade.php
→ FUNGSINYA untuk: FORM EDIT BRAND
   Isinya: Form yang sudah terisi data lama
   Bisa ngapain: Sama seperti create, tapi untuk update

📄 register.blade.php
→ FUNGSINYA untuk: Halaman PENDAFTARAN BRAND (oleh franchisor)
   Isinya: Form kirim brand ke superadmin untuk diverifikasi

{{-- 📁 resources/views/financial/ --}}
📁 financial/
→ FUNGSINYA untuk: Halaman laporan keuangan

📄 brand_report.blade.php
→ FUNGSINYA untuk: LAPORAN KEUANGAN PER BRAND (lengkap)
   Isinya: Filter outlet, filter tanggal, line chart income,
           tabel ranking, kartu ringkasan
   Bisa ngapain: Ubah chart type, tambah filter, download CSV

📄 create.blade.php
→ FUNGSINYA untuk: FORM INPUT/EDIT LAPORAN KEUANGAN (oleh franchisee)
   Isinya: Pilih outlet, tanggal, isi qty produk, expense
           → MODE EDIT jika sudah ada laporan sebelumnya
   Bisa ngapain: Ubah form, tambah field, ubah cara hitung

{{-- 📁 resources/views/components/ --}}
📁 components/
→ FUNGSINYA untuk: File-FILE BANTU (dokumentasi, panduan, dll)
   Isinya: BUKAN halaman yang tampil — cuma referensi code

📄 penjelasan-code-franchisor.blade.php
📄 penjelasan-lengkap-project.blade.php (FILE INI)
📄 accessible-examples.blade.php
📄 accessibility-guide.md
→ FUNGSINYA untuk: Dokumentasi kode & panduan aksesibilitas
   Bisa ngapain: BACA SAJA — tidak mempengaruhi tampilan

{{-- 📄 resources/views/home.blade.php --}}
📄 home.blade.php
→ FUNGSINYA untuk: Halaman DEPAN / LANDING PAGE (sebelum login)
   Bisa ngapain: Ubah teks hero, gambar, tombol CTA

{{-- 📄 resources/views/about.blade.php --}}
📄 about.blade.php
→ FUNGSINYA untuk: Halaman TENTANG / ABOUT

{{-- 📄 resources/views/outlet.blade.php --}}
📄 outlet.blade.php
→ FUNGSINYA untuk: Halaman publik daftar outlet


{{-- ============================================================
    📁 resources/css/app.css
    ───────────────────────────────────────────────────────
    FUNGSINYA untuk: SEMUA STYLING CSS aplikasi (Tailwind v4).
    Isinya: @theme (definisi warna), @layer components (class kustom),
    @layer utilities, focus ring, skip-to-content, alert styles.
    Bisa ngapain: Ubah WARNA THEME (oxblood, taupe, dll),
    ubah shadow, ubah radius tombol, tambah class kustom baru.
    KALAU WARNA TIDAK SESUAI → edit di SINI.
    ============================================================ --}}

📄 app.css
→ FUNGSINYA untuk: Definisi warna tema & class komponen
   Bagian-bagian:
   1. @theme → --color-oxblood, --color-taupe, --color-ivory, dll
   2. @layer base → body background, selection, focus-visible, skip-to-content
   3. @layer components → premium-button, premium-input, premium-table,
      premium-alert-success/error/info, premium-link, premium-label
   4. @layer utilities → reveal-on-scroll, sr-only, premium-glow
   Bisa ngapain:
   - UBAH WARNA: ganti kode hex di @theme
   - UBAH TOMBOL: edit class di .premium-button
   - TAMBAH ALERT BARU: copy premium-alert-success, ganti warna
   - TAMBAH UTILITY BARU: tambah di @layer utilities


{{-- ============================================================
    📁 database/migrations/
    ───────────────────────────────────────────────────────
    FUNGSINYA untuk: RIWAYAT PERUBAHAN STRUKTUR DATABASE.
    Setiap file = 1 skema tabel yang pernah dibuat.
    Bisa ngapain: Lihat kolom tabel, lihat foreign key, lihat tipe data.
    KALAU ERROR "Column not found" → cek MIGRASI dulu.
    ============================================================ --}}

📄 2026_05_18_143634_create_brands_table.php
→ FUNGSINYA untuk: Buat tabel 'brands'
   Kolom: brand_id, franchisor_id, brand_name, status, created_at

📄 2026_05_18_143634_create_produk_table.php
→ FUNGSINYA untuk: Buat tabel 'produk'
   Kolom: produk_id, brand_id, produk_name, Price

📄 2026_05_18_143637_create_transactions_table.php
→ FUNGSINYA untuk: Buat tabel 'transactions'
   Kolom: transaction_id, outlet_id, karyawan_id, type, amount, description, transaction_date

📄 2026_06_21_064212_add_avatar_to_users_table.php
→ FUNGSINYA untuk: Tambah kolom 'avatar' di tabel users


{{-- ============================================================
    ⚠️ PANDUAN: KALAU ERROR, CEK DIMANA?
    ═══════════════════════════════════════════════════════
    ============================================================ --}}

{{-- ERROR: Halaman 403 (Forbidden) --}}
❌ Error 403 saat akses halaman
→ LOKASI: Controller — method cek role ($user->role !== 'franchisor')
→ CEK:   app/Http/Controllers/{Controller}
→ SOLUSI: Pastikan login dengan role yang benar

{{-- ERROR: Halaman 404 (Not Found) --}}
❌ Error 404 saat akses URL
→ LOKASI: routes/web.php — Route tidak terdaftar
→ CEK:   routes/web.php
→ SOLUSI: Tambah Route::get() baru

{{-- ERROR: Grafik kosong / tidak ada data --}}
❌ Grafik kosong
→ LOKASI: Controller — dailyPerOutlet() atau todayPerOutlet()
→ CEK:   FranchisorFinancialController.php atau BrandFinancialController.php
→ SOLUSI: Cek apakah data di financial_reports ada untuk periode tsb

{{-- ERROR: Perhitungan salah --}}
❌ Total Items / Income salah
→ LOKASI: Controller — withSum atau perhitungan di store()
→ CEK:   OutletFinancialReportController@store atau FranchisorFinancialController
→ SOLUSI: Cek cara hitung totalItems = sum(qty)

{{-- ERROR: Tampilan berantakan --}}
❌ Tampilan HTML kacau / warna salah
→ LOKASI: resources/views/ (Blade) atau resources/css/app.css
→ CEK:   File .blade.php yang bersangkutan
→ SOLUSI: Edit HTML atau class Tailwind di view

{{-- ERROR: Data tidak muncul di dropdown --}}
❌ Dropdown outlet / brand kosong
→ LOKASI: Controller → query yang ngirim data ke view
→ CEK:   OutletFinancialReportController@create atau yang relevan
→ SOLUSI: Cek $outlets = Outlet::where(...)->get()

{{-- ERROR: Route not found --}}
❌ Route [name] not found
→ LOKASI: routes/web.php
→ CEK:   Apakah route name ada di daftar
→ SOLUSI: php artisan route:list | grep "nama_route"

{{-- ERROR: Database / Query --}}
❌ SQL error / column not found
→ LOKASI: database/migrations/ atau query di Controller
→ CEK:   Migrasi terakhir atau query di controller
→ SOLUSI: Cek nama kolom di tabel database


{{-- ============================================================
    📋 RINGKASAN: SIAPA NGERJAIN APA DI FILE MANA
    ═══════════════════════════════════════════════════════
    ============================================================ --}}

{{-- KALAU MAU: --}}
{{-- Ubah warna tema → edit resources/css/app.css (bagian @theme) --}}
{{-- Tambah halaman baru → edit routes/web.php + bikin view baru --}}
{{-- Betulin grafik → edit app/Http/Controllers/FranchisorFinancialController.php --}}
{{-- Betulin input laporan → edit app/Http/Controllers/OutletFinancialReportController.php --}}
{{-- Ubah tampilan tabel → edit resources/views/dashboard/franchisor_outlets_today.blade.php --}}
{{-- Tambah menu navbar → edit resources/views/layouts/dashboard.blade.php --}}
{{-- Tambah kolom database → bikin migration baru + update model --}}