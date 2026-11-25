# KasKu

**Sistem Transparansi Kas Kelas & Dana Event Sekolah** — aplikasi web Laravel fullstack monolith untuk mencatat pemasukan, pengeluaran, tagihan kas, dan verifikasi bukti pembayaran siswa secara terbuka.

Halaman transparansi dapat diakses tanpa login, sedangkan pengelolaan data dilakukan oleh bendahara dan siswa melalui dashboard masing-masing.

## Fitur

- **Halaman publik** — menampilkan saldo kas, total pemasukan, total pengeluaran, dan riwayat transaksi tanpa login.
- **Role-based access** — `bendahara` dan `siswa` dengan hak akses berbeda.
- **Tagihan kas per periode** — bendahara membuat tagihan mingguan/event beserta nominal dan jatuh tempo.
- **Upload bukti pembayaran** — siswa mengirim bukti transfer/tunai ke storage publik.
- **Verifikasi pembayaran** — bendahara dapat menyetujui (`approved`) atau menolak (`rejected`) setoran.
- **Pencatatan pengeluaran** — bendahara mencatat pengeluaran dan mengunggah foto nota.
- **Perhitungan saldo terpusat** — hanya pembayaran `approved` yang dihitung sebagai pemasukan.
- **Automated test & CI** — PHPUnit dengan SQLite in-memory dan GitHub Actions.

## Teknologi

| Komponen | Keterangan |
|---|---|
| Framework | Laravel 13 |
| Bahasa | PHP 8.3+ (dikembangkan dengan PHP 8.5) |
| Database | SQLite |
| Tampilan | Blade + Tailwind CSS (CDN) |
| Testing | PHPUnit 12 |
| CI/CD | GitHub Actions |

> Tailwind CSS dimuat melalui CDN, jadi **tidak perlu menjalankan `npm install` atau `npm run build`** untuk menjalankan aplikasi.

## Struktur Proyek

```text
kaskita/
├── app/                        # BACKEND — logika aplikasi
│   ├── Enums/                  # Role, PaymentMethod, PaymentStatus
│   ├── Http/
│   │   ├── Controllers/        # Auth, PublicDashboard, Dashboard, KasPeriod, Payment, Expense
│   │   └── Middleware/         # EnsureUserHasRole
│   ├── Models/                 # User, KasPeriod, Payment, Expense
│   └── Services/KasService.php # Perhitungan pemasukan, pengeluaran, saldo
├── database/                   # BACKEND — skema & data
│   ├── factories/              # Factory untuk data testing
│   ├── migrations/             # users, kas_periods, payments, expenses
│   └── seeders/DatabaseSeeder.php
├── resources/views/frontend/   # FRONTEND — tampilan Blade
│   ├── layouts/app.blade.php
│   ├── public/index.blade.php
│   ├── auth/login.blade.php
│   └── dashboard/
│       ├── bendahara.blade.php
│       └── siswa.blade.php
├── routes/web.php              # BACKEND — routing & hak akses
├── tests/Feature/KasFlowTest.php
└── .github/workflows/laravel-ci.yml
```

## Pembagian Backend & Frontend

Proyek ini tetap memakai **arsitektur monolith Laravel**, tetapi kode dikelompokkan agar batas backend dan frontend mudah dipahami:

| Bagian | Lokasi | Isi |
|---|---|---|
| **Backend** | `app/`, `database/`, `routes/` | Controller, model, service, migration, seeder, dan routing |
| **Frontend** | `resources/views/frontend/` | Layout, halaman publik, login, dan dashboard |
| **Penghubung** | `app/Http/Controllers/` | Controller memproses data lalu mengirimnya ke view `frontend.*` |

Frontend tetap dirender oleh Laravel menggunakan Blade, jadi tidak ada proses fetching API JSON terpisah. Pemisahan ini hanya pengelompokan folder, bukan pemisahan repository atau aplikasi.

## Persyaratan

- PHP 8.3 atau lebih baru dengan ekstensi `pdo_sqlite`, `mbstring`, `fileinfo`, dan `openssl`
- Composer 2
- Tidak perlu Node.js/NPM untuk menjalankan aplikasi

## Cara Menjalankan

### 1. Install dependensi

```bash
composer install
```

### 2. Siapkan environment

Linux/macOS/Git Bash:

