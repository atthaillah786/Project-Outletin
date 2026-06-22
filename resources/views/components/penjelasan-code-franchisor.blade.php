{{-- ═══════════════════════════════════════════════════════════════════════════
    DOKUMENTASI CODE — FRANCHISOR / PEMILIK BRAND (Outletin)
    Per-file, per-baris: "KODE → fungsinya untuk..."
    ═══════════════════════════════════════════════════════════════════════════ --}}



{{-- ═══════════════════════════════════════════════════════════════════════════
    FILE 1: app/Http/Controllers/FranchisorFinancialController.php
    ───────────────────────────────────────────────────────────────────────
    Controller untuk menampilkan data keuangan harian. Isinya 2 method:
    1) todayPerOutlet() → "Pendapatan Hari Ini"
    2) dailyPerOutlet() → "Grafik Harian"
    ═══════════════════════════════════════════════════════════════════════════ --}}


{{-- ============================================================
    METHOD 1: todayPerOutlet() — PENDAPATAN HARI INI
    ============================================================ --}}

{{-- KODE --}}
public function todayPerOutlet(Request $request)
→ FUNGSINYA untuk: Method utama halaman "Pendapatan Hari Ini"
   Memanggil view 'dashboard.franchisor_outlets_today'
   Hanya bisa diakses oleh role 'franchisor'

{{-- KODE --}}
$user = Auth::user();
if (!$user || $user->role !== 'franchisor') { abort(403, ...); }
→ FUNGSINYA untuk: Cek apakah user sudah login DAN role-nya 'franchisor'
   Baris 16-19 di file asli
   abort(403) → tampilkan halaman error "Forbidden" jika bukan franchisor
   → PENTING: mencegah franchisee atau superadmin lihat data franchisor

{{-- KODE --}}
$today = Carbon::today();
→ FUNGSINYA untuk: Ambil tanggal hari ini dari server
   $today = object Carbon, contoh: 2026-06-21 00:00:00
   Dipakai untuk filter laporan yang tanggalnya hari ini aja

{{-- KODE --}}
$outlets = Outlet::whereHas('brand', function($q) use ($user) {
    $q->where('franchisor_id', $user->user_id)->where('status', 'approved');
})
→ FUNGSINYA untuk: Ambil semua outlet milik franchisor yang login
   whereHas('brand', ...) → "hanya ambil outlet yang brand-nya memenuhi syarat"
   Syarat: brand.franchisor_id = user_id franchisor DAN brand.status = 'approved'
   → Jadi outlet milik BRAND ORANG LAIN tidak akan muncul

{{-- KODE --}}
->withSum(['financialReports as today_total_items' => function($q) use ($today) {
    $q->whereDate('report_date', $today);
}], 'total_items')
→ FUNGSINYA untuk: Hitung TOTAL BARANG TERJUAL hari ini untuk setiap outlet
   withSum() → Laravel otomatis bikin SUM query
   'financialReports as today_total_items' → nama properti hasilnya: $outlet->today_total_items
   whereDate('report_date', $today) → hanya laporan tanggal hari ini
   Contoh: Outlet Cabang 1 lapor 5 item + 3 item → today_total_items = 8

{{-- KODE --}}
->withSum(['financialReports as today_total_income' => ...], 'total_income')
→ FUNGSINYA untuk: Hitung TOTAL PENDAPATAN hari ini
   Sama seperti di atas, tapi jumlahkan kolom total_income
   Hasilnya: $outlet->today_total_income

{{-- KODE --}}
->withSum(['financialReports as today_total_expense' => ...], 'total_expense')
→ FUNGSINYA untuk: Hitung TOTAL PENGELUARAN hari ini
   Hasilnya: $outlet->today_total_expense

{{-- KODE --}}
->orderBy('outlet_name')->get();
→ FUNGSINYA untuk: Urutkan outlet A-Z berdasarkan nama, lalu jalankan query
   get() → eksekusi query, hasilnya Collection of Outlet objects

{{-- KODE --}}
$labels = $outlets->pluck('outlet_name')->toArray();
→ FUNGSINYA untuk: Siapkan array nama outlet untuk sumbu X grafik
   pluck('outlet_name') → ambil kolom outlet_name aja dari semua outlet
   toArray() → ubah dari Collection ke array biasa PHP
   Contoh hasil: ['Outlet Cabang 1', 'Outlet Cabang 2']

{{-- KODE --}}
$incomeData = $outlets->map(fn($o) => (float) ($o->today_total_income ?? 0))->toArray();
→ FUNGSINYA untuk: Siapkan array angka income untuk grafik batang
   map(fn($o) => ...) → untuk setiap outlet, ambil today_total_income
   ?? 0 → jika null (belum ada laporan), pakai 0
   (float) → pastikan formatnya angka desimal
   Contoh hasil: [658000, 739000]

{{-- KODE --}}
$expenseData = $outlets->map(fn($o) => (float) ($o->today_total_expense ?? 0))->toArray();
→ FUNGSINYA untuk: Siapkan array angka expense untuk grafik batang
   Sama seperti di atas, tapi ambil today_total_expense
   Contoh hasil: [0, 0]

{{-- KODE --}}
$tableRows = $outlets->map(fn($o) => [
    'outlet_name' => $o->outlet_name,
    'total_items' => (int) ($o->today_total_items ?? 0),
    'total_income' => (float) ($o->today_total_income ?? 0),
    'total_expense' => (float) ($o->today_total_expense ?? 0),
    'total_profit' => (float) (($o->today_total_income ?? 0) - ($o->today_total_expense ?? 0)),
])->toArray();
→ FUNGSINYA untuk: Siapkan data tabel rincian per outlet
   Setiap baris = 1 array asosiatif dengan 5 kolom:
   - outlet_name     → nama outlet
   - total_items     → jumlah barang terjual (integer)
   - total_income    → total pendapatan (float)
   - total_expense   → total pengeluaran (float)
   - total_profit    → LABA = income - expense (float)

{{-- KODE --}}
return view('dashboard.franchisor_outlets_today', compact('labels', 'incomeData', 'expenseData', 'tableRows'));
→ FUNGSINYA untuk: Kirim 4 variabel ke Blade view
   compact() → bikin array ['labels' => $labels, 'incomeData' => $incomeData, ...]
   View akan render: summary cards, bar chart, tabel rincian


{{-- ============================================================
    METHOD 2: dailyPerOutlet() — GRAFIK HARIAN
    ============================================================ --}}

{{-- KODE --}}
public function dailyPerOutlet(Request $request)
→ FUNGSINYA untuk: Method utama halaman "Grafik Harian"
   Menampilkan perbandingan outlet dalam RENTANG TANGGAL
   Output: view 'dashboard.franchisor_daily_transactions'

{{-- KODE --}}
$startDate = $request->query('start_date')
    ? Carbon::parse($request->query('start_date'))->startOfDay()
    : Carbon::today()->subDays(6);
