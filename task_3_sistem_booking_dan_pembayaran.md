# Task 3: Sistem Booking dan Pembayaran (Bookings & Payments)

## Deskripsi Singkat
Mengembangkan alur penyewaan kos. Tenant dapat melakukan pemesanan (booking) kamar dan sistem mencatat tagihan serta status pembayarannya.

## Kebutuhan (Requirements)
1. **Migration & Model:**
   - Buat tabel `bookings` yang mencatat referensi `tenant_id` dan `room_id`, `start_date`, `end_date`, dll.
   - Buat tabel `payments` yang mencatat detail pembayaran atas suatu booking.

2. **Seeder (Data Dummy):**
   - Buat `BookingSeeder` dan `PaymentSeeder`.
   - Simulasikan skenario transaksi: Booking status `active` dengan Payment `paid`, serta Booking status `pending` dengan Payment `unpaid`.

3. **Logika Transaksi (CRUD):**
   - **Booking:** Tenant menginput durasi sewa, sistem menghitung `total_price` berdasarkan `price` kamar.
   - **Stock Control:** Saat booking berhasil / active, kurangi `available_stock` pada tabel `rooms` yang sesuai.
   - **Payment:** Mengelola status pembayaran (sementara input form manual/bukti transfer, atau disiapkan tempat/kolom untuk integrasi API di masa depan).

4. **Standar & Validasi:**
   - Karena modul ini sangat kritikal, penggunaan `DB::beginTransaction()` bersifat wajib untuk mencegah ketidaksesuaian data antara tabel booking, payment, dan stock control.

## Langkah Eksekusi
- [ ] Generate Migrations & Models (Bookings, Payments).
- [ ] Buat BookingController dan PaymentController.
- [ ] Implementasikan form pemesanan untuk Tenant.
- [ ] Buat halaman Manajemen Pesanan untuk Owner.
- [ ] Tulis Seeder dan jalankan untuk memvisualisasikan data transaksi di Dashboard/Tabel.
