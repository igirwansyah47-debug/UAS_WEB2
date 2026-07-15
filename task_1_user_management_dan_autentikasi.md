# Task 1: Manajemen Pengguna & Autentikasi (User Management & Auth)

## Deskripsi Singkat
Menyesuaikan modul autentikasi dan manajemen pengguna yang sudah ada agar sepenuhnya selaras dengan struktur peran (role) yang didefinisikan dalam PRD (superadmin, owner, tenant).

## Kebutuhan (Requirements)
1. **Penyesuaian Struktur Tabel:**
   - Tabel `users` perlu dipastikan memiliki kolom `role` dengan tipe enum (atau string) yang menerima nilai: `superadmin`, `owner`, dan `tenant`.
   - Tambahkan kolom `phone` jika belum ada, sesuai dengan skema di PRD.

2. **Pembaruan Seeder (Data Dummy):**
   - Perbarui `UserSeeder` atau buat seeder baru untuk melakukan populasi data dummy.
   - Wajib ada minimal 1 data dummy untuk masing-masing role (`superadmin`, `owner`, `tenant`).

3. **Penyesuaian Logika CRUD (UserController):**
   - **Create/Update:** Pastikan validasi form (baik di frontend maupun di `UserController`) mengenali opsi role `superadmin`, `owner`, dan `tenant`.
   - **Read:** Pastikan daftar pengguna menampilkan role yang sesuai. Filter berdasarkan role juga disarankan jika diperlukan.
   - **Delete:** Logika penghapusan sudah menggunakan `DB::beginTransaction()`, pertahankan standar tersebut.

4. **Autentikasi & Otorisasi:**
   - Sesuaikan middleware (jika ada) agar membatasi akses berdasarkan role. Contoh: hanya `superadmin` yang bisa mengakses menu Manajemen Pengguna.

## Standar Coding & Konvensi
- **Validasi:** Gunakan `$request->validate()` seperti pada method yang sudah *existing*.
- **Transaksi Database:** Gunakan `DB::beginTransaction()`, `DB::commit()`, dan `DB::rollBack()` secara konsisten pada method `store`, `update`, dan `destroy`.
- **Penamaan Variabel:** Tetap gunakan penamaan variabel berbahasa Inggris untuk atribut dan berbahasa Indonesia untuk pesan error/flash data (sesuai *existing*).
- **Controller:** Tetap gunakan pola arsitektur Resource Controller.

## Langkah Eksekusi (Jangan Ngoding Dulu)
- [ ] Periksa dan perbarui Migration file `users` table.
- [ ] Perbarui `UserFactory` dan `UserSeeder`.
- [ ] Sesuaikan Blade view (`user.create`, `user.edit`, `user.index`) untuk memfasilitasi dropdown role baru.
- [ ] Sesuaikan method `store` dan `update` di `UserController.php`.
- [ ] Uji coba login dengan role yang berbeda.