→ FUNGSINYA untuk: Ambil parameter start_date dari URL, atau pakai DEFAULT
   $request->query('start_date') → ambil ?start_date=2026-06-14 dari URL
   Carbon::parse(...) → ubah string jadi object Carbon
   ->startOfDay() → set jam ke 00:00:00
   Carbon::today()->subDays(6) → DEFAULT: 7 hari yang lalu
   Contoh URL: /dashboard/franchisor/harian?start_date=2026-06-14&end_date=2026-06-21

{{-- KODE --}}
$endDate = $request->query('end_date')
    ? Carbon::parse($request->query('end_date'))->endOfDay()
    : Carbon::today()->endOfDay();
→ FUNGSINYA untuk: Ambil parameter end_date dari URL, atau pakai DEFAULT
   endOfDay() → set jam ke 23:59:59
   DEFAULT: hari ini jam 23:59:59

{{-- KODE --}}
if ($startDate->greaterThan($endDate)) {
    [$startDate, $endDate] = [$endDate, $startDate];
}
→ FUNGSINYA untuk: Proteksi jika user salah urut tanggal
   Contoh: start=21 Juni, end=14 Juni → otomatis ditukar
   Jadi start=14 Juni, end=21 Juni → tidak error

{{-- KODE --}}
$outlets = Outlet::whereHas('brand', function ($q) use ($user) {
    $q->where('franchisor_id', $user->user_id)->where('status', 'approved');
})->orderBy('outlet_name')->get();
→ FUNGSINYA untuk: Ambil semua outlet approved milik franchisor
   Sama persis seperti di method 1

{{-- KODE --}}
$days = $startDate->diffInDays($endDate) + 1;
→ FUNGSINYA untuk: Hitung jumlah hari dalam rentang tanggal
   diffInDays() → selisih hari antara start dan end
   + 1 → termasuk hari pertama dan terakhir
   Contoh: 14 Juni - 21 Juni → 7 + 1 = 8 hari

{{-- KODE --}}
for ($i = 0; $i < $days; $i++) {
    $current = $startDate->copy()->addDays($i);
    $dateLabels[] = $current->format('d M');
    $dateKeys[] = $current->toDateString();
}
→ FUNGSINYA untuk: Buat array tanggal untuk sumbu X grafik
   copy() → duplikat object Carbon biar original tidak berubah
   addDays($i) → tambah i hari
   $dateLabels[] → untuk DITAMPILKAN di grafik: "14 Jun", "15 Jun", ...
   $dateKeys[]   → untuk LOOKUP data: "2026-06-14", "2026-06-15", ...

{{-- KODE --}}
$reports = FinancialReport::whereIn('outlet_id', $outletIds)
    ->whereBetween('report_date', [$startDate->toDateString(), $endDate->toDateString()])
    ->orderBy('report_date')
    ->get();
→ FUNGSINYA untuk: Ambil semua laporan keuangan dalam rentang tanggal
   whereIn('outlet_id', $outletIds) → hanya outlet milik franchisor
   whereBetween('report_date', [...]) → filter tanggal (pakai string YYYY-MM-DD)
   toDateString() → PENTING: ubah Carbon ke string biar whereBetween works
   orderBy('report_date') → urut dari tanggal lama ke baru

{{-- KODE --}}
$reportLookup = [];
foreach ($reports->where('outlet_id', $outlet->outlet_id) as $r) {
    $reportLookup[$r->report_date->toDateString()] = $r;
}
→ FUNGSINYA untuk: Buat lookup table per outlet dengan KEY string tanggal
   INI ADALAH FIX UNTUK CARBON CAST BUG!
   Masalah: $r->report_date adalah Carbon object (karena casts di model)
            String "2026-06-21" TIDAK SAMA dengan Carbon object
            Jadi isset($array["2026-06-21"]) selalu FALSE
   Solusi: panggil ->toDateString() → jadi string "2026-06-21"
           Baru dipakai sebagai key → isset() berfungsi normal
   Contoh hasil: ["2026-06-19" => {report}, "2026-06-20" => {report}]

{{-- KODE --}}
foreach ($dateKeys as $key) {
    if (isset($reportLookup[$key])) {
        $incomeData[] = (float) $reportLookup[$key]->total_income;
        $expenseData[] = (float) $reportLookup[$key]->total_expense;
    } else {
        $incomeData[] = 0;
        $expenseData[] = 0;
    }
}
→ FUNGSINYA untuk: Cocokkan data laporan dengan setiap tanggal
   Untuk setiap tanggal (dari dateKeys):
   - Jika ADA laporan di lookup → ambil total_income & total_expense
   - Jika TIDAK ADA → isi 0 (outlet tutup / tidak lapor)
   Hasil: array sepanjang jumlah hari, misal [50000, 0, 75000, ...]

{{-- KODE --}}
$palettes = [
    ['bg' => 'rgba(54, 162, 235, 0.75)',  'border' => 'rgba(54, 162, 235, 1)'],
    ['bg' => 'rgba(255, 99, 132, 0.75)',  'border' => 'rgba(255, 99, 132, 1)'],
    ...
];
$p = $palettes[$index % count($palettes)];
→ FUNGSINYA untuk: Siapkan 8 warna UNIK untuk setiap outlet
   Setiap outlet punya warna berbeda:
   0: biru, 1: merah, 2: kuning, 3: hijau, 4: ungu, 5: oranye, 6: hijau terang, 7: pink
   $index % 8 → jika outlet > 8, warna akan repeat dari awal

{{-- KODE --}}
$incomeDatasets[] = [
    'label' => $outlet->outlet_name,
    'data' => $incomeData,
    'backgroundColor' => $p['bg'],
    'borderColor' => $p['border'],
    'borderWidth' => 1,
];
→ FUNGSINYA untuk: Siapkan dataset INCOME untuk Chart.js
   'label' → nama outlet, muncul di legend chart
   'data' → array angka income per tanggal
   'backgroundColor' → warna batang (dari palet)
   'borderColor' → warna tepi batang

{{-- KODE --}}
$expenseDatasets[] = [
    'label' => $outlet->outlet_name,
    'data' => $expenseData,
    'borderColor' => $p['border'],
    'borderWidth' => 2,
    'tension' => 0.3,
    'pointRadius' => 4,
    'fill' => false,
];
→ FUNGSINYA untuk: Siapkan dataset EXPENSE untuk Chart.js (line chart)
   'tension' => 0.3 → garis agak melengkung (smooth curve)
   'pointRadius' => 4 → ada titik bulat di setiap data
   'fill' => false → tanpa area warna di bawah garis

