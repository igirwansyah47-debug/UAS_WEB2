# Task 5: Dashboard dan Analitik Statistik (Dashboard & Analytics)

## Deskripsi Singkat
Mengembangkan antarmuka Dashboard yang informatif untuk Superadmin dan Owner. Menyajikan ringkasan data, grafik statistik okupansi, dan performa pendapatan.

## Kebutuhan (Requirements)
1. **Pembaruan Controller (DashboardController):**
   - **Superadmin:** Melihat total pengguna (berdasarkan role), total transaksi, dan pendapatan kotor keseluruhan platform.
   - **Owner:** Melihat statistik khusus propertinya, seperti tingkat hunian (okupansi) kamar, total pendapatan per bulan, dan status pembayaran yang tertunda.
   - **Tenant:** Melihat properti yang sedang disewa aktif, tagihan jatuh tempo terdekat, atau pengumuman dari Owner.

2. **Seeder (Data Dummy):**
   - Tidak memerlukan seeder tabel baru, tetapi harus menggunakan seeder dari task sebelumnya (`BookingSeeder`, `PaymentSeeder`, `RoomSeeder`) dengan rentang tanggal (`created_at`) yang bervariasi agar grafik/tabel analitik dapat menampilkan visualisasi data bulanan yang realistis.

3. **Logika Bisnis:**
   - Gunakan query *Eloquent* yang efisien, dengan `withCount()` atau `sum()` untuk agregasi data (hindari N+1 problem).

4. **Standar & UI:**
   - Gunakan library chart yang kompatibel atau sudah tersedia di template eksisting (misalnya Chart.js atau ApexCharts).
   - Terapkan filter berdasarkan bulan dan tahun.

## Langkah Eksekusi
- [ ] Perbarui `DashboardController.php` dengan query agregasi untuk masing-masing Role.
- [ ] Sesuaikan view Blade `dashboard.blade.php` agar komponen yang dirender bergantung pada `auth()->user()->role`.
- [ ] Integrasikan grafik (Chart) untuk visualisasi pendapatan bulanan Owner/Superadmin.
- [ ] Jalankan seeder menyeluruh (DatabaseSeeder) dengan data tanggal bervariasi.
