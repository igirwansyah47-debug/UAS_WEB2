# Task 7: Pengaturan Master Data dan Verifikasi (Master Data & Verification)

## Deskripsi Singkat
Mengembangkan fitur eksklusif untuk Superadmin guna mengelola master data (seperti kategori fasilitas, kota) dan melakukan verifikasi properti serta owner yang baru mendaftar di platform.

## Kebutuhan (Requirements)
1. **Verifikasi Owner/Properti:**
   - Tambahkan kolom `is_verified` (boolean) pada tabel `users` atau `properties`.
   - Hanya properti yang di-verifikasi oleh Superadmin yang dapat muncul di halaman pencarian Tenant.

2. **Master Data CRUD:**
   - Superadmin dapat menambah, mengedit, dan menghapus `facilities` secara global.
   - (Opsional) Tabel master `cities` atau `categories` jika platform ingin diskalakan.

3. **Seeder (Data Dummy):**
   - Buat seeder dengan properti yang `is_verified = true` dan sebagian `false` untuk disetujui melalui dashboard admin.

4. **Standar & Validasi:**
   - Modul ini wajib dienkapsulasi dengan middleware `superadmin`.
   - Terapkan validasi standard dengan `$request->validate()` dan pesan error bahasa Indonesia.
   - Aksi *approve/reject* wajib menggunakan `DB::beginTransaction()`.

## Langkah Eksekusi
- [ ] Buat kolom `is_verified` via Migration.
- [ ] Buat `VerificationController` untuk aksi approve/reject.
- [ ] Buat halaman Manajemen Fasilitas Global untuk Superadmin.
- [ ] Sesuaikan Seeder untuk memvisualisasikan status verifikasi yang berbeda.
