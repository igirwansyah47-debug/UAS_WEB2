# Task 10: Pencarian Peta dan In-App Chat (Map Search & Messages)

## Deskripsi Singkat
Memberikan pengalaman visual dan interaktif yang lebih baik bagi calon penyewa melalui pencarian kos berbasis titik koordinat di Peta, serta memungkinkan komunikasi *real-time* atau *asynchronous* antara penyewa dan pemilik kos tanpa harus keluar dari aplikasi.

## Kebutuhan (Requirements)
1. **Pencarian Berbasis Peta (Geolocation):**
   - Tambahkan kolom `latitude` dan `longitude` di tabel `properties`.
   - Integrasikan *Map SDK* (misal: Google Maps API, Mapbox, atau Leaflet/OpenStreetMap).
   - Tampilkan *Pin/Marker* pada halaman pencarian (*Search Page*) sesuai koordinat kos.

2. **In-App Chat (Messages):**
   - Tabel `messages` untuk menyimpan konten chat, `sender_id`, dan `receiver_id`.
   - Fitur chat ini diperuntukkan untuk negosiasi atau tanya-jawab seputar kos (pre-booking) dan selama masa penyewaan (post-booking).

3. **Seeder (Data Dummy):**
   - `MessageSeeder`: Buat histori percakapan dummy antara salah satu Tenant dan Owner untuk melihat visualisasi UI *Chat Bubble*.
   - Sesuaikan `PropertySeeder` dengan menyisipkan dummy data latitude & longitude (misal titik koordinat di wilayah Jakarta atau Bandung).

4. **Standar & Validasi:**
   - Gunakan Javascript/AJAX (atau fitur frontend seperti Vue/React/Livewire jika ada) untuk mengirim chat tanpa *reload* halaman, untuk *user experience* yang lebih baik.

## Langkah Eksekusi
- [ ] Ubah tabel `properties` untuk koordinat (lat, long).
- [ ] Buat Tabel `messages` dan model/relasinya.
- [ ] Integrasikan plugin Maps di halaman detail & pencarian.
- [ ] Bangun antarmuka (*UI*) jendela obrolan (Chat Window).
- [ ] Terapkan *Seeder* untuk koordinat dan data percakapan.
