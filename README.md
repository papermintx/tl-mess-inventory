# Sistem Informasi Showcase Mess & Inventarisasi Aset - Tanjung Lesung

Sistem web modern berbasis **Laravel 12**, **Livewire 4**, dan **Mary UI (DaisyUI 5 + Tailwind CSS v4)** untuk menampilkan akomodasi mess karyawan/peserta magang di Tanjung Lesung serta tata kelola inventarisasi aset fisik dengan standar keamanan dan otorisasi *Role-Based Access Control* (RBAC).

---

## 🌟 Fitur Utama

### 1. Showcase Akomodasi Mess (Landing Page)
- **Hero Banner Sinematik**: Latar pemandangan *sunset golden hour* pesisir Tanjung Lesung dengan tipografi kontras tinggi yang nyaman dibaca.
- **Ringkasan Metrik Cepat**: Statistik total ruangan, aset terdata, kapasitas penghuni, serta persentase kondisi aset siap pakai.
- **Fasilitas Standar Mess**: Kartu fasilitas komunal dan privat (kamar berpendingin, ruang rekreasi/TV, dapur & dispenser air).
- **Tata Letak Kamar & Area Mess (Rata Tengah)**:
  - Disusun simetris dan proporsional menggunakan flexbox `justify-center`.
  - Dilengkapi foto aspek lanskap (16:9), badge kode ruangan, kapasitas orang, deskripsi, dan penghitung aset fisik terdaftar secara dinamis.
- **Footer Resmi 4 Kolom**:
  - Disesuaikan dengan palet warna utama (*Deep Navy Blue* `#0d47a1`).
  - Ornamen lengkungan gelombang laut (*wave curve*).
  - Tautan navigasi (*Explore*), saluran resmi (*Official Sites*), serta kontak reservasi pengelola: **`+62 838-7200-3995`** (WhatsApp & Telepon).

### 2. Pengelolaan Inventaris Aset (Livewire 4 SPA)
- **Tabel Responsif & Proporsional**:
  - Penomoran kode aset monospace (`AST-KMR01-001`).
  - Thumbnail gambar fisik asli pada barang yang memiliki dokumentasi foto.
  - Pemotongan teks otomatis dengan elipsis (`...`) pada deskripsi dan nama ruangan agar tabel rapi dan tidak memicu bilah gulir horizontal (*no unnecessary scrollbar*).
  - Tooltip *hover* untuk membaca deskripsi lengkap.
- **Filter & Pencarian Multikriteria**:
  - Pencarian instan (debounce) berdasarkan kode atau nama barang.
  - Filter lokasi per ruangan master.
  - Filter status kondisi fisik (*Bagus*, *Perlu Perbaikan*, *Rusak*).
- **Inspeksi & Galeri Foto Lapangan (Modal Detail)**:
  - Galeri foto dokumentasi fisik resolusi tinggi beserta catatan kondisi dan identitas pengunggah (*Audit Trail*).
- **Paginasi Bebas Tabrakan (*Safe-Area Pagination*)**:
  - Tata letak paginasi diatur dengan jarak aman agar tombol navigasi nomor halaman tidak tertimpa oleh *Floating Action Button*.
  - Tombol aksi ganda: Administrator dapat menambah aset melalui *Header Action* di bagian atas maupun *Floating Button* di sudut kanan bawah.

### 3. Arsitektur Data Ruangan & Fasilitas Tanpa Beban Database (*In-Memory*)
- Data master **7 Ruangan Mess** dan **Fasilitas** diakses langsung melalui model in-memory (`App\Models\Room` dan `App\Models\Facility`), sehingga sangat cepat dan bebas dari kueri tabel database yang berulang.
- Database relasional murni dikhususkan untuk **Akun Pengguna (`users`)**, **Data Inventaris Aset (`assets`)**, dan **Dokumentasi Foto Fisik (`asset_photos`)**.

---

## 🛏️ Daftar Data Master Ruangan Mess

