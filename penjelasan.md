# Rencana & Dokumentasi Perubahan Proyek: Sistem Inventaris Mess Tanjung Lesung

Dokumen ini merangkum seluruh arsitektur, konsep perubahan, implementasi hak akses (Spatie & Laravel Breeze), verifikasi OTP email, serta panduan melanjutkan pengembangan (*development*) di perangkat lain.

---

## 1. Konsep & Filosofi Akses Sistem

Sistem ini dirancang khusus untuk inventaris mess kawasan wisata Tanjung Lesung dengan prinsip pemisahan peran yang efisien:

1. **Publik adalah Viewer (Tanpa Login):**
   - Semua pengunjung (tamu, staf umum, auditor) dapat langsung melihat halaman *Showcase Mess* (`/`) dan data *Inventaris Aset* (`/inventory`) dalam mode baca saja (*read-only*).
   - Pengunjung publik **tidak perlu membuat akun** hanya untuk melihat data.
2. **Akun Khusus Administrator Pengelola:**
   - Pembuatan akun dan login hanya diwajibkan bagi petugas/staf yang mengelola fisik aset (tambah barang baru, ubah status kondisi, unggah foto lapangan, atau hapus aset).
3. **Pendaftar Pertama adalah Administrator Utama:**
   - Pengguna pertama yang mendaftar ke sistem secara otomatis menjadi **Administrator Utama (Super Admin / Pemilik Kunci Akses)** dengan bendera `is_first_admin = true`.
4. **Otoritas Tunggal Persetujuan Hak Akses:**
   - Siapa pun yang mendaftar setelahnya akan berstatus **Viewer** dengan permohonan admin berstatus `pending`.
   - **Hanya Administrator Pertama** yang memiliki wewenang untuk menyetujui, menolak, atau mencabut hak akses administrator melalui halaman khusus di `/admin/access-requests`.
5. **Verifikasi Keaslian Email via OTP (100% Gratis):**
   - Sebelum permohonan admin diajukan ke Administrator Pertama, pendaftar wajib memverifikasi email dengan **kode OTP 6-digit**.
   - Ketika Administrator Pertama menyetujui akun tersebut, sistem otomatis mengirimkan **email pemberitahuan akun aktif** beserta detail login.

---

## 2. Alur Kerja (Workflow) Sistem Baru

```
[1. Pendaftaran Pengelola]
  👉 Pemohon mengisi formulir pendaftaran di /register (Breeze)
       │
[2. Pengiriman OTP 6-Digit]
  👉 Sistem membuat kode OTP unik (masa aktif 15 menit) & mengirim ke email pemohon
       │
[3. Verifikasi OTP]
  👉 Pemohon memasukkan 6 digit OTP di layar
  👉 Email diverifikasi (email_verified_at = now())
       │
       ├─► Jika Pendaftar Pertama : Otomatis jadi Administrator Utama (is_first_admin = true)
       │
       └─► Jika Pendaftar Selanjutnya: Akun dibuat dengan status 'pending'
             │
[4. Peninjauan oleh Administrator Pertama]
  👉 Administrator Utama menerima notifikasi badge di sidebar (/admin/access-requests)
  👉 Meninjau data pemohon & alasan pengajuan
       │
[5. Persetujuan & Notifikasi Akun]
  👉 Admin Utama menekan "Setujui Jadi Admin"
  👉 Role Spatie 'admin' aktif
  👉 Email resmi konfirmasi akun & tautan login otomatis terkirim ke inbox pemohon
```

---

## 3. Komponen & Berkas Perubahan

### A. Database & Migrasi
* `database/migrations/2026_10_27_183813_create_permission_tables.php`:
  Tabel bawaan Spatie Permission (`roles`, `permissions`, `model_has_roles`, dll.).
* `database/migrations/2026_10_31_155412_add_admin_approval_fields_to_users_table.php`:
  Kolom status pada tabel `users`: `is_first_admin`, `admin_request_status`, `admin_request_reason`, `admin_requested_at`, `admin_action_at`, `admin_action_by`.
* `database/migrations/2026_10_31_162107_create_email_otps_table.php`:
  Tabel sementara untuk menyimpan kode OTP, email tujuan, payload data akun, dan waktu kedaluwarsa.

