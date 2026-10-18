# Sistem Informasi Showcase Mess & Inventarisasi Aset - Tanjung Lesung

Sistem web modern berbasis **Laravel 12**, **Livewire 4**, dan **Mary UI (DaisyUI 5 + Tailwind CSS v4)** untuk menampilkan akomodasi mess karyawan/peserta magang di Tanjung Lesung serta tata kelola inventarisasi aset fisik dengan standar keamanan dan otorisasi *Role-Based Access Control* (RBAC).

---

## 🌟 Fitur Utama

### 1. Showcase Akomodasi Mess (Landing Page)
- **Hero Banner Sinematik**: Latar pemandangan *sunset golden hour* pesisir Tanjung Lesung dengan tipografi kontras tinggi yang nyaman dibaca.

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

### 3. Arsitektur Data Ruangan
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

## 👥 Hak Akses & Tata Kelola Pengguna

Sistem dirancang dengan prinsip kemudahan akses informasi dan keamanan bertingkat:

1. **Viewer (Default - Seluruh Pengunjung / Tanpa Perlu Login)**
   - Semua orang yang membuka aplikasi secara otomatis bertindak sebagai **Viewer**.
   - **Tidak perlu login sama sekali**.
   - Hak akses: Menjelajahi *landing page showcase*, membuka katalog daftar inventaris mess, melakukan pencarian & filter data, mengecek status kondisi barang, serta membuka galeri foto fisik lapangan dalam mode baca (*Read-Only*).

2. **Pengelola / Admin (Khusus Petugas - Memerlukan Login)**
   - Satu-satunya pihak yang **wajib melakukan login**.
   - Hak akses penuh: Mengelola daftar inventaris (menambah aset baru, memperbarui informasi & kondisi fisik barang, mengunggah dokumentasi foto lapangan, dan menghapus aset).
   - **Kredensial Login Pengelola:**
     - **Username:** `admin`
     - **Password:** `password`

| Peran (Role) | Akses Login | Hak Akses Utama |
| :--- | :--- | :--- |
| **Viewer (Default)** | **Tanpa Login** (Akses Langsung) | Peninjauan inventaris, cek status barang, filter ruangan, galeri foto fisik (*Read-Only*) |
| **Pengelola / Admin (`admin`)** | **Wajib Login** (`admin` / `password`) | Hak akses penuh: Tambah, edit, hapus aset inventaris, upload foto fisik lapangan |

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
Jalankan server pengembangan dengan dukungan unggah foto fisik lapangan hingga 10MB:
```bash
composer run serve
# atau jalankan mode pengembangan lengkap (Laravel + Vite bersamaan):
composer run dev
```
*Catatan: Jika menjalankan perintah `php artisan serve` secara langsung tanpa composer, sertakan flag batas upload:*
```bash
php -d upload_max_filesize=10M -d post_max_size=12M artisan serve
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
