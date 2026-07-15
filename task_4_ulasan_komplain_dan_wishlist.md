# Task 4: Ulasan, Komplain, dan Wishlist (Reviews, Complaints & Wishlists)

## Deskripsi Singkat
Mengembangkan fitur interaksi lanjutan bagi tenant, yaitu memberikan ulasan (review) terhadap properti, mengajukan komplain terkait fasilitas kamar, serta menyimpan kos favorit ke dalam wishlist.

## Kebutuhan (Requirements)
1. **Migration & Model:**
   - Tabel `reviews` (relasi ke `tenant_id` dan `property_id`).
   - Tabel `complaints` (relasi ke `tenant_id` dan `room_id`).
   - Tabel `wishlists` (relasi ke `tenant_id` dan `property_id`).

2. **Seeder (Data Dummy):**
   - Buat `ReviewSeeder`, `ComplaintSeeder`, dan `WishlistSeeder`.
   - Simulasikan data komplain dengan status yang berbeda (`open`, `in_progress`, `resolved`) agar antarmuka owner dapat membedakannya.
   - Masukkan beberapa data review untuk mengkalkulasi rating rata-rata properti.

3. **Logika CRUD & Interaksi:**
   - **Reviews:** Tenant hanya boleh me-review properti yang pernah atau sedang disewa (bisa diakses via `bookings`).
   - **Complaints:** Tenant mengirim pesan kerusakan ke owner. Owner mengelola status komplain tersebut.
   - **Wishlists:** Toggle (Add/Remove) properti ke dalam daftar favorit tenant.

4. **Standar & Validasi:**
   - Pastikan validasi form ulasan (contoh: rating harus 1 hingga 5).
   - Penggunaan transaksi database standar dan pengembalian (return) pesan sukses/error berbahasa Indonesia.

## Langkah Eksekusi
- [ ] Generate Migrations & Models (Reviews, Complaints, Wishlists).
- [ ] Buat Controllers terkait.
- [ ] Buat Seeder dan eksekusi untuk memastikan data dummy muncul di UI.
- [ ] Implementasi UI/UX di blade (misalnya menggunakan icon bintang untuk rating).
