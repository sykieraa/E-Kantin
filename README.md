# E-Kantin

Aplikasi pemesanan kantin sekolah berbasis web. Siswa memesan dari ponsel, mendapat nomor antrean digital, dan datang ke kantin saat pesanan sudah siap. Penjual mengelola menu, stok, status kantin, dan antrean. Admin memantau semuanya dari satu dashboard.

Dibuat dengan Laravel 11, MySQL, Blade, dan Tailwind CSS.

---

## Daftar isi

1. [Baca ini dulu: apa yang sudah jadi dan apa yang belum](#baca-ini-dulu)
2. [Step 1 — Persiapan software](#step-1--persiapan-software)
3. [Step 2 — Extract ZIP](#step-2--extract-zip)
4. [Step 3 — Install dependency](#step-3--install-dependency)
5. [Step 4 — Environment](#step-4--environment)
6. [Step 5 — Database](#step-5--database)
7. [Step 6 — Seeder](#step-6--seeder)
8. [Step 7 — Storage link](#step-7--storage-link)
9. [Step 8 — Menjalankan aplikasi](#step-8--menjalankan-aplikasi)
10. [Step 9 — Checklist testing](#step-9--checklist-testing)
11. [Step 10 — Troubleshooting](#step-10--troubleshooting)
12. [Roadmap sampai final project](#roadmap-sampai-final-project)
13. [Cara melakukan kustomisasi](#cara-melakukan-kustomisasi)
14. [Struktur folder](#struktur-folder)

---

## Baca ini dulu

### Yang sudah berfungsi penuh (terhubung database, bukan tampilan kosong)

| Fitur | Status |
|---|---|
| Login 3 role (siswa, penjual, admin) dengan password ter-hash | Berfungsi |
| Middleware role dan proteksi halaman per role | Berfungsi |
| CRUD menu oleh penjual, termasuk upload foto | Berfungsi |
| Keranjang, checkout, pembuatan pesanan | Berfungsi |
| Pengurangan stok otomatis saat checkout | Berfungsi |
| Pengembalian stok saat pesanan dibatalkan | Berfungsi |
| Penguncian baris database agar stok tidak minus saat pemesanan bersamaan | Berfungsi |
| Nomor antrean urut per kantin, reset setiap hari | Berfungsi |
| Perubahan status pesanan oleh penjual | Berfungsi |
| Status kantin (buka / tutup sementara / tutup) memblokir pemesanan | Berfungsi |
| Riwayat transaksi dengan snapshot harga | Berfungsi |
| CRUD siswa, penjual, kantin, kategori oleh admin | Berfungsi |
| Dashboard statistik admin dan penjual | Berfungsi |
| Tampilan responsif (ponsel, tablet, desktop) | Berfungsi |

### Yang perlu kamu tahu sebelum demo (jangan diklaim lebih dari ini)

**1. Notifikasi "siap diambil" memakai polling, bukan push notification.**
Halaman detail pesanan siswa menanyakan status ke server setiap 7 detik lewat JavaScript. Kalau statusnya berubah, halaman di-reload. Ini bukan WebSocket, bukan Laravel Echo, bukan notifikasi HP. Kalau penguji bertanya, jawab apa adanya: polling AJAX. Untuk kebutuhan kantin sekolah ini sudah cukup dan jauh lebih sederhana untuk dipasang.

**2. Pembayaran memakai asumsi tunai saat pengambilan.**
Tidak ada payment gateway dan tidak ada saldo. Kolom `payment_status` di tabel `orders` otomatis menjadi `paid` ketika penjual menandai pesanan Selesai. Kalau sekolahmu butuh pembayaran non-tunai, itu masuk ke bagian pengembangan selanjutnya.

**3. Project ini belum pernah dijalankan end-to-end oleh pembuatnya.**
Semua file PHP sudah lolos pemeriksaan syntax dan semua nama route yang dipakai di tampilan sudah dicek keberadaannya, tapi aplikasi belum pernah benar-benar dijalankan dengan PHP + MySQL. Kemungkinan masih ada penyesuaian kecil saat pertama kali dijalankan. Bagian [Troubleshooting](#step-10--troubleshooting) dibuat untuk itu.

**4. Folder `vendor` dan `node_modules` tidak disertakan.**
Keduanya berukuran ratusan MB dan dapat dipasang ulang lewat Composer dan npm. Hasil build CSS/JS (`public/build`) **sudah disertakan**, jadi kamu bisa menjalankan aplikasi tanpa perlu menjalankan npm sama sekali.

---

## Step 1 — Persiapan software

| Software | Versi minimal | Kegunaan |
|---|---|---|
| PHP | 8.2 | Menjalankan Laravel |
| Composer | 2.x | Memasang library PHP |
| MySQL / MariaDB | 5.7 / 10.4 | Database |
| Node.js | 18 | Hanya jika ingin mengubah tampilan |
| Git | bebas | Opsional |
| VS Code | terbaru | Editor kode |

### Cara paling mudah: pakai Laragon (Windows)

Laragon sudah memuat PHP, MySQL, dan Composer sekaligus, jadi kamu tidak perlu memasangnya satu per satu.

1. Unduh Laragon Full dari situs resminya, lalu install seperti aplikasi biasa.
2. Buka Laragon, klik **Start All**. Tombol Apache dan MySQL akan menjadi hijau.
3. Klik kanan pada jendela Laragon → **Terminal**. Terminal inilah yang akan kamu pakai untuk semua perintah di panduan ini.
4. Cek apakah semuanya siap dengan mengetik:

```bash
php -v
composer -V
```

Kalau keduanya menampilkan nomor versi, kamu siap lanjut.

> **PHP extension yang wajib aktif:** `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`. Di Laragon semuanya sudah aktif secara bawaan. Kalau memakai XAMPP dan ada error terkait extension, buka `php.ini` lalu hapus tanda titik koma (`;`) di depan baris `extension=...` yang bersangkutan, lalu restart Apache.

### Kalau memakai XAMPP

Sama saja, tapi Composer harus dipasang terpisah dari situs getcomposer.org. Jalankan perintah lewat Command Prompt biasa, bukan lewat panel XAMPP.

---

## Step 2 — Extract ZIP

1. Klik kanan `E-Kantin.zip` → **Extract All** (atau Extract Here kalau memakai 7-Zip/WinRAR).
2. Pindahkan folder hasil extract ke lokasi project:
   - Laragon: `C:\laragon\www\E-Kantin`
   - XAMPP: `C:\xampp\htdocs\E-Kantin`
   - Linux/Mac: bebas, misalnya `~/projects/E-Kantin`
3. Pastikan struktur folder benar. Di dalam `E-Kantin` harus langsung terlihat folder `app`, `config`, `database`, `public`, `resources`, `routes`, dan file `artisan`. Kalau yang terlihat malah folder `E-Kantin` lagi di dalamnya, masuk satu tingkat dan pindahkan isinya ke atas.

### Membuka di VS Code

Buka VS Code → menu **File** → **Open Folder** → pilih folder `E-Kantin` → **Select Folder**.

Lalu buka terminal bawaan VS Code dengan **Ctrl + `** (tombol backtick, di bawah tombol Escape). Semua perintah di bawah dijalankan dari terminal ini, dengan posisi berada di dalam folder `E-Kantin`.

Ekstensi VS Code yang sangat membantu: **PHP Intelephense**, **Laravel Blade Snippets**, dan **Tailwind CSS IntelliSense**.

---

## Step 3 — Install dependency

```bash
composer install
```

Perintah ini mengunduh folder `vendor`. Butuh koneksi internet dan biasanya memakan waktu 1 sampai 5 menit. Tunggu sampai muncul tulisan `Generating optimized autoload files`.

Node.js **tidak wajib** karena hasil build CSS sudah disertakan. Kamu hanya perlu menjalankan perintah berikut kalau ingin mengubah tampilan:

```bash
npm install
npm run build
```

---

## Step 4 — Environment

File `.env` menyimpan konfigurasi rahasia seperti password database. File ini sengaja tidak ikut di dalam ZIP, jadi kamu harus membuatnya sendiri dari contoh yang sudah disediakan.

**Windows (Command Prompt atau terminal Laragon):**

```bash
copy .env.example .env
```

**Linux, Mac, atau Git Bash:**

```bash
cp .env.example .env
```

Lalu buat kunci enkripsi aplikasi:

```bash
php artisan key:generate
```

Kalau berhasil, baris `APP_KEY=` di dalam `.env` akan terisi dengan deretan karakter acak yang diawali `base64:`.

---

## Step 5 — Database

### 1. Buat database kosong

**Lewat Laragon (paling cepat):** klik kanan jendela Laragon → **MySQL** → **phpMyAdmin**. Login dengan username `root` dan password kosong. Klik tab **Databases**, ketik `ekantin`, pilih collation `utf8mb4_unicode_ci`, lalu klik **Create**.

**Lewat terminal:**

```bash
mysql -u root -p -e "CREATE DATABASE ekantin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Sesuaikan `.env`

Buka file `.env` di VS Code, lalu pastikan bagian ini cocok dengan pengaturan MySQL di komputermu:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ekantin
DB_USERNAME=root
DB_PASSWORD=
```

Di Laragon dan XAMPP standar, `DB_USERNAME` adalah `root` dan `DB_PASSWORD` dikosongkan. Kalau kamu pernah memasang password untuk MySQL, isikan di situ.

### 3. Jalankan migration

```bash
php artisan migrate
```

Migration akan membuat seluruh tabel: `users`, `students`, `canteens`, `categories`, `products`, `orders`, `order_items`, dan tabel bantu Laravel.

Kalau muncul pertanyaan `Do you really wish to run this command?`, ketik `yes` lalu Enter.

---

## Step 6 — Seeder

Seeder mengisi database dengan data demo supaya kamu bisa langsung mencoba aplikasinya.

```bash
php artisan db:seed
```

Kalau ingin mengulang dari nol (menghapus semua data lalu mengisi ulang):

```bash
php artisan migrate:fresh --seed
```

### Akun demo yang dihasilkan

| Role | Username / NIS | Password |
|---|---|---|
| Admin | `admin` | `password` |
| Penjual Kantin Bu Sri (kode A) | `penjual1` | `password` |
| Penjual Kantin Pak Budi (kode B) | `penjual2` | `password` |
| Siswa — Ahmad Fauzi, XI RPL 1 | `2024001` | `password` |
| Siswa — Siti Nurhaliza, XI RPL 1 | `2024002` | `password` |
| Siswa — Rizky Pratama, XI RPL 2 | `2024003` | `password` |
| Siswa — Dewi Lestari, XII TKJ 1 | `2024004` | `password` |
| Siswa — Bagas Saputra, X RPL 1 | `2024005` | `password` |

Seeder juga membuat 5 kategori dan 12 menu makanan Indonesia yang terbagi di dua kantin.

> **Penting.** Semua password di atas adalah `password` dan hanya untuk keperluan demo dan pengujian. Kalau aplikasi ini benar-benar dipakai di sekolah, semua password wajib diganti lebih dulu. Caranya ada di bagian [Cara melakukan kustomisasi](#cara-melakukan-kustomisasi). Jangan pernah mengunggah file `.env` berisi password asli ke GitHub.

---

## Step 7 — Storage link

Foto menu yang diunggah penjual disimpan di `storage/app/public`, sementara browser hanya bisa membaca isi folder `public`. Perintah berikut membuat jembatan di antara keduanya:

```bash
php artisan storage:link
```

Kalau berhasil, akan muncul folder pintasan `public/storage`. Tanpa langkah ini, semua foto menu akan tampil rusak.

> Di Windows, perintah ini kadang butuh hak administrator. Kalau gagal, tutup terminal lalu buka ulang dengan klik kanan → **Run as administrator**.

---

## Step 8 — Menjalankan aplikasi

```bash
php artisan serve
```

Terminal akan menampilkan alamat seperti:

```
INFO  Server running on [http://127.0.0.1:8000].
```

Buka alamat itu di browser. Kamu akan langsung diarahkan ke halaman login.

Biarkan terminal ini tetap terbuka selama aplikasi dipakai. Untuk menghentikan server, tekan **Ctrl + C**.

### Kapan perlu `npm run dev`?

Hanya kalau kamu sedang **mengubah tampilan** (file di folder `resources/views`, `resources/css`, atau `resources/js`) dan ingin perubahannya langsung terlihat tanpa build ulang.

Kalau kamu melakukannya, buka **terminal kedua** (terminal pertama tetap menjalankan `php artisan serve`) lalu jalankan:

```bash
npm run dev
```

Setelah selesai mengubah tampilan, hentikan `npm run dev` dengan Ctrl + C, lalu jalankan `npm run build` sekali untuk menyimpan hasil akhirnya.

Kalau kamu **tidak** mengubah tampilan, cukup `php artisan serve` saja.

---

## Step 9 — Checklist testing

Jalankan berurutan. Centang satu per satu, dan catat hasilnya. Checklist ini juga bisa langsung kamu salin ke laporan PKL sebagai bukti pengujian.

### A. Autentikasi dan hak akses

- [ ] Login admin dengan `admin` / `password`, masuk ke dashboard admin
- [ ] Login penjual dengan `penjual1` / `password`, masuk ke dashboard penjual
- [ ] Login siswa dengan `2024001` / `password`, masuk ke dashboard siswa
- [ ] Login dengan password salah, muncul pesan "Data login tidak ditemukan"
- [ ] Saat login sebagai siswa, buka `/admin/dashboard` di address bar — harus muncul halaman 403
- [ ] Saat login sebagai penjual, buka `/admin/students` — harus muncul halaman 403
- [ ] Logout mengembalikan ke halaman login

### B. Pengelolaan menu (login sebagai `penjual1`)

- [ ] Buka menu **Menu** → **Tambah menu**, isi semua kolom, unggah foto, simpan
- [ ] Menu baru muncul di daftar lengkap dengan fotonya
- [ ] Klik **Edit**, ubah harga, simpan, harga berubah
- [ ] Ubah stok lewat kotak Stok di kartu menu, klik Simpan, stok berubah
- [ ] Klik **Nonaktifkan**, menu berlabel "Tidak tersedia"
- [ ] Klik **Hapus**, muncul konfirmasi, menu hilang dari daftar

### C. Status kantin

- [ ] Sebagai `penjual1`, buka **Status kantin**, pilih **Tutup**, simpan
- [ ] Login sebagai siswa `2024001`, Kantin Bu Sri berlabel merah **Tutup**
- [ ] Buka halaman kantin tersebut, tombol Tambahkan tidak bisa diklik
- [ ] Kembali sebagai penjual, ubah ke **Tutup sementara**, siswa melihat pesan "Kantin sedang tidak menerima pesanan"
- [ ] Kembalikan ke **Buka**

### D. Alur pemesanan (login sebagai siswa `2024001`)

- [ ] Catat stok awal salah satu menu, misalnya Nasi Goreng Spesial = 20
- [ ] Buka detail menu, atur jumlah 2, tambahkan ke keranjang
- [ ] Keranjang menampilkan subtotal dan total yang benar
- [ ] Klik **Lanjut ke checkout**, ringkasan pesanan tampil benar
- [ ] Klik **Buat pesanan**
- [ ] Muncul nomor antrean besar, misalnya **A001**, status **Menunggu**
- [ ] Cek kembali stok menu tadi — harus berkurang menjadi 18
- [ ] Buat pesanan kedua dari akun siswa lain, nomor antrean menjadi **A002**
- [ ] Pesan dari kantin B, nomor antreannya dimulai dari **B001**, bukan A003

### E. Validasi stok

- [ ] Sebagai penjual, set stok sebuah menu menjadi 0
- [ ] Sebagai siswa, menu tersebut berlabel **Stok habis** dan tidak bisa dipesan
- [ ] Set stok menjadi 1, masukkan ke keranjang, lalu (sebagai penjual di tab lain) turunkan stok jadi 0
- [ ] Siswa menekan checkout — muncul pesan "Stok tidak mencukupi", pesanan tidak terbuat

### F. Smart Queue dan status pesanan

- [ ] Sebagai penjual, buka **Antrean**, pesanan tadi muncul di kolom Menunggu
- [ ] Klik pesanan → **Tandai diproses**, kartu pindah ke kolom Diproses
- [ ] Klik **Tandai siap diambil**
- [ ] Di jendela siswa yang masih membuka halaman pesanan, tunggu maksimal 7 detik — halaman berubah sendiri dan menampilkan "Pesanan A001 sudah siap diambil!"
- [ ] Sebagai penjual, klik **Tandai selesai**, pesanan hilang dari papan antrean

### G. Pembatalan dan pengembalian stok

- [ ] Sebagai siswa, buat pesanan baru, catat stoknya
- [ ] Klik **Batalkan pesanan** pada pesanan berstatus Menunggu
- [ ] Cek stok menu — harus kembali ke jumlah semula

### H. Riwayat

- [ ] Sebagai siswa, buka **Pesanan**, semua pesanan tadi terdaftar dengan tanggal dan status
- [ ] Buka salah satunya, rincian menu dan total tampil benar
- [ ] Sebagai penjual, ubah harga menu yang pernah dipesan
- [ ] Buka lagi riwayat siswa — harga di riwayat **tidak berubah** (ini membuktikan snapshot harga berfungsi)
- [ ] Sebagai penjual, buka **Riwayat**, filter tanggal, total pemasukan tampil

### I. Admin

- [ ] Dashboard menampilkan jumlah siswa, kantin, menu, dan pesanan hari ini
- [ ] Tambah siswa baru, lalu login memakai NIS siswa tersebut
- [ ] Tambah penjual baru, lalu tambah kantin dan hubungkan ke penjual itu
- [ ] Tambah kategori baru, kategori muncul di form tambah menu penjual
- [ ] Halaman **Pesanan** bisa difilter per kantin, status, dan tanggal

### J. Responsif

- [ ] Buka aplikasi di ponsel (atau tekan F12 → ikon ponsel di browser)
- [ ] Navigasi bawah (Kantin / Keranjang / Pesanan) muncul di layar kecil
- [ ] Semua tabel bisa digeser ke samping tanpa merusak tata letak
- [ ] Nomor antrean tetap terbaca besar dan jelas

### Pengujian otomatis (opsional, nilai tambah untuk laporan)

Project ini menyertakan dua pengujian otomatis untuk aturan paling kritis: stok berkurang setelah checkout, dan pesanan melebihi stok harus ditolak.

```bash
# Buat dulu database kosong bernama ekantin_testing
php artisan test
```

---

## Step 10 — Troubleshooting

### 1. `No application encryption key has been specified` / APP_KEY missing

**Penyebab:** file `.env` belum ada, atau baris `APP_KEY` masih kosong.
**Solusi:** pastikan `.env` sudah dibuat dari `.env.example`, lalu buat kuncinya.

```bash
php artisan key:generate
php artisan config:clear
```

---

### 2. `Failed to open stream: vendor/autoload.php`

**Penyebab:** folder `vendor` belum ada karena `composer install` belum dijalankan atau gagal di tengah jalan.
**Solusi:**

```bash
composer install
```

Kalau gagal karena timeout, coba `composer install --prefer-dist -vvv` untuk melihat di bagian mana prosesnya berhenti. Kalau muncul pesan tentang extension yang kurang (misalnya `ext-fileinfo`), aktifkan extension tersebut di `php.ini` lebih dulu.

---

### 3. `SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'`

**Penyebab:** username atau password MySQL di `.env` tidak cocok.
**Solusi:** buka `.env`, samakan `DB_USERNAME` dan `DB_PASSWORD` dengan pengaturan MySQL-mu. Di Laragon dan XAMPP standar, username `root` dengan password kosong. Setelah mengubah `.env`:

```bash
php artisan config:clear
```

---

### 4. `SQLSTATE[HY000] [1049] Unknown database 'ekantin'`

**Penyebab:** database-nya memang belum dibuat.
**Solusi:** buat database `ekantin` lewat phpMyAdmin atau perintah di [Step 5](#step-5--database). Perhatikan juga huruf besar-kecil nama database.

---

### 5. `SQLSTATE[HY000] [2002] Connection refused`

**Penyebab:** service MySQL belum berjalan.
**Solusi:** buka Laragon, klik **Start All**, pastikan indikator MySQL berwarna hijau. Kalau memakai XAMPP, klik **Start** pada baris MySQL di control panel.

---

### 6. `Syntax error or access violation: 1071 Specified key was too long`

**Penyebab:** versi MySQL lama (di bawah 5.7.7) tidak mendukung panjang index bawaan Laravel.
**Solusi:** tambahkan baris berikut di dalam method `boot()` pada `app/Providers/AppServiceProvider.php`:

```php
\Illuminate\Support\Facades\Schema::defaultStringLength(191);
```

Lalu ulangi:

```bash
php artisan migrate:fresh --seed
```

---

### 7. Migration error atau tabel setengah jadi

**Penyebab:** migration gagal di tengah sehingga sebagian tabel sudah terbuat.
**Solusi (hati-hati, ini menghapus semua data):**

```bash
php artisan migrate:fresh --seed
```

---

### 8. Foto menu tidak muncul, hanya ikon gambar rusak

**Penyebab:** symbolic link `public/storage` belum dibuat, atau `APP_URL` di `.env` tidak sesuai.
**Solusi:**

```bash
php artisan storage:link
```

Kalau link sudah ada tapi tetap tidak muncul, hapus folder `public/storage` lalu jalankan perintah di atas lagi. Pastikan juga `APP_URL` di `.env` sama dengan alamat yang kamu buka di browser, misalnya `http://localhost:8000`.

---

### 9. Tampilan berantakan, tidak ada warna sama sekali

**Penyebab:** file CSS hasil build tidak ditemukan, atau `npm run dev` pernah dijalankan lalu dihentikan sehingga Laravel masih mencari server Vite.
**Solusi:** pastikan folder `public/build` ada dan berisi `manifest.json`. Kalau tidak ada:

```bash
npm install
npm run build
```

Kalau ada file `public/hot`, hapus file tersebut. File itu penanda bahwa `npm run dev` sedang berjalan.

---

### 10. `npm error code ENOENT` atau `npm is not recognized`

**Penyebab:** Node.js belum terpasang.
**Solusi:** untuk menjalankan aplikasi ini, Node.js sebenarnya **tidak wajib** karena `public/build` sudah disertakan. Abaikan saja. Kalau memang perlu, pasang Node.js versi LTS dari nodejs.org lalu tutup dan buka ulang terminal.

---

### 11. `The stream or file "storage/logs/laravel.log" could not be opened` (permission error)

**Penyebab:** folder `storage` atau `bootstrap/cache` tidak bisa ditulis. Umumnya terjadi di Linux atau Mac.
**Solusi:**

```bash
chmod -R 775 storage bootstrap/cache
```

Di Windows, klik kanan folder `storage` → Properties → Security → pastikan user kamu punya izin Write.

---

### 12. `Failed to listen on 127.0.0.1:8000 (address already in use)`

**Penyebab:** port 8000 sedang dipakai aplikasi lain, biasanya `php artisan serve` yang lupa dihentikan.
**Solusi:** jalankan di port lain.

```bash
php artisan serve --port=8080
```

---

### 13. `'composer' is not recognized` atau `'php' is not recognized`

**Penyebab:** PHP atau Composer tidak ada di PATH Windows.
**Solusi termudah:** jangan pakai Command Prompt biasa. Buka terminal lewat Laragon (klik kanan jendela Laragon → Terminal), karena terminal Laragon sudah otomatis mengenali PHP dan Composer.
**Solusi permanen:** tambahkan folder PHP (misalnya `C:\laragon\bin\php\php-8.2.x`) ke Environment Variables → Path, lalu buka ulang terminal.

---

### 14. Perubahan di `.env` atau kode tidak terasa

**Penyebab:** Laravel menyimpan cache konfigurasi dan tampilan.
**Solusi:**

```bash
php artisan optimize:clear
```

---

### 15. Halaman 403 "Kamu tidak punya akses ke halaman ini"

**Penyebab:** ini bukan bug. Middleware role sedang bekerja sesuai rancangan, misalnya siswa mencoba membuka halaman admin.
**Solusi:** logout, lalu login dengan akun yang sesuai role halaman tersebut.

---

### 16. Penjual melihat pesan "Akun penjual ini belum dihubungkan ke kantin mana pun"

**Penyebab:** akun penjual sudah dibuat tapi belum dikaitkan ke kantin.
**Solusi:** login sebagai admin → menu **Kantin** → **Tambah kantin** → pilih penjual tersebut pada kolom Penjual pemilik.

---

## Roadmap sampai final project

```text
ZIP
 ↓
Extract
 ↓
Install dependency (composer install)
 ↓
Configure .env (copy + key:generate)
 ↓
Create database (ekantin)
 ↓
Migration (php artisan migrate)
 ↓
Seeder (php artisan db:seed)
 ↓
Storage link (php artisan storage:link)
 ↓
Run Laravel (php artisan serve)
 ↓
Testing (checklist Step 9)
 ↓
Fix bugs
 ↓
UI refinement (warna, logo, nama sekolah)
 ↓
Security check (ganti semua password demo)
 ↓
Final testing (ulangi checklist dari awal)
 ↓
Deployment (hosting / server sekolah)
 ↓
FINAL PROJECT
```

### Catatan singkat soal deployment

Kalau project ini akan dipasang di hosting atau server sekolah, ada empat hal yang wajib diubah:

1. Di `.env`: `APP_ENV=production`, `APP_DEBUG=false`. Ini penting karena `APP_DEBUG=true` di server publik akan membocorkan isi konfigurasi saat terjadi error.
2. Arahkan document root web server ke folder `public`, bukan ke folder utama project.
3. Ganti semua password demo.
4. Jalankan `php artisan config:cache` dan `php artisan route:cache` supaya aplikasi lebih cepat.

---

## Cara melakukan kustomisasi

### Mengubah nama aplikasi

Buka `.env`, ubah baris pertama:

```env
APP_NAME="Kantin SMKN 1 Depok"
```

Lalu jalankan `php artisan config:clear`. Nama ini otomatis muncul di navbar dan judul halaman.

### Mengubah warna tema

Semua warna oranye berasal dari satu tempat: `tailwind.config.js`, bagian `colors.brand`. Ganti nilai hex-nya dengan warna sekolahmu, misalnya biru:

```js
brand: {
    50: '#eff6ff',  100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd',
    400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
    800: '#1e40af', 900: '#1e3a8a',
},
```

Lalu build ulang:

```bash
npm install
npm run build
```

Cara mudah mendapatkan sepuluh tingkat warna: buka palette generator Tailwind, atau salin salah satu palet bawaan Tailwind (blue, emerald, rose, dan sebagainya).

### Mengganti logo

Logo saat ini berupa kotak oranye bertuliskan "EK". Letaknya di dua file: `resources/views/layouts/partials/nav-student.blade.php` dan `resources/views/layouts/partials/nav-bar.blade.php`.

Untuk menggantinya dengan gambar, simpan file logo di `public/img/logo.png`, lalu ganti bagian `<span class="grid h-9 w-9 ...">EK</span>` menjadi:

```blade
<img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-9 w-9 rounded-xl object-cover">
```

### Menambah siswa

Dua cara. **Satu per satu:** login sebagai admin → **Siswa** → **Tambah siswa**. **Banyak sekaligus:** buka `database/seeders/StudentSeeder.php`, tambahkan baris ke dalam array `$students`, lalu jalankan `php artisan db:seed --class=StudentSeeder`. Seeder ini aman dijalankan berulang karena memakai `updateOrCreate`, jadi data lama tidak tergandakan.

### Menambah kantin

Login sebagai admin. Buat dulu akun penjualnya lewat menu **Penjual**, baru buat kantinnya lewat menu **Kantin** dan pilih penjual tadi sebagai pemilik.

Kolom **Kode kantin** menentukan awalan nomor antrean. Kode `C` akan menghasilkan nomor antrean C001, C002, dan seterusnya. Setiap kantin harus punya kode yang berbeda.

### Menambah menu

Login sebagai penjual → **Menu** → **Tambah menu**. Harga ditulis angka saja tanpa titik, misalnya `15000`.

### Mengganti akun admin atau password

**Lewat aplikasi (paling mudah):** login dengan akun yang ingin diubah, klik nama kamu di pojok kanan atas → **Ganti password**.

**Lewat terminal, kalau password admin lupa:**

```bash
php artisan tinker
```

Lalu ketik:

```php
$u = App\Models\User::where('username', 'admin')->first();
$u->password = 'PasswordBaruYangKuat';
$u->save();
exit
```

Password otomatis di-hash oleh model, jadi tidak perlu memanggil `Hash::make` sendiri.

**Mengganti username admin:** ubah `database/seeders/AdminSeeder.php` sebelum seeding, atau ubah langsung lewat tinker seperti di atas dengan mengganti `$u->username`.

### Mengubah data sekolah

Nama sekolah saat ini hanya muncul di dua tempat: `APP_NAME` pada `.env`, dan baris kecil di bagian bawah halaman login (`resources/views/auth/login.blade.php`). Ubah keduanya sesuai sekolahmu.

### Mengubah ambang batas "stok menipis"

Dashboard penjual menandai menu dengan stok 5 atau kurang. Angka ini ada di `app/Http/Controllers/Seller/DashboardController.php` pada baris `lowStock(5)`.

### Mengubah kecepatan pengecekan status pesanan

Saat ini setiap 7 detik. Ubah angka `7000` (milidetik) di bagian akhir `resources/js/app.js`, lalu jalankan `npm run build`. Jangan dibuat terlalu kecil karena akan membebani server.

---

## Struktur folder

```
E-Kantin/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/          Login dan ganti password
│   │   │   ├── Student/       Dashboard, kantin, keranjang, checkout, pesanan
│   │   │   ├── Seller/        Dashboard, menu, pesanan, antrean, status, riwayat
│   │   │   └── Admin/         Dashboard, siswa, penjual, kantin, kategori, monitoring
│   │   ├── Middleware/
│   │   │   └── EnsureUserHasRole.php    Pembatas akses berdasarkan role
│   │   └── Requests/
│   │       └── ProductRequest.php       Aturan validasi form menu
│   ├── Models/                User, Student, Canteen, Category, Product, Order, OrderItem
│   ├── Services/
│   │   └── OrderService.php   Inti logika checkout, stok, dan nomor antrean
│   ├── Support/
│   │   ├── Cart.php           Keranjang berbasis session
│   │   └── helpers.php        Fungsi rupiah()
│   └── Providers/
├── bootstrap/app.php          Pendaftaran middleware dan routing
├── config/                    Konfigurasi app, auth, database, dan lainnya
├── database/
│   ├── migrations/            Struktur tabel
│   └── seeders/               Data demo
├── public/
│   ├── index.php              Pintu masuk aplikasi
│   └── build/                 Hasil compile CSS dan JS (sudah disertakan)
├── resources/
│   ├── css/app.css            Tailwind dan komponen CSS
│   ├── js/app.js              Konfirmasi hapus, dropdown, polling status
│   └── views/
│       ├── layouts/           Kerangka halaman dan navigasi per role
│       ├── components/        Badge status, notifikasi, gambar menu
│       ├── auth/ student/ seller/ admin/
├── routes/web.php             Seluruh rute, dikelompokkan per role
├── storage/                   Log, cache, dan foto menu yang diunggah
├── tests/Feature/             Pengujian otomatis stok dan checkout
├── .env.example               Contoh konfigurasi
├── composer.json              Daftar library PHP
├── package.json               Daftar library frontend
└── tailwind.config.js         Warna tema
```

### Tiga file yang paling penting untuk dipahami

Kalau kamu ditanya penguji "bagian mana yang paling inti dari aplikasi ini", jawabannya ada di tiga file berikut.

**`app/Services/OrderService.php`** — seluruh aturan checkout ada di sini: pengecekan status kantin, penguncian baris produk agar stok tidak minus, pengurangan stok, pembuatan nomor antrean, dan pengembalian stok saat pembatalan.

**`app/Support/Cart.php`** — keranjang belanja berbasis session, termasuk aturan bahwa satu keranjang hanya boleh berisi menu dari satu kantin.

**`app/Http/Middleware/EnsureUserHasRole.php`** — penjaga pintu yang membuat siswa tidak bisa membuka halaman penjual maupun admin.

---

## Lisensi dan atribusi

Project ini dibuat untuk keperluan PKL. Laravel berlisensi MIT. Silakan dipakai, diubah, dan dikembangkan.
