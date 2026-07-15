# Task 8: Integrasi Payment Gateway dan Notifikasi Otomatis (Payment & Notifications)

## Deskripsi Singkat
Mengubah sistem pembayaran manual dari Task 3 menjadi pembayaran terotomatisasi menggunakan API Payment Gateway pihak ketiga (Midtrans/Xendit) serta menambahkan fitur notifikasi (Email).

## Kebutuhan (Requirements)
1. **Sistem Payment Gateway:**
   - Tambahkan *payment gateway SDK/API client* via Composer.
   - Perbarui logika pada `PaymentController` untuk men-generate Virtual Account (VA) atau *Payment Link*.
   - Buat *Webhook/Callback endpoint* untuk menerima notifikasi pembayaran sukses dari Payment Gateway dan mengubah status `payments` menjadi `paid`.

2. **Notifikasi (Email):**
   - Implementasikan *Laravel Mailable* atau *Notifications* untuk mengirimkan email otomatis ketika:
     1. Tenant berhasil melakukan booking (Instruksi Pembayaran).
     2. Pembayaran berhasil diverifikasi (Kuitansi Digital).
     3. H-3 masa sewa habis (Pengingat perpanjangan).

3. **Seeder (Data Dummy):**
   - Tambahkan skenario `transaction_id` *dummy* pada `PaymentSeeder` untuk memvisualisasikan bagaimana data callback payment gateway disimpan.

4. **Standar & Validasi:**
   - Gunakan fitur `Jobs` & `Queues` bawaan Laravel untuk pengiriman email agar halaman tidak *loading* lama (proses *asynchronous*).
   - Lindungi *Webhook endpoint* agar hanya bisa diakses oleh IP dari Payment Gateway provider.

## Langkah Eksekusi
- [ ] Install package Payment Gateway (Misal: Midtrans).
- [ ] Buat konfigurasi `.env` dan file config untuk kredensial API.
- [ ] Buat class `Mailable` untuk template email kuitansi.
- [ ] Buat Route & Controller untuk menangani Webhook.
- [ ] Konfigurasi `Queue` driver (misal menggunakan database) untuk mengirim notifikasi secara *background*.
