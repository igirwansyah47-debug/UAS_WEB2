# Task 2: Manajemen Properti & Kamar (Properties & Rooms)

## Deskripsi Singkat
Mengembangkan modul utama untuk manajemen properti kos-kosan dan data kamar. Owner dapat menambahkan kos-kosan beserta tipe-tipe kamar yang tersedia di dalamnya, lengkap dengan fasilitasnya.

## Kebutuhan (Requirements)
1. **Migration & Model:**
   - Buat tabel `properties` (dimiliki oleh owner/user).
   - Buat tabel `rooms` (berelasi dengan properties).
   - Buat tabel `facilities` (master data fasilitas).
   - Buat tabel pivot `room_facilities` (many-to-many antara rooms dan facilities).

2. **Seeder (Data Dummy):**
   - Buat `PropertySeeder`, `RoomSeeder`, dan `FacilitySeeder`.
   - Bangun skenario relasional (misal: Owner A memiliki Properti X. Properti X memiliki Kamar Standard dan VIP. Masing-masing kamar memiliki fasilitas berbeda).

3. **Logika CRUD:**
   - **Properties:** Form input properti yang terikat dengan user ID dari Owner yang sedang login.
   - **Rooms:** Form input tipe kamar yang terikat dengan properti tertentu (One-to-Many).
   - **Facilities:** Form input master fasilitas (hanya superadmin) dan penyisipan fasilitas ke kamar menggunakan metode *sync* atau *attach*.

4. **Standar & Validasi:**
   - Terapkan standar `DB::beginTransaction()` pada setiap aksi *Create*, *Update*, dan *Delete*.
   - Validasi menggunakan `$request->validate()` dan pesan error bahasa Indonesia.
   - Gunakan fitur File Storage yang sama jika mengunggah foto properti/kamar (seperti fitur unggah *avatar* di `UserController`).

## Langkah Eksekusi
- [ ] Generate Migrations & Models untuk Properties, Rooms, Facilities, dan pivot tabelnya.
- [ ] Generate Controllers (PropertyController, RoomController).
- [ ] Buat Form Views (Blade templates) dengan styling bawaan (NiceAdmin/Bootstrap).
- [ ] Buat dan eksekusi Database Seeder untuk memvisualisasikan data dummy.
