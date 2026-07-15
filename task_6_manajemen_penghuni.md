# Task 6: Manajemen Penghuni (Tenant Management)

## Deskripsi Singkat
Memberikan fitur kepada Owner untuk melihat dan mengelola data tenant/penghuni yang sedang aktif menyewa di properti mereka, termasuk riwayat penyewaan sebelumnya.

## Kebutuhan (Requirements)
1. **Query & Controller:**
   - Buat `TenantController` (khusus akses Owner) untuk melist `Users` dengan role `tenant` yang memiliki `bookings` berstatus `active` di properti milik Owner tersebut.
   - Owner dapat melihat detail profil tenant tersebut (nomor telepon/KTP) yang relevan untuk kebutuhan kontak darurat.

2. **Seeder (Data Dummy):**
   - Pastikan ada seeder yang mensimulasikan tenant dengan status sewa `active` dan `completed`. Sehingga Owner bisa melihat daftar penghuni saat ini vs riwayat penghuni lampau.

3. **Logika CRUD & Interaksi:**
   - **Read:** Filter tabel berdasarkan Properti atau Status Sewa (Aktif / Selesai).
   - Owner tidak dapat menghapus akun tenant secara langsung, tetapi dapat mengubah status `bookings` menjadi `completed` atau `cancelled` jika tenant melanggar aturan atau masa sewa habis sebelum waktunya.

4. **Standar & Validasi:**
   - Pengamanan akses: Pastikan Owner A tidak bisa mengakses data tenant yang hanya menyewa di properti milik Owner B. (Implementasi otorisasi via Middleware atau Policy).

## Langkah Eksekusi
- [ ] Buat `TenantManagementController` khusus Owner.
- [ ] Implementasikan Laravel Policy untuk membatasi akses data tenant antar Owner.
- [ ] Buat UI tabel Manajemen Penghuni dengan fitur pencarian dan filter status.
- [ ] Sempurnakan Seeder agar mensimulasikan minimal 5 tenant dengan status berbeda.