{{-- KODE --}}
$outletTotals = [];
foreach ($outlets as $outlet) {
    $outletReports = $reports->where('outlet_id', $outlet->outlet_id);
    $income = (float) $outletReports->sum('total_income');
    $expense = (float) $outletReports->sum('total_expense');
    $items = (int) $outletReports->sum('total_items');
    $profit = $income - $expense;
    $outletTotals[] = [ ... ];
}
→ FUNGSINYA untuk: Hitung TOTAL per outlet untuk ranking
   BUKAN per hari, tapi AKUMULASI seluruh periode
   $items = total barang terjual (sum total_items)
   $profit = income - expense (rumus laba)

{{-- KODE --}}
usort($outletTotals, fn($a, $b) => $b['total_income'] <=> $a['total_income']);
→ FUNGSINYA untuk: Urutkan outlet dari income TERBESAR ke TERKECIL
   $b <=> $a → descending (besar ke kecil)
   $a <=> $b → ascending (kecil ke besar)
   Hasil: posisi 1 = outlet dengan pendapatan tertinggi

{{-- KODE --}}
$grandIncome = array_sum(array_column($outletTotals, 'total_income'));
→ FUNGSINYA untuk: Hitung TOTAL INCOME semua outlet
   array_column($array, 'total_income') → ambil semua nilai total_income
   array_sum(...) → jumlahkan semua
   $grandIncome, $grandExpense, $grandProfit untuk summary cards

{{-- KODE --}}
return view('dashboard.franchisor_daily_transactions', compact(
    'dateLabels', 'incomeDatasets', 'expenseDatasets', 'outletTotals',
    'grandIncome', 'grandExpense', 'grandProfit', 'startDate', 'endDate', 'outlets'
));
→ FUNGSINYA untuk: Kirim 10 variabel ke Blade view
   View akan render: filter form, summary cards, 2 chart, tabel ranking, mini cards



{{-- ═══════════════════════════════════════════════════════════════════════════
    FILE 2: app/Http/Controllers/BrandFinancialController.php
    ───────────────────────────────────────────────────────────────────────
    Controller laporan keuangan PER BRAND. Bisa filter per outlet & tanggal.
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- KODE --}}
$brand = Brand::findOrFail($brandId);
if ($brand->franchisor_id !== $user->user_id) { abort(403); }
→ FUNGSINYA untuk: Cari brand berdasarkan URL, lalu cek kepemilikan
   findOrFail($brandId) → cari ID brand dari URL, jika tidak ada → 404
   Cek kepemilikan: pastikan brand ini milik franchisor yang login
   → Mencegah franchisor A lihat data brand milik franchisor B

{{-- KODE --}}
$start = $startDate ? Carbon::parse($startDate)->startOfDay()
                    : Carbon::now()->startOfMonth();
→ FUNGSINYA untuk: Ambil start_date dari URL, DEFAULT = awal bulan ini
   startOfMonth() → tanggal 1 bulan ini jam 00:00:00

{{-- KODE --}}
$end = $endDate ? Carbon::parse($endDate)->endOfDay()
                : Carbon::now()->endOfMonth();
→ FUNGSINYA untuk: Ambil end_date dari URL, DEFAULT = akhir bulan ini

{{-- KODE --}}
$outletsQuery = Outlet::where('brand_id', $brandId);
if ($outletId) { $outletsQuery->where('outlet_id', $outletId); }
$outlets = $outletsQuery->get();
→ FUNGSINYA untuk: Ambil outlet milik brand (bisa filter per outlet)
   Jika user pilih outlet tertentu → tampilkan 1 outlet aja
   Jika tidak → tampilkan SEMUA outlet brand ini

{{-- KODE --}}
FinancialReport::whereIn('outlet_id', $outlets->pluck('outlet_id'))
    ->whereBetween('report_date', [$start->toDateString(), $end->toDateString()])
    ->orderBy('report_date')->get();
→ FUNGSINYA untuk: Ambil laporan keuangan di rentang tanggal
   whereIn + whereBetween → filter outlet & tanggal

{{-- KODE --}}
$reportLookup = [];
foreach ($reports->where('outlet_id', $outlet->outlet_id) as $r) {
    $reportLookup[$r->report_date->toDateString()] = $r;
}
→ FUNGSINYA untuk: Sama seperti dailyPerOutlet — fix Carbon Cast Bug
   Key pakai ->toDateString() biar string cocok

{{-- KODE (download method) --}}
$filename = 'financial_report_brand_' . $brandId . '_' . date('Ymd') . '.csv';
→ FUNGSINYA untuk: Buat nama file CSV untuk download
   Contoh: financial_report_brand_1_20260621.csv

{{-- KODE --}}
$callback = function () use ($reports, $outlets) {
    $handle = fopen('php://output', 'w');
    fputcsv($handle, ['report_date', 'outlet_id', 'outlet_name', 'total_income', 'total_expense']);
    foreach ($reports as $r) { fputcsv($handle, [ ... ]); }
    fclose($handle);
};
return Response::stream($callback, 200, $headers);
→ FUNGSINYA untuk: Kirim file CSV langsung ke browser (tanpa simpan file)
   fopen('php://output', 'w') → stream langsung ke browser
   fputcsv() → tulis 1 baris CSV
   Baris pertama = HEADER kolom
   Baris berikutnya = DATA laporan
   Response::stream() → kirim bertahap, cocok untuk data besar



{{-- ═══════════════════════════════════════════════════════════════════════════
    FILE 3: app/Http/Controllers/FranchisorDashboardController.php
    ───────────────────────────────────────────────────────────────────────
    Dashboard utama franchisor (halaman pertama setelah login).
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- KODE --}}
$hasApprovedBrand = Brand::where('franchisor_id', $user->user_id)
    ->where('status', 'approved')->exists();
if (!$hasApprovedBrand) { return redirect()->route('brand.registration.create'); }
→ FUNGSINYA untuk: Cek apakah franchisor punya brand yang sudah di-approve
   exists() → return true jika minimal 1 brand ditemukan
   Jika belum punya brand approved → redirect ke halaman daftar brand

{{-- KODE --}}
$brands = Brand::where('franchisor_id', $user->user_id)
    ->where('status', 'approved')->orderBy('brand_name')->get();
$brandIds = $brands->pluck('brand_id');
→ FUNGSINYA untuk: Ambil semua brand approved milik franchisor + ID-nya
   pluck('brand_id') → ambil cuma kolom brand_id aja

{{-- KODE --}}
$applications = Outlet::with(['brand', 'franchise'])
    ->whereIn('brand_id', $brandIds)
    ->orderBy('outlet_id', 'desc')->get();
→ FUNGSINYA untuk: Ambil semua aplikasi outlet (pending + approved)
   with(['brand', 'franchise']) → EAGER LOADING
   → Ambil data brand & franchise SEKALIGUS, bukan N+1 query
   Tanpa with(): 1 query outlet + N query brand + N query franchise
   Dengan with(): hanya 3 query total

{{-- KODE --}}
$pendingApplications = $applications->where('status', 'pending')->values();
$outlets = $applications->where('status', 'approved')->values();
→ FUNGSINYA untuk: Pisahkan outlet yang masih pending vs sudah disetujui
   values() → reset index array biar rapi mulai dari 0

