# Task 9: Perpanjangan Sewa dan Tagihan Tambahan (Renewal & Extra Bills)

## Deskripsi Singkat
Mengembangkan fitur siklus sewa jangka panjang (long-term tenancy), memungkinkan perpanjangan sewa secara otomatis maupun manual, manajemen uang jaminan (deposit), serta sistem penagihan biaya tambahan seperti listrik dan air setiap bulannya.

## Kebutuhan (Requirements)
1. **Migration & Model:**
   - Tabel `extra_bills` dengan relasi ke `booking_id` untuk mencatat tagihan ekstra (listrik, parkir, dll).
   - Tambahkan kolom `security_deposit` pada tabel `bookings` atau `properties` untuk mencatat nilai uang jaminan.

2. **Perpanjangan Sewa (Renewal):**
   - Buat logika pembuatan Invoice/Booking baru atau perpanjangan `end_date` ketika waktu sewa (misal 1 bulan) sudah hampir habis.
   - Owner dapat mengonfirmasi pengembalian *Security Deposit* saat status booking berubah menjadi *completed*.

3. **Seeder (Data Dummy):**
   - `ExtraBillSeeder`: Mensimulasikan tagihan listrik yang belum dibayar oleh Tenant untuk bulan berjalan.

4. **Standar & Validasi:**
   - Terapkan kalkulasi total tagihan secara dinamis: `Total Tagihan = (Harga Kamar + Extra Bills)`.
   - Gunakan `DB::beginTransaction()` untuk menghindari inkonsistensi saat kalkulasi tagihan.

## Langkah Eksekusi
- [ ] Ubah tabel `bookings` untuk menyimpan nilai `security_deposit`.
- [ ] Buat Migration & Model untuk `ExtraBills`.
- [ ] Buat Antarmuka (UI) bagi Owner untuk menambahkan tagihan ekstra bulanan kepada Tenant aktif.
- [ ] Buat Antarmuka bagi Tenant untuk melihat daftar rincian tagihan beserta tombol pembayarannya.
