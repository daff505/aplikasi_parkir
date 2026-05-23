<h1 align="center">🅿️ Aplikasi Parkir Digital</h1>

<p align="center">
  Sistem Manajemen Parkir berbasis web yang modern, lengkap, dan siap deploy.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Midtrans-Payment-00B1E1?style=for-the-badge" alt="Midtrans">
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker">
</p>

---

## 📖 Tentang Proyek

**Aplikasi Parkir Digital** adalah sistem manajemen parkir berbasis web yang dibangun menggunakan framework **Laravel**. Aplikasi ini dirancang untuk mengelola seluruh siklus transaksi parkir — mulai dari kendaraan masuk, pencatatan otomatis, hingga pembayaran dan pencetakan struk — dengan dukungan berbagai metode pembayaran termasuk **QRIS via Midtrans**.

Sistem ini mendukung **3 peran pengguna** dengan hak akses berbeda, menjadikannya cocok untuk digunakan oleh pengelola parkir skala kecil hingga menengah.

---

## ✨ Fitur Utama

### 🚗 Transaksi Kendaraan
- **Kendaraan Masuk** — Catat plat nomor, jenis kendaraan (motor/mobil/truk), area parkir, pemilik, dan merk/warna.
- **Generate Tiket Otomatis** — Setiap kendaraan masuk mendapat nomor tiket unik format `TKT-YYYYMMDD-XXXXX`.
- **Kendaraan Keluar** — Cari transaksi via nomor tiket atau plat nomor, hitung durasi & biaya secara otomatis.
- **Kalkulasi Biaya Dinamis** — Biaya dihitung per jam berdasarkan jenis kendaraan + **sistem denda otomatis** jika melewati batas waktu yang dikonfigurasi.
- **Cetak Struk Digital** — Struk berisi detail lengkap transaksi, dapat dikonfigurasi nama & header sesuai kebutuhan.

### 💳 Pembayaran
- **Tunai** — Pembayaran langsung di kasir.
- **QRIS (via Midtrans Snap)** — Integrasi payment gateway Midtrans untuk pembayaran digital dengan QR Code.
- **Debit & Kartu Kredit** — Opsi metode pembayaran tambahan.

### 👥 Multi-Role User
| Role | Akses |
|------|-------|
| **Admin** | Kelola semua data: user, area, tarif, kendaraan, pengaturan, log aktivitas |
| **Petugas** | Proses transaksi masuk/keluar, akses laporan |
| **Owner** | Lihat pendapatan bulanan, volume kendaraan, riwayat transaksi |

### 📊 Dashboard & Laporan
- **Dashboard Admin** — Statistik total area, pengguna, kendaraan, transaksi + grafik pendapatan 7 hari terakhir.
- **Dashboard Owner** — Pendapatan bulan ini, total volume kendaraan, kapasitas terisi, transaksi terbaru.
- **Laporan** — Rekapitulasi transaksi untuk Admin & Petugas.
- **Log Aktivitas** — Rekam jejak setiap aksi yang dilakukan pengguna di sistem.

### ⚙️ Pengaturan Sistem
- Nama aplikasi & header struk yang dapat diubah secara dinamis.
- Konfigurasi tarif per jam berdasarkan jenis kendaraan.
- Pengaturan batas waktu & denda per jam jika parkir terlalu lama.
- Manajemen area parkir dengan kapasitas & status (aktif/nonaktif).

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend Framework | Laravel 11 |
| Bahasa | PHP 8.2 |
| Database | MySQL |
| Frontend Build | Vite + Blade |
| Payment Gateway | Midtrans Snap API |
| Deployment | Docker, Render, Railway |

---

## ⚙️ Cara Kerja Sistem

### Alur Kendaraan Masuk
```
Petugas input data kendaraan
       ↓
Sistem cek/buat data kendaraan di tb_kendaraan
       ↓
Ambil tarif sesuai jenis kendaraan dari tb_tarif
       ↓
Generate Nomor Tiket unik (TKT-YYYYMMDD-XXXXX)
       ↓
Simpan transaksi ke tb_transaksi (status: masuk)
       ↓
Database trigger update jumlah terisi di tb_area_parkir
       ↓
Log aktivitas dicatat di tb_log_aktivitas
```

### Alur Kendaraan Keluar & Pembayaran
```
Petugas cari tiket (nomor tiket / plat nomor)
       ↓
Sistem hitung durasi: ceil(menit / 60) → dalam jam
       ↓
Biaya = Durasi × Tarif/Jam
       ↓
Jika durasi > batas waktu → tambah Denda per jam
       ↓
Pilih metode bayar (Tunai / QRIS / Debit / Kredit)
       ↓
[Jika QRIS] → Request Snap Token ke Midtrans API
            → User scan QR → Midtrans kirim callback
            → Status transaksi otomatis diupdate
       ↓
Update tb_transaksi (status: keluar, waktu keluar, biaya total)
       ↓
Generate & simpan Struk ke tb_struk
       ↓
Redirect ke halaman cetak struk
```

---

## 🚀 Instalasi Lokal

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM

### Langkah-langkah

```bash
# 1. Clone repositori
git clone https://github.com/daff505/aplikasi_parkir.git
cd aplikasi_parkir/parkir_app

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Konfigurasi database di .env
# DB_DATABASE=db_aplikasi_parkir
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Import database
# Import file: db_aplikasi_parkir.sql ke MySQL Anda

# 8. Build assets
npm run dev

# 9. Jalankan server
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

---

## 🐳 Deploy dengan Docker

```bash
# Build image
docker build -t aplikasi-parkir .

# Jalankan container
docker run -p 8000:8000 \
  -e DB_HOST=your_db_host \
  -e DB_DATABASE=db_aplikasi_parkir \
  -e DB_USERNAME=your_user \
  -e DB_PASSWORD=your_password \
  aplikasi-parkir
```

Deploy juga tersedia untuk **Render** (via `render.yaml`) dan **Railway** (via `railway.toml` + `nixpacks.toml`).

---

## 🔐 Konfigurasi Midtrans

Tambahkan key berikut ke `.env` untuk mengaktifkan pembayaran QRIS:

```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

Daftar akun Midtrans di [https://midtrans.com](https://midtrans.com) untuk mendapatkan API Key.

---

## 📁 Struktur Database Utama

| Tabel | Deskripsi |
|-------|-----------|
| `tb_user` | Data pengguna (admin, petugas, owner) |
| `tb_area_parkir` | Area/zona parkir beserta kapasitas |
| `tb_kendaraan` | Master data kendaraan |
| `tb_tarif` | Tarif parkir per jenis kendaraan |
| `tb_transaksi` | Rekam semua transaksi masuk/keluar |
| `tb_struk` | Struk/bukti pembayaran |
| `tb_pengaturan` | Konfigurasi aplikasi (nama, denda, dll) |
| `tb_log_aktivitas` | Jejak aktivitas pengguna |

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademis. Bebas digunakan dan dimodifikasi.

---

<p align="center">Dibuat dengan ❤️ menggunakan Laravel</p>