```bash
cp .env.example .env
php artisan key:generate
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

### 3. Siapkan database SQLite

Pastikan file database tersedia. Jika belum ada:

Linux/macOS/Git Bash:

```bash
touch database/database.sqlite
```

Windows PowerShell:

```powershell
New-Item -ItemType File -Force database/database.sqlite
```

Jalankan migrasi dan seeder data demo:

```bash
php artisan migrate --seed
```

Untuk mereset seluruh data demo dari awal:

```bash
php artisan migrate:fresh --seed
```

### 4. Buat storage link

Diperlukan agar foto bukti bayar dan nota dapat diakses publik:

```bash
php artisan storage:link
```

> Di Windows, pembuatan symlink bisa memerlukan Developer Mode atau terminal sebagai Administrator.

### 5. Jalankan server

```bash
php artisan serve
```

Buka **http://127.0.0.1:8000**.

## Akun Demo

Semua akun menggunakan password: **`password`**

| Peran | Nama | Email | NIS |
|---|---|---|---|
| Bendahara | Bendahara Kelas | `bendahara@sekolah.sch.id` | — |
| Siswa | Aditya Pratama | `aditya@sekolah.sch.id` | `2425001` |
| Siswa | Bunga Lestari | `bunga@sekolah.sch.id` | `2425002` |
| Siswa | Citra Dewi | `citra@sekolah.sch.id` | `2425003` |
| Siswa | Dimas Saputra | `dimas@sekolah.sch.id` | `2425004` |
| Siswa | Eka Putri | `eka@sekolah.sch.id` | `2425005` |

## Alur Demo

1. Buka `/` untuk melihat halaman transparansi publik: saldo, pemasukan, pengeluaran, dan riwayat transaksi.
2. Login sebagai **bendahara** untuk:
   - membuat tagihan kas baru;
   - menyetujui atau menolak setoran yang berstatus `pending`;
   - mencatat pengeluaran beserta foto nota.
3. Login sebagai **siswa** untuk:
   - melihat daftar tagihan dan status pembayaran;
   - mengunggah bukti bayar (wajib untuk metode transfer).
4. Setelah siswa mengirim bukti, statusnya `pending` dan saldo **belum bertambah**.
5. Setelah bendahara menyetujui, status menjadi `approved` dan saldo otomatis bertambah.
6. Setiap pengeluaran yang dicatat bendahara otomatis mengurangi saldo.

## Menjalankan Test

```bash
php artisan test --compact
```

Test menggunakan database **SQLite in-memory** (`:memory:`) yang dikonfigurasi di `phpunit.xml`, sehingga tidak mengubah database demo lokal.

Cakupan test utama:

- Halaman publik dapat diakses dan menampilkan saldo.
- Siswa dapat mengirim pembayaran dengan status awal `pending` (saldo belum bertambah).
- Bendahara dapat menyetujui pembayaran (saldo bertambah).
- Bendahara dapat mencatat pengeluaran (saldo berkurang).
- Login/logout, proteksi role, dashboard, dan pencegahan pembayaran ganda.

## CI/CD

Workflow GitHub Actions tersedia di `.github/workflows/laravel-ci.yml` dan berjalan otomatis pada setiap **push** atau **pull request** ke branch `main`.

Langkah pipeline:

1. Setup PHP 8.5 dan ekstensi yang dibutuhkan.
2. Copy `.env.example` menjadi `.env`.
3. `composer install`.
4. `php artisan key:generate`.
5. `php artisan test`.

## Rute Utama

| Method | URI | Akses | Fungsi |
|---|---|---|---|
| GET | `/` | Publik | Halaman transparansi kas |
| GET/POST | `/login` | Guest | Login |
| POST | `/logout` | Auth | Logout |
| GET | `/dashboard` | Auth | Dashboard sesuai role |
| POST | `/kas-periods` | Bendahara | Membuat tagihan kas |
| POST | `/payments` | Siswa | Mengirim bukti pembayaran |
| PATCH | `/payments/{payment}/approve` | Bendahara | Menyetujui pembayaran |
| PATCH | `/payments/{payment}/reject` | Bendahara | Menolak pembayaran |
| POST | `/expenses` | Bendahara | Mencatat pengeluaran |

## Catatan

- Foto bukti bayar disimpan di `storage/app/public/proofs`.
- Foto nota pengeluaran disimpan di `storage/app/public/receipts`.
- Saldo dihitung di `App\Services\KasService`; hanya pembayaran berstatus `approved` yang dihitung sebagai pemasukan.
- Proyek ini menggunakan lisensi MIT.