{{-- KODE (grafik bulanan) --}}
FinancialReport::query()
    ->join('outlets', 'financial_reports.outlet_id', '=', 'outlets.outlet_id')
    ->whereIn('outlets.brand_id', $brandIds)
    ->where('outlets.status', 'approved')
    ->selectRaw("DATE_FORMAT(financial_reports.report_date, '%Y-%m') as month")
    ->selectRaw("SUM(financial_reports.total_income) as total_income")
    ->selectRaw("SUM(financial_reports.total_expense) as total_expense")
    ->groupBy('month')->orderBy('month')->get();
→ FUNGSINYA untuk: Query grafik BULANAN (income & expense per bulan)
   JOIN tabel outlets → filter brand_id
   DATE_FORMAT(report_date, '%Y-%m') → ambil bulan aja (contoh: "2026-06")
   SUM(total_income) → total pendapatan per bulan
   SUM(total_expense) → total pengeluaran per bulan
   GROUP BY month → 1 baris per bulan
   Hasil: [{month: '2026-05', total_income: 500000, total_expense: 50000}, ...]

{{-- KODE --}}
$chartLabels = $financialReports->pluck('month')->values()->toArray();
$incomeData = $financialReports->pluck('total_income')->map(fn($v) => (float)$v)->toArray();
$expenseData = $financialReports->pluck('total_expense')->map(fn($v) => (float)$v)->toArray();
$profitData = $financialReports->map(fn($r) => (float)$r->total_income - (float)$r->total_expense)->toArray();
→ FUNGSINYA untuk: Siapkan data chart dashboard
   $chartLabels → ['2026-05', '2026-06']
   $incomeData  → [500000, 1200000]
   $expenseData → [50000, 100000]
   $profitData  → [450000, 1100000] = income - expense per bulan



{{-- ═══════════════════════════════════════════════════════════════════════════
    FILE 4: App\Models\FinancialReport.php
    ───────────────────────────────────────────────────────────────────────
    Model untuk tabel 'financial_reports'. Setiap baris = 1 laporan
    SATU OUTLET untuk SATU TANGGAL.
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- KODE --}}
protected $table = 'financial_reports';
→ FUNGSINYA untuk: Memberi tahu Laravel nama tabel di database
   Default: Laravel cari tabel 'financial_reports' (plural dari FinancialReport)
   Tapi lebih aman ditulis eksplisit

{{-- KODE --}}
protected $primaryKey = 'financial_id';
→ FUNGSINYA untuk: Set primary key (bukan 'id' seperti default Laravel)
   Tabel ini pakai 'financial_id' sebagai primary key

{{-- KODE --}}
const CREATED_AT = 'created_at';
const UPDATED_AT = null;
→ FUNGSINYA untuk: Atur timestamp otomatis
   created_at → diisi otomatis saat insert
   UPDATED_AT = null → tabel ini TIDAK punya kolom updated_at

{{-- KODE --}}
protected $fillable = [
    'outlet_id', 'report_date', 'total_items', 'total_income', 'total_expense',
];
→ FUNGSINYA untuk: Mass-assignment protection
   Hanya kolom ini yang bisa diisi via create()/update()
   Kolom lain (financial_id, created_at) aman dari input manual

{{-- KODE --}}
protected $casts = [
    'report_date' => 'date',
    'total_items' => 'integer',
    'total_income' => 'decimal:2',
    'total_expense' => 'decimal:2',
];
→ FUNGSINYA untuk: Konversi tipe data OTOMATIS dari database
   'report_date' => 'date' → string database jadi object Carbon
   → INI MENYEBABKAN BUG! Kalau pake keyBy('report_date'), key-nya Carbon object
   → String "2026-06-21" != Carbon object, jadi isset() selalu false
   → SOLUSI: pake $reportLookup[$r->report_date->toDateString()]
   'total_items' => 'integer' → string jadi integer
   'total_income' => 'decimal:2' → string jadi float 2 desimal

{{-- KODE --}}
public function outlet() {
    return $this->belongsTo(Outlet::class, 'outlet_id', 'outlet_id');
}
→ FUNGSINYA untuk: Relasi setiap laporan dimiliki oleh SATU outlet
   Cara panggil: $financialReport->outlet->outlet_name
   belongsTo(Model, foreign_key, owner_key)



{{-- ═══════════════════════════════════════════════════════════════════════════
    FILE 5: App\Models\Outlet.php
    ───────────────────────────────────────────────────────────────────────
    Model untuk tabel 'outlets'. Setiap baris = 1 outlet franchisee.
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- KODE --}}
public function brand() {
    return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
}
→ FUNGSINYA untuk: Relasi outlet ke BRAND
   Setiap outlet dimiliki oleh SATU brand
   Cara panggil: $outlet->brand->brand_name
                 $outlet->brand->franchisor_id (siapa pemilik brand)

{{-- KODE --}}
public function franchise() {
    return $this->belongsTo(User::class, 'franchise_id', 'user_id');
}
→ FUNGSINYA untuk: Relasi outlet ke USER pemilik (franchisee)
   Cara panggil: $outlet->franchise->name (nama pemilik outlet)

{{-- KODE --}}
public function financialReports() {
    return $this->hasMany(FinancialReport::class, 'outlet_id', 'outlet_id');
}
→ FUNGSINYA untuk: Relasi outlet ke BANYAK laporan keuangan
   Satu outlet punya banyak laporan (1 per hari)
   DIPAKAI di controller via withSum() untuk ambil total income/expense
   Contoh: $outlet->financialReports()->whereDate('report_date', today())->sum('total_income')

{{-- KODE --}}
public function transactions() {
    return $this->hasMany(Transaction::class, 'outlet_id', 'outlet_id');
}
→ FUNGSINYA untuk: Relasi outlet ke transaksi detail
   TIDAK DIPAKAI di grafik harian (kita pakai financial_reports)



{{-- ═══════════════════════════════════════════════════════════════════════════
    FILE 6: routes/web.php (bagian franchisor)
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- KODE --}}
Route::get('/dashboard/franchisor', [FranchisorDashboardController::class, 'index'])
    ->name('franchisor.dashboard');
→ FUNGSINYA untuk: Route ke DASHBOARD UTAMA franchisor
   GET /dashboard/franchisor → FranchisorDashboardController@index
   name('franchisor.dashboard') → panggil via route('franchisor.dashboard')

{{-- KODE --}}
Route::post('/dashboard/franchisor/outlets/{id}/approve', [...])
    ->name('franchisor.applications.approve');
→ FUNGSINYA untuk: Route APPROVE pengajuan outlet
   POST /dashboard/franchisor/outlets/{id}/approve
   {id} → ID outlet (dari URL)

