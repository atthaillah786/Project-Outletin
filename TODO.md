# TODO - Owner Laporan Keuangan & Perbaikan Grafik

- [ ] Pahami implementasi laporan keuangan brand yang ada (controller + view)
- [x] Update `BrandFinancialController@index` untuk support filter (start/end date, outlet_id)
- [x] Update `BrandFinancialController@download` agar CSV mengikuti filter

- [x] Perbaiki tampilan grafik di view `resources/views/financial/brand_report.blade.php`


      - [ ] kurangi crowding (default agregasi mingguan/lebih ringkas)
      - [ ] tampilkan ranking outlet berdasarkan income & profit
      - [x] tambahkan form filter di halaman owner
- [ ] Pastikan tampilan tetap responsif dan chart tidak error
- [ ] Validasi alur: franchisor buka halaman -> filter -> grafik + tabel berubah
- [ ] Validasi download CSV