| Kode Ruangan | Nama Ruangan / Area | Kapasitas | Foto Cover |
| :--- | :--- | :---: | :--- |
| `KMR-01` | Kamar Cowok 01 (Area Depan) | 1 Orang | `/images/kamar-depan.jpeg` |
| `KMR-03` | Kamar Cowok 03 (Area Tengah) | 1 Orang | `/images/kamar-tengah.jpeg` |
| `KMR-06` | Kamar Cowok 06 (Area Belakang) | 1 Orang | `/images/kamar-belakang.jpeg` |
| `COMM-01` | Ruang Tengah & Bersama | 10 Orang | `/images/kumpul.jpeg` |
| `KIT-01` | Dapur & Area Makan | 1 Orang | `/images/masak-masak.jpeg` |
| `BTH-01` | Kamar Mandi & Toilet Depan | 1 Orang | `/images/mandi-depan.jpeg` |
| `BTH-02` | Kamar Mandi & Toilet Belakang | 1 Orang | `/images/mandi-belakang.jpeg` |

---

## 👥 Hak Akses & Akun Pengguna Bawaan (Default Credentials)

Sistem menerapkan prinsip *Separation of Duties* (SoD) dan *Role-Based Access Control* (RBAC) ketat:

| Peran (Role) | Hak Akses | Kredensial Login |
| :--- | :--- | :--- |
| **Administrator (`admin`)** | Hak akses penuh: Tambah/Ubah/Hapus aset, unggah dokumentasi foto fisik lapangan, serta pencatatan audit log sistem. | **Username:** `admin`<br>**Password:** `password` |
| **Viewer / Staff (`viewer`)** | Hak inspeksi: Melihat inventaris, membuka galeri foto fisik, dan monitoring kondisi barang (Mode Read-Only). | **Username:** `viewer`<br>**Password:** `password` |
| **Publik / Guest** | Akses publik: Mengakses showcase landing page dan daftar inventaris tanpa perlu login. | Akses langsung tanpa login |

---

## 🛠️ Tumpukan Teknologi (Tech Stack)

- **Backend**: PHP 8.3+, [Laravel 12](https://laravel.com)
- **Reaktivitas UI**: [Livewire 4](https://livewire.laravel.com), [Mary UI 2.9](https://mary-ui.com)
- **Styling & Tema**: [Tailwind CSS v4](https://tailwindcss.com), [DaisyUI 5](https://daisyui.com)
- **Database**: PostgreSQL (atau SQLite / MySQL sesuai konfigurasi lingkungan)
- **Frontend Bundler**: [Vite 8](https://vitejs.dev)
- **Pengujian & Standar Kode**: PHPUnit 12, Laravel Pint

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Kloning Repositori
```bash
git clone <url-repositori-anda>
cd tl-mess-inventory
```

### 2. Instalasi Dependensi PHP & JavaScript
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan (.env)
Salin berkas contoh lingkungan dan generate app key:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan parameter koneksi database pada `.env` (contoh untuk PostgreSQL):
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=mess_db
DB_USERNAME=nama_user_anda
DB_PASSWORD=password_anda
```

### 4. Tautkan Storage Simbolik
Pastikan folder berkas publik tertaut ke storage penyimpanan foto fisik:
```bash
php artisan storage:link
```

### 5. Jalankan Migrasi & Database Seeder
Jalankan migrasi bersih beserta data mock inventaris dan akun:
```bash
php artisan migrate:fresh --seed
```

### 6. Kompilasi Aset Frontend
Untuk mode produksi:
```bash
npm run build
```

Untuk mode pengembangan aktif:
```bash
npm run dev
```

### 7. Jalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui peramban: `http://localhost:8000`

---

## 🧪 Pengujian & Kualitas Kode

Jalankan seluruh rangkaian pengujian fitur dan unit:
```bash
php artisan test
```

Pastikan format kode memenuhi standar PSR-12 dan gaya Laravel:
```bash
vendor/bin/pint --format agent
```

---

## 📄 Lisensi
Proyek ini dikembangkan secara tertutup untuk kebutuhan operasional Mess Tanjung Lesung.
Semua hak cipta dilindungi undang-undang © 2026 Mess Tanjung Lesung.