{{-- KODE --}}
Route::post('/dashboard/franchisor/outlets/{id}/reject', [...])
    ->name('franchisor.applications.reject');
→ FUNGSINYA untuk: Route REJECT pengajuan outlet
   POST /dashboard/franchisor/outlets/{id}/reject

{{-- KODE --}}
Route::get('/dashboard/franchisor/brands/{id}/financials', [...])
    ->name('franchisor.brand.financials');
→ FUNGSINYA untuk: Route LAPORAN KEUANGAN PER BRAND
   GET /dashboard/franchisor/brands/{id}/financials
   {id} → brand_id

{{-- KODE --}}
Route::get('/dashboard/franchisor/brands/{id}/financials/download', [...])
    ->name('franchisor.brand.financials.download');
→ FUNGSINYA untuk: Route DOWNLOAD CSV laporan brand

{{-- KODE --}}
Route::get('/dashboard/franchisor/outlets-today', [...])
    ->name('franchisor.financial.outlets_today');
→ FUNGSINYA untuk: Route "PENDAPATAN HARI INI"
   GET /dashboard/franchisor/outlets-today
   Menampilkan summary + bar chart + tabel

{{-- KODE --}}
Route::get('/dashboard/franchisor/harian', [...])
    ->name('franchisor.financial.daily');
→ FUNGSINYA untuk: Route "GRAFIK TRANSAKSI HARIAN"
   GET /dashboard/franchisor/harian
   Menampilkan income chart + expense chart + ranking + filter tanggal



{{-- ═══════════════════════════════════════════════════════════════════════════
    FILE 7: resources/views/layouts/dashboard.blade.php (navbar franchisor)
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- KODE --}}
<a href="{{ route('franchisor.financial.outlets_today') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
    Pendapatan Hari Ini
</a>
→ FUNGSINYA untuk: Link navigasi ke halaman "Pendapatan Hari Ini"
   route('franchisor.financial.outlets_today') → panggil route name
   class="rounded-full px-4 py-2" → styling Tailwind: rounded penuh, padding
   hover:bg-oxblood hover:text-white → saat di-hover: bg merah anggur, teks putih

{{-- KODE --}}
<a href="{{ route('franchisor.financial.daily') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
    Grafik Harian
</a>
→ FUNGSINYA untuk: Link navigasi ke halaman "Grafik Harian"
   route('franchisor.financial.daily') → /dashboard/franchisor/harian

{{-- KODE --}}
<a href="{{ route('manage.brands.index') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
    Brand Saya
</a>
→ FUNGSINYA untuk: Link ke halaman manajemen brand

{{-- KODE --}}
<a href="{{ route('manage.outlets.index') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
    Outlet
</a>
→ FUNGSINYA untuk: Link ke halaman manajemen outlet

{{-- KODE --}}
<a href="{{ route('manage.produk.index') }}" class="rounded-full px-4 py-2 transition hover:bg-oxblood hover:text-white">
    Produk
</a>
→ FUNGSINYA untuk: Link ke halaman manajemen produk



{{-- ═══════════════════════════════════════════════════════════════════════════
    FILE 8: resources/views/dashboard/franchisor_outlets_today.blade.php
    ───────────────────────────────────────────────────────────────────────
    Halaman "Pendapatan Hari Ini"
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- KODE --}}
@foreach($tableRows as $row)
    <div class="...">
        <p class="...">{{ $row['outlet_name'] }}</p>
        <p class="...">Rp {{ number_format($row['total_income'], 0, ',', '.') }}</p>
        <p>Item terjual: {{ $row['total_items'] }}</p>
        <p>Expense: Rp {{ number_format($row['total_expense'], 0, ',', '.') }}</p>
        <p>Profit: Rp {{ number_format($row['total_profit'], 0, ',', '.') }}</p>
    </div>
@endforeach
→ FUNGSINYA untuk: Tampilkan SUMMARY CARDS per outlet
   number_format($angka, 0, ',', '.') → format Rupiah: 1.000.000
   total_profit bisa hijau (>=0) atau merah (<0)

{{-- KODE (Chart.js) --}}
new Chart(ctx, {
    type: 'bar',
    data: {
        labels,
        datasets: [
            { label: 'Income (Rp)', data: incomeData, backgroundColor: 'rgba(34, 197, 94, 0.8)' },
            { label: 'Expense (Rp)', data: expenseData, backgroundColor: 'rgba(239, 68, 68, 0.8)' }
        ]
    }
});
→ FUNGSINYA untuk: Tampilkan BAR CHART income vs expense
   Income = batang HIJAU
   Expense = batang MERAH
   Sumbu X = nama outlet, Sumbu Y = Rupiah
   Bisa hover untuk lihat nominal

