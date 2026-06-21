/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                /* ── ORIGINAL PALETTE (untuk referensi) ──
                 * ivory:  #f8f8f7  (latar terang)
                 * linen:  #cbc0b2  (aksen krem/border)
                 * oxblood:#550b14  (primer gelap merah anggur)
                 * taupe:  #7e6961  (secondary abu-coklat)
                 * ink:    #201717  (teks utama, hampir hitam)
                 * mist:   #efeae4  (latar sekunder)
                 *
                 * ── ANALISA WCAG 2.2 AA ──
                 * Kombinasi                   Rasio     Status
                 * oxblood di white            14.6:1    ✅ Lolos
                 * ink di white                17.6:1    ✅ Lolos
                 * taupe (#7e6961) di ivory    4.83:1    ✅ Lolos
                 * taupe (#7e6961) di mist     4.29:1    ❌ GAGAL (normal text)
                 * taupe (#7e6961) di linen    2.87:1    ❌ GAGAL (semua teks)
                 * linen di white              1.79:1    ❌ GAGAL (semua teks)
                 *
                 * ── PENYESUAIAN ──
                 * Taupe: digelapkan dari #7e6961 → #6b5850 (+16% kontras)
                 * Linen: tetap sebagai warna background/border (bukan teks)
                 *        Semua teks di atas linen HARUS pakai oxblood/ink
                 */

                // ▸ Latar & Aksen (tidak berubah — hanya untuk background/border)
                ivory: '#f8f8f7',
                linen: '#cbc0b2',
                mist: '#efeae4',

                // ▸ Primer — teks bermakna "brand" (tombol, judul, link)
                oxblood: '#550b14',

                // ▸ Secondary — teks pendukung / muted / label
                //   Digelapkan agar rasio kontras ≥ 4.5:1 di atas ivory/mist
                taupe: '#6b5850',

                // ▸ Teks utama — body copy, heading besar
                ink: '#201717',
            },
            boxShadow: {
                premium: '0 18px 55px rgb(85 11 20 / 0.12)',
            },
        },
    },
    plugins: [],
};