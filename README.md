# AI Lab Polinema Website

Web profile dari **Applied Informatics Laboratory – Politeknik Negeri Malang** yang terdiri dari **web profile publik** dan **sistem administrasi internal**, dibangun untuk kebutuhan informasi, manajemen anggota, serta pengelolaan aktivitas laboratorium.
Proyek ini menjadi bagian dari aktivitas Project-Based-Learning tahun Tingkat 2 Prodi D-IV Teknik Informatika 2025/2026.

---

## 📌 Gambaran Umum Sistem

Proyek ini dibagi menjadi **dua sistem utama** yang terpisah namun menggunakan **satu database PostgreSQL** yang sama:

| Sistem | Teknologi | Deskripsi |
|------|----------|-----------|
| Web Profile | Laravel 10 + Tailwind CSS | Website publik (read-only) |
| Admin System | PHP Native | Sistem internal (Superadmin & Member Admin) |
| Database | PostgreSQL | Sumber data terpusat |

---

## 🧩 Arsitektur Sistem

```

PostgreSQL Database
↑
│
──────────────────────────────────
│                                 │
│   Web Profile (Laravel)         │
│   - Publik                      │
│   - Read-only                   │
│                                 │
│   Admin System (PHP Native)     │
│   - Superadmin                  │
│   - Member Admin                │
│   - CRUD Data                   │
│                                 │
──────────────────────────────────

````

---

## 🌐 Web Profile (Laravel)

Web profile digunakan sebagai **media informasi publik** dan tidak memerlukan login.

### Fitur:
- Home
- About Lab
- Research
- Activity
- Members
- Member Profile (read-only)
- Facility
- Contact
- Volunteer Registration

### Teknologi:
- Laravel **versi 10**
- Tailwind CSS
- Blade Template
- PostgreSQL

📌 **Catatan:**  
Laravel versi 10 digunakan karena versi 11 dan 12 memiliki perubahan konfigurasi routing yang menyebabkan ketidakcocokan pada proyek ini.

---

## 🔐 Admin System (PHP Native)

Sistem internal untuk pengelolaan data laboratorium. Semua konfigurasinya terletak pada directory `ai-lab/frontend/admin`

### Role:
#### 🟥 Superadmin
- Kelola admin lab
- Kelola akun member
- Approve lab permit
- Kelola news & project
- Akses penuh sistem

#### 🟧 Member Admin
- Dashboard pribadi
- Edit profil
- CRUD:
  - Research
  - PPM
  - IPS
  - Activities
- Lihat status lab permit

---

## 🛠️ Kebutuhan Sistem

### Software:
- PHP 8.1 – 8.2
- Composer
- PostgreSQL 14+
- pgAdmin 4 (atau software Database Management lainnya)
- Web Server (XAMPP / Laragon)
- Browser (Chrome disarankan)
- NPM (NodeJS Package Manager)

---

## 🚀 Instalasi Web Profile (Laravel)

### 1. Masuk ke directory backend
```bash
mkdir laravel-project
cd laravel-project
````

### 2. Pastikan Composer terinstall

```bash
composer -v
```

### 3. Install Laravel 10

```bash
composer create-project laravel/laravel ai-lab-backend "10.*"
```

#### Atau, bisa menggunakan ini

### 1. Masuk ke drive `ai-lab-backend`
```bash
cd ai-lab-backend
```

### 2. Install library-library Laravel menggunakan Composer
```bash
composer install
```

### 3. Lalu Install menggunakan NPM
```bash
npm install
```

---

### 4. Konfigurasi Database (`.env`)
Buat file `.env` baru dengan menggunakan command ini
```bash
cp .env.example .env
```

Konfigurasi file `.env` tersebut
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432 // Ganti port jika berbeda
DB_DATABASE=ai_lab_db // Ganti juga jika berbeda
DB_USERNAME=postgres // User PostgreSQL default untuk Windows. Ganti juga bila berbeda
DB_PASSWORD=12345 // Ganti jika berbeda
```

Jalankan command ini setelah meng-konfigurasi .env dari Laravel
```bash
php artisan key:generate
```

### 5. Jalankan Laravel

```bash
php artisan serve
```

### 6. Migrasi Database

```bash
php artisan migrate
```

### Jika terjadi error setelah edit `.env`

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

---

## 🧑‍💻 Instalasi Admin System (PHP Native)

1. Letakkan project di web server:

```
C:/xampp/htdocs/ai-lab/ -- Contoh jika menggunakan XAMPP
```

2. Konfigurasi koneksi database di `Database.php`:

```php
$host = "127.0.0.1";   // sama .env Laravel
$port = "5432"; // Diganti jika port PostgreSQL beda
$db = "ai_lab_db";     // Diganti jika nama DB berbeda
$user = trim("postgres"); // 
$password = "nasywa1010";  // Diganti jika berbeda
$connStr = "host=$host port=$port dbname=$db user=$user password=$password";
```

3. Jalankan Apache & PostgreSQL

4. Akses:
### Web Profile

```
http://localhost/ai-lab/frontend
```
### Admin Page
```
http://localhost/ai-lab/frontend/admin
```

---

## 🗄️ Database

* Nama Database: `ai_lab_db`
* DBMS: PostgreSQL
* Import menggunakan pgAdmin (Restore `.sql`)
* Trigger & constraint otomatis aktif

---

## 🔒 Keamanan Sistem

* Session-based authentication
* Role-based access control
* Database trigger untuk validasi, limitasi data, dan pencegahan spam
* Halaman unauthorized (`403.php`)

---

## 📚 Tujuan Proyek

Proyek ini dikembangkan sebagai bagian dari:

* Website profil laboratorium
* Sistem manajemen internal AI Lab
* Media pembelajaran web development (Laravel, PHP Native, PostgreSQL)

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan akademik dan internal laboratorium.

```

---