{{-- KODE --}}
<table class="premium-table w-full min-w-[600px]">
    <caption class="sr-only">Rincian pendapatan hari ini</caption>
    <thead>
        <tr>
            <th scope="col">Nama Outlet</th>
            <th scope="col" class="text-right">Item Terjual</th>
            <th scope="col" class="text-right">Income</th>
            <th scope="col" class="text-right">Expense</th>
            <th scope="col" class="text-right">Profit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tableRows as $row)
        <tr>
            <th scope="row">{{ $row['outlet_name'] }}</th>
            <td class="text-right">{{ $row['total_items'] }}</td>
            <td class="text-right">Rp {{ number_format($row['total_income'], 0, ',', '.') }}</td>
            <td class="text-right">{{ $row['total_expense'] > 0 ? 'Rp ' . number_format(...) : '-' }}</td>
            <td class="text-right">Rp {{ number_format($row['total_profit'], 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
→ FUNGSINYA untuk: Tabel rincian lengkap per outlet
   <caption class="sr-only"> → untuk screen reader (tidak tampil visual)
   scope="col" / scope="row" → aksesibilitas, hubungan header & data
   Jika expense = 0 → tampilkan "-" (strip) bukan "Rp 0"



{{-- ═══════════════════════════════════════════════════════════════════════════
    FILE 9: resources/views/dashboard/franchisor_daily_transactions.blade.php
    ───────────────────────────────────────────────────────────────────────
    Halaman "Grafik Harian"
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- KODE (filter form) --}}
<form method="GET" action="{{ route('franchisor.financial.daily') }}">
    <input type="date" name="start_date" value="{{ $startDate->toDateString() }}" />
    <input type="date" name="end_date" value="{{ $endDate->toDateString() }}" />
    <button type="submit">Tampilkan</button>
    <a href="{{ route('franchisor.financial.daily') }}">Reset</a>
</form>
→ FUNGSINYA untuk: Form FILTER TANGGAL
   method="GET" → data dikirim via URL (query string)
   action="{{ route('franchisor.financial.daily') }}" → ke halaman yang sama
   Input date: start_date & end_date
   Tombol "Tampilkan" → submit form
   Link "Reset" → kembali ke URL tanpa parameter (default 7 hari)

{{-- KODE (summary cards) --}}
<div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
    <p class="text-xs uppercase tracking-wide text-taupe">Total Income</p>
    <p class="mt-2 text-2xl font-bold text-green-600">Rp {{ number_format($grandIncome, 0, ',', '.') }}</p>
</div>
→ FUNGSINYA untuk: Kartu ringkasan Total Income
   Jumlah income semua outlet selama periode

{{-- KODE (Income Bar Chart) --}}
new Chart(ctxIncome, {
    type: 'bar',
    data: { labels: dateLabels, datasets: incomeDatasets },
    options: {
        scales: { y: { beginAtZero: true, ticks: { callback: value => 'Rp ' + format(value) } } },
        plugins: { legend: { position: 'bottom' } }
    }
});
→ FUNGSINYA untuk: Tampilkan BAR CHART income per outlet
   incomeDatasets → multi dataset (1 per outlet), warna berbeda
   Sumbu X = tanggal, Sumbu Y = Rupiah
   beginAtZero = true → grafik mulai dari 0 (bukan dari nilai terendah)

{{-- KODE (Expense Line Chart) --}}
new Chart(ctxExpense, {
    type: 'line',
    data: { labels: dateLabels, datasets: expenseDatasets },
    options: { ... }
});
→ FUNGSINYA untuk: Tampilkan LINE CHART expense per outlet
   1 garis per outlet, warna sesuai palet
   pointRadius = 4 → ada titik data
   tension = 0.3 → garis smooth

{{-- ═══════════════════════════════════════════════════════════════════════════
    FILE 9: resources/views/dashboard/franchisor_daily_transactions.blade.php (LANJUTAN)
    ───────────────────────────────────────────────────────────────────────
    Halaman "Grafik Harian" — Bagian: TABEL RANKING & MINI CARDS
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- KODE (Tabel Ranking - dimulai dari baris 84) --}}
<section class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
→ FUNGSINYA untuk: MEMBUNGKUS seluruh bagian tabel ranking dalam satu section
   bg-white → latar putih
   p-6 → padding 24px di semua sisi
   rounded-2xl → sudut melengkung besar (16px)
   shadow-sm → bayangan kecil
   border border-gray-100 → garis tepi abu-abu tipis

{{-- KODE (baris 86-91) --}}
<div class="flex items-end justify-between gap-4 mb-4">
    <div>
        <h2 class="text-xl font-bold">Rekap Periode (Ranking Income)</h2>
        <p class="text-sm text-taupe">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
    </div>
</div>
→ FUNGSINYA untuk: JUDUL SEKSI "Rekap Periode (Ranking Income)"
   flex items-end justify-between → item di kiri-kanan, rata bawah
   <h2 class="text-xl font-bold"> → heading level 2, teks besar dan tebal
   <p class="text-sm text-taupe"> → teks kecil warna taupe (#6b5850)
   $startDate->format('d M Y') → format tanggal Bahasa Inggris: 14 Jun 2026
   $endDate->format('d M Y') → contoh: 21 Jun 2026
   → JADI: "14 Jun 2026 - 21 Jun 2026"

{{-- KODE (baris 93-94) --}}
<div class="overflow-x-auto">
→ FUNGSINYA untuk: MEMBUNGKUS tabel agar bisa di-scroll horizontal
   overflow-x-auto → jika tabel lebih lebar dari layar, muncul scrollbar horizontal
   Penting untuk tampilan HP/tablet

{{-- KODE (baris 94-136) --}}
<table class="premium-table w-full min-w-[800px]">
→ FUNGSINYA untuk: MEMULAI TABEL dengan class premium-table
   premium-table → class kustom dari app.css (font, border, hover effect)
   w-full → lebar 100% dari container
   min-w-[800px] → lebar MINIMAL 800px (biar bisa di-scroll di HP)

{{-- KODE (baris 95) --}}
<caption class="sr-only">Ranking outlet berdasarkan total income periode {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</caption>
→ FUNGSINYA untuk: KETERANGAN TABEL (hanya untuk pembaca layar)
   class="sr-only" → TIDAK TAMPIL VISUAL, hanya dibaca screen reader
   Penting untuk aksesibilitas: pengguna tunanetra bisa tahu isi tabel
   Isi caption: "Ranking outlet berdasarkan total income periode 14 Jun 2026 - 21 Jun 2026"

{{-- KODE (baris 96-105) --}}
<thead>
    <tr>
        <th scope="col" class="p-3">#</th>
        <th scope="col" class="p-3">Outlet</th>
        <th scope="col" class="p-3 text-right">Items</th>
        <th scope="col" class="p-3 text-right">Income</th>
        <th scope="col" class="p-3 text-right">Expense</th>
        <th scope="col" class="p-3 text-right">Profit</th>
    </tr>
</thead>
→ FUNGSINYA untuk: HEADER TABEL (baris pertama)
   <thead> → bagian header tabel
   <th scope="col"> → header KOLOM
   scope="col" → aksesibilitas: memberitahu screen reader bahwa ini header kolom
   p-3 → padding 12px
   text-right → teks rata kanan (khusus kolom angka)
   Kolom: # (peringkat), Outlet (nama), Items (barang terjual), Income, Expense, Profit

{{-- KODE (baris 106) --}}
<tbody>
→ FUNGSINYA untuk: MEMULAI BAGIAN BADAN TABEL (isi data)

{{-- KODE (baris 107-134) --}}
@forelse ($outletTotals as $idx => $outlet)
→ FUNGSINYA untuk: LOOP data outlet untuk ditampilkan sebagai baris tabel
   $outletTotals → array dari controller (sudah diurutkan income tertinggi ke terendah)
   $idx → index loop (0, 1, 2, 3, ...)
   $outlet → array asosiatif: ['outlet_name' => ..., 'total_items' => ..., ...]
   @forelse → sama seperti @foreach, tapi ada @empty jika data kosong
   → Jika tidak ada data → tampilkan "Belum ada data transaksi"

{{-- KODE (baris 108-112) --}}
@php
    $rank = $idx + 1;
    $isTop = $rank <= 3;
    $rowBg = $isTop ? 'bg-yellow-50' : '';
@endphp
→ FUNGSINYA untuk: HITUNG PERINGKAT dan style baris TOP 3
   $rank = $idx + 1 → peringkat dimulai dari 1 (bukan 0)
   $isTop = $rank <= 3 → TRUE jika peringkat 1, 2, atau 3
   $rowBg = $isTop ? 'bg-yellow-50' : '' → baris TOP 3 dapat background kuning
   bg-yellow-50 → kuning sangat muda (#fffbeb)
   → JADI: 3 outlet teratas punya sorotan kuning

{{-- KODE (baris 113) --}}
<tr class="border-b border-linen/40 {{ $rowBg }}">
→ FUNGSINYA untuk: MEMULAI BARIS TABEL
   border-b border-linen/40 → garis bawah tipis warna linen 40% opacity
   {{ $rowBg }} → tambah class bg-yellow-50 jika outlet TOP 3

{{-- KODE (baris 114) --}}
<td class="p-3 font-semibold text-ink">{{ $rank }}</td>
→ FUNGSINYA untuk: KOLOM PERINGKAT (#)
   font-semibold → teks setengah tebal
   text-ink → warna #201717 (hitam pekat)
   {{ $rank }} → nomor peringkat: 1, 2, 3, ...

{{-- KODE (baris 115-121) --}}
<td class="p-3">
    <div class="flex items-center gap-2">
        @if($isTop)
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-yellow-200 text-yellow-900">TOP</span>
        @endif
        <span class="font-semibold text-ink">{{ $outlet['outlet_name'] }}</span>
    </div>
</td>
→ FUNGSINYA untuk: KOLOM NAMA OUTLET + BADGE "TOP"
   <div class="flex items-center gap-2"> → flexbox: item sejajar horizontal, jarak 8px
   @if($isTop) → hanya tampilkan badge untuk peringkat 1-3
      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-yellow-200 text-yellow-900">TOP</span>
      → FUNGSINYA untuk: BADGE "TOP" — label kuning untuk 3 outlet teratas
         inline-flex → display flex inline (agar ukuran sesuai konten)
         items-center → item di tengah vertikal
         px-2 → padding kiri-kanan 8px
         py-1 → padding atas-bawah 4px
         rounded-full → sudut melengkung penuh (bentuk pil)
         text-xs → ukuran font sangat kecil (12px)
         font-bold → teks tebal
         bg-yellow-200 → latar kuning (#fde68a)
         text-yellow-900 → teks kuning gelap (#78350f)
         "TOP" → teks di dalam badge
         → JADI: badge kuning kecil bertuliskan "TOP"
   @endif → penutup if
   <span class="font-semibold text-ink">{{ $outlet['outlet_name'] }}</span>
      → FUNGSINYA untuk: NAMA OUTLET — teks setengah tebal warna hitam
      $outlet['outlet_name'] → nama outlet dari database (misal: "Outlet Cabang 1")
   </div> → penutup flex

{{-- KODE (baris 123) --}}
<td class="p-3 text-right font-semibold">{{ $outlet['total_items'] ?? 0 }}</td>
→ FUNGSINYA untuk: KOLOM ITEMS (jumlah barang terjual)
   text-right → rata kanan (karena angka)
   font-semibold → setengah tebal
   $outlet['total_items'] ?? 0 → jika null/tidak ada, tampilkan 0
   → JADI: menampilkan total barang terjual outlet selama periode

{{-- KODE (baris 124) --}}
<td class="p-3 font-semibold text-right text-green-700">Rp {{ number_format($outlet['total_income'], 0, ',', '.') }}</td>
→ FUNGSINYA untuk: KOLOM INCOME (total pendapatan) — warna HIJAU
   text-green-700 → teks hijau tua (#15803d)
   font-semibold → setengah tebal
   text-right → rata kanan
   number_format($outlet['total_income'], 0, ',', '.')
      → FUNGSINYA untuk: FORMAT ANGKA JADI RUPIAH
         Parameter: (angka_yang_akan_diformat, jumlah_desimal, pemisah_desimal, pemisah_ribuan)
         0 → tanpa desimal (misal 500000 → "500.000", bukan "500.000,00")
         ',' → pemisah desimal (tidak dipakai karena 0 desimal)
         '.' → pemisah ribuan (jadi titik: 1.000.000)
         → CONTOH: 658000 → "Rp 658.000"
                   1200000 → "Rp 1.200.000"
   → JADI: menampilkan pendapatan outlet dengan format Rupiah, teks hijau

{{-- KODE (baris 125) --}}
<td class="p-3 text-right">Rp {{ number_format($outlet['total_expense'], 0, ',', '.') }}</td>
→ FUNGSINYA untuk: KOLOM EXPENSE (total pengeluaran)
   text-right → rata kanan
   number_format(...) → format Rupiah (sama seperti di atas)
   → JADI: menampilkan total pengeluaran outlet

{{-- KODE (baris 126-128) --}}
<td class="p-3 font-semibold text-right {{ $outlet['total_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
    Rp {{ number_format($outlet['total_profit'], 0, ',', '.') }}
</td>
→ FUNGSINYA untuk: KOLOM PROFIT (laba = income - expense)
   font-semibold → setengah tebal
   text-right → rata kanan
   {{ $outlet['total_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}
      → FUNGSINYA untuk: WARNA BERSYARAT
         Jika profit >= 0 (untung/laba) → HIJAU (text-green-600)
         Jika profit < 0 (rugi) → MERAH (text-red-600)
         → JADI: otomatis menyesuaikan warna
   number_format($outlet['total_profit'], 0, ',', '.') → format Rupiah
   → CONTOH: untung 450.000 → teks hijau "Rp 450.000"
             rugi 50.000 → teks merah "Rp -50.000"

{{-- KODE (baris 113-129) --}}
</tr>
→ FUNGSINYA untuk: PENUTUP baris tabel

{{-- KODE (baris 130-133) --}}
@empty
    <tr>
        <td colspan="6" class="p-6 text-center text-taupe">Belum ada data transaksi pada periode ini.</td>
    </tr>
→ FUNGSINYA untuk: PESAN jika TIDAK ADA DATA SAMA SEKALI
   @empty → dijalankan jika $outletTotals kosong
   colspan="6" → gabung 6 kolom jadi 1 (karena semua kolom kosong)
   text-center → teks di tengah
   text-taupe → warna abu-abu (#6b5850)
   Pesan: "Belum ada data transaksi pada periode ini."

{{-- KODE (baris 134-137) --}}
@endforelse
            </tbody>
        </table>
    </div>
→ FUNGSINYA untuk: PENUTUP
   @endforelse → penutup @forelse
   </tbody> → penutup badan tabel
   </table> → penutup tabel
   </div> → penutup overflow-x-auto

{{-- ═══════════════════════════════════════════════════════════════════════════
    FILE 9 LANJUTAN: MINI CARDS per outlet (baris 139-154)
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- KODE (baris 139-154) --}}
{{-- Mini cards per outlet --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
→ FUNGSINYA untuk: GRID KARTU RINGKASAN per outlet
   grid → CSS Grid layout
   grid-cols-1 → 1 kolom di layar HP
   md:grid-cols-2 → 2 kolom di layar tablet (768px+)
   lg:grid-cols-3 → 3 kolom di layar desktop (1024px+)
   gap-4 → jarak antar kartu 16px
   mt-8 → margin atas 32px

{{-- KODE (baris 141-153) --}}
@foreach ($outletTotals as $outlet)
    <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
        <p class="text-sm font-semibold text-ink">{{ $outlet['outlet_name'] }}</p>
        <p class="mt-3 text-xl font-bold">Rp {{ number_format($outlet['total_income'], 0, ',', '.') }}</p>
        <div class="mt-1 text-xs text-taupe space-y-0.5">
            <p>Items terjual: <span class="font-semibold text-ink">{{ $outlet['total_items'] ?? 0 }}</span></p>
            <p>
                Expense: Rp {{ number_format($outlet['total_expense'], 0, ',', '.') }} |
                Profit: <span class="font-semibold {{ $outlet['total_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($outlet['total_profit'], 0, ',', '.') }}</span>
            </p>
        </div>
    </div>
@endforeach
→ FUNGSINYA untuk: KARTU RINGKASAN per outlet (alternatif tampilan selain tabel)
   @foreach ($outletTotals as $outlet) → loop setiap outlet
   <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-100">
      → KARTU: sudut lengkung besar, latar putih, padding 20px, bayangan tipis, border abu-abu
   <p class="text-sm font-semibold text-ink">{{ $outlet['outlet_name'] }}</p>
      → NAMA OUTLET: font kecil, setengah tebal, hitam
   <p class="mt-3 text-xl font-bold">Rp {{ number_format($outlet['total_income'], 0, ',', '.') }}</p>
      → TOTAL INCOME: font besar (xl), tebal, format Rupiah
   <div class="mt-1 text-xs text-taupe space-y-0.5">
      → DETAIL: font kecil, warna taupe, jarak antar baris 2px
      "Items terjual: ..."
         → total_items (atau 0 jika null)
      "Expense: ... | Profit: ..."
         → expense + profit (hijau/merah bersyarat)
   </div>
</div>

{{-- KODE (baris 155) --}}
</section>
→ FUNGSINYA untuk: PENUTUP section tabel ranking (dari baris 84)



{{-- ═══════════════════════════════════════════════════════════════════════════
    PENJELASAN DATA FLOW LENGKAP
    ───────────────────────────────────────────────────────────────────────
    Dari input sampai grafik: LANGKAH 1 → 2 → 3 → 4
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- LANGKAH 1: FRANCHISEE INPUT LAPORAN --}}

{{-- KODE --}}
Franchisee → Buka /dashboard/franchisee/financial-report/create
            → Pilih outlet, tanggal, isi qty produk, submit
→ FUNGSINYA untuk: Franchisee mengisi laporan keuangan harian
   OutletFinancialReportController@store menangani submit:
   1. Validasi data (outlet_id, report_date, quantities, total_expense)
   2. Cek kepemilikan outlet (outlet punya franchisee ini?)
   3. Cek DUPLIKAT (sudah ada laporan? → redirect ke halaman EDIT)
   4. Hitung totalItems = sum quantity
   5. Hitung totalIncome = sum(qty × harga)
   6. Simpan ke tabel financial_reports
   7. Redirect dengan pesan sukses

{{-- LANGKAH 2: DATA DI DATABASE --}}

{{-- KODE --}}
Tabel financial_reports:
financial_id | outlet_id | report_date | total_items | total_income | total_expense
1            | 1         | 2026-06-19  | 12          | 360000       | 0
2            | 2         | 2026-06-19  | 8           | 249000       | 0
3            | 1         | 2026-06-20  | 20          | 635000       | 0
4            | 2         | 2026-06-20  | 15          | 501000       | 50000
5            | 1         | 2026-06-21  | 22          | 658000       | 0
6            | 2         | 2026-06-21  | 25          | 739000       | 0
→ FUNGSINYA untuk: Contoh isi tabel financial_reports
   outlet_id 1 = Outlet Cabang 1 (milik brand SSB)
   outlet_id 2 = Outlet Cabang 2 (milik brand SSB)
   report_date = tanggal laporan
   total_items = jumlah barang terjual
   total_income = pendapatan (qty × harga)
   total_expense = biaya operasional

{{-- LANGKAH 3: FRANCHISOR LIHAT GRAFIK --}}

{{-- KODE --}}
Franchisor → Buka /dashboard/franchisor/harian (menu "Grafik Harian")
           → Controller jalankan dailyPerOutlet()
→ FUNGSINYA untuk: Franchisor melihat grafik perbandingan outlet
   Yang terjadi di server:
   1. Ambil semua outlet approved milik franchisor
   2. Buat daftar tanggal (dari filter atau default 7 hari)
   3. Query financial_reports di rentang tanggal
   4. LOOP per outlet + per tanggal → cocokkan data
   5. Kirim 10 variabel ke view
   Yang tampil di halaman:
   - Summary cards (grand total income/expense/profit)
   - Bar chart income per outlet
   - Line chart expense per outlet
   - Tabel ranking outlet
   - Mini cards per outlet

{{-- LANGKAH 4: FILTER TANGGAL --}}

{{-- KODE --}}
Franchisor → Ubah tanggal di form filter → klik "Tampilkan"
           → URL berubah: /dashboard/franchisor/harian?start_date=...&end_date=...
           → Halaman reload dengan data baru
→ FUNGSINYA untuk: Filter grafik berdasarkan rentang tanggal
   Klik "Reset" → kembali ke default 7 hari terakhir
   Format tanggal: YYYY-MM-DD (tahun-bulan-tanggal)



{{-- ═══════════════════════════════════════════════════════════════════════════
    TROUBLESHOOTING
    ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- MASALAH 1: Grafik kosong --}}
Grafik kosong / tidak ada batang
→ PENYEBAB: Tidak ada data di financial_reports untuk periode tersebut
→ SOLUSI:   Cek database: SELECT * FROM financial_reports JOIN outlets ...
            Minta franchisee input laporan dulu

{{-- MASALAH 2: Total Items selalu 1 --}}
Total items / transaksi selalu 1
→ PENYEBAB: Bug LAMA (sekarang sudah diperbaiki)
   Dulu pakai withCount (jumlah RECORD), sekarang pakai withSum('total_items')
→ SOLUSI:   Sudah benar di controller terbaru

{{-- MASALAH 3: Error toDateString() --}}
Error "Trying to get property 'toDateString' of non-object"
→ PENYEBAB: Ada financial_report dengan report_date NULL
→ SOLUSI:   Hapus data NULL: DELETE FROM financial_reports WHERE report_date IS NULL

{{-- MASALAH 4: Error 403 --}}
Error 403 saat akses halaman
→ PENYEBAB: User bukan franchisor, atau brand bukan miliknya
→ SOLUSI:   Login dengan akun franchisor yang benar

{{-- MASALAH 5: Route tidak ditemukan --}}
Route franchisor.financial.daily not found
→ PENYEBAB: Route belum didaftarkan
→ SOLUSI:   php artisan route:list --name=franchisor.financial
            Cek apakah route muncul di daftar

{{-- MASALAH 6: Filter tidak berfungsi --}}
Filter tanggal tidak berfungsi
→ PENYEBAB: Format tanggal salah
→ SOLUSI:   Gunakan format YYYY-MM-DD (2026-06-14)