### B. Model & Logika Otorisasi
* `app/Models/User.php`:
  Trait `HasRoles`, method `isFirstAdmin()`, `hasPendingAdminRequest()`, dan relasi `adminApprover()`.
* `app/Models/EmailOtp.php`:
  Model untuk validasi kecocokan kode OTP dan pengecekan kedaluwarsa.
* `app/Providers/AppServiceProvider.php`:
  Gate otorisasi aset dan pembatasan kemampuan persetujuan admin hanya untuk Administrator Pertama.

### C. Surat Elektronik (Mailables & Views)
* `app/Mail/SendOtpMail.php` & `resources/views/emails/send-otp.blade.php`:
  Template email pengiriman kode OTP 6-digit resmi bertema Tanjung Lesung.
* `app/Mail/AdminApprovedMail.php` & `resources/views/emails/admin-approved.blade.php`:
  Template email notifikasi akun disetujui, ringkasan kredensial login, dan tombol akses.

### D. Antarmuka Pengguna (UI & Frontend)
* `resources/views/livewire/pages/auth/register.blade.php`:
  Form registrasi 2-langkah standar Laravel Breeze (Livewire Volt): Input Data &rarr; Verifikasi OTP.
* `resources/views/livewire/pages/auth/login.blade.php`:
  Form login resmi Laravel Breeze yang fleksibel mendukung login via **Username** maupun **Email**.
* `resources/views/pages/admin/access-requests.blade.php`:
  Halaman eksklusif Administrator Pertama dengan tab permohonan pending, daftar pengguna, statistik, dan aksi setujui/tolak/cabut hak akses.
* `resources/views/livewire/profile/request-admin-access-form.blade.php`:
  Form pengajuan hak akses admin susulan pada halaman profil.
* `resources/views/layouts/app.blade.php`:
  Sidebar terintegrasi Mary UI dengan badge counter permohonan tertunda.

---

## 4. Konfigurasi Email (100% Gratis)

Sistem email ini tidak memerlukan biaya langganan:

### Mode Pengembangan Lokal (Tanpa Internet):
Secara default di `.env`:
```env
MAIL_MAILER=log
```
Setiap email OTP dan konfirmasi akan langsung tercatat di file `storage/logs/laravel.log`. Pantau dengan:
```bash
tail -f storage/logs/laravel.log
```

### Mode Produksi / Kirim ke Email Asli (Gratis 500 email/hari):
Gunakan SMTP Gmail biasa di `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=emailanda@gmail.com
MAIL_PASSWORD=sandi_aplikasi_google_16_karakter
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=emailanda@gmail.com
MAIL_FROM_NAME="Sistem Inventaris Mess Tanjung Lesung"
```

---

## 5. Panduan Melanjutkan Development di Perangkat Lain

Saat Anda mengunduh (*clone*) atau berpindah ke perangkat lain, ikuti langkah berikut:

### 1. Ambil Perubahan Terbaru dari GitHub
```bash
git fetch origin
git checkout feature/breeze-spatie
git pull origin feature/breeze-spatie
```

### 2. Pasang Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Environment (`.env`)
Pastikan file `.env` sudah ada dan sesuaikan koneksi database PostgreSQL Anda:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=mess_db
DB_USERNAME=postgres
DB_PASSWORD=rahasia
```

### 4. Jalankan Migrasi & Seeder
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder
```

### 5. Kompilasi Aset & Jalankan Server
```bash
npm run build
# Atau jalankan dev server bersamaan:
composer run dev
```

---

## 6. Skenario Pengujian (Testing Matrix)

1. **Uji Pengunjung Publik:** Buka `http://localhost:8000/` dan `/inventory` tanpa login. Semua data dan foto aset dapat dilihat normal.
2. **Uji Pendaftar Pertama:** Buka `/register` saat database kosong. Masukkan data & verifikasi OTP. Akun otomatis menjadi **👑 Administrator Pertama**.
3. **Uji Pendaftar Kedua:** Buka mode *incognito*, daftar akun baru dengan email lain & masukkan OTP. Akun akan berstatus **Viewer** dengan status permohonan **Pending**.
4. **Uji Persetujuan:** Login dengan akun Admin Pertama, klik menu **Persetujuan Admin** di sidebar, lalu klik **Setujui Jadi Admin**. Akun kedua otomatis menjadi Administrator dan email konfirmasi terkirim.
