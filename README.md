# Helpdesk TIK Diskominfo Kabupaten Lebak

Sistem helpdesk TIK berbasis HESK 3.7.11 yang dikustomisasi untuk Diskominfo Kab. Lebak. Dilengkapi REST API layer internal dan data dummy untuk pengujian.

---

## Stack

- **Web Server**: Nginx 1.31 + PHP-FPM 8.2
- **Database**: MySQL 8.0
- **Framework**: HESK 3.7.11

---

## Setup dengan Docker (Direkomendasikan)

Pastikan **Docker Desktop** sudah berjalan, lalu jalankan:

```bash
docker compose up -d
```

Selesai. Database dan data dummy otomatis ter-import.

Akses aplikasi:

| Layanan | URL |
| :--- | :--- |
| Portal Helpdesk | http://localhost:8089 |
| Login User | http://localhost:8089/login.php |
| Panel Admin | http://localhost:8089/admin/ |
| phpMyAdmin | http://localhost:8081 |

### Setelah git pull (jika ada perubahan Dockerfile atau docker-compose.yml)

```bash
docker compose down --remove-orphans
docker compose build
docker compose up -d
```

> Gunakan `--remove-orphans` untuk membersihkan container lama yang nama service-nya mungkin sudah berubah. Data database pada volume `db_data` tidak akan terhapus.

---

## Setup Manual dengan XAMPP / Laragon

**Prasyarat**: XAMPP atau Laragon (PHP 8.x, MySQL/MariaDB, Apache)

1. Clone atau ekstrak repositori ke folder `htdocs` (misal: `C:/xampp/htdocs/helpdesk`).
2. Buka phpMyAdmin (`http://localhost/phpmyadmin`), buat database baru bernama `hesk_db` (Collation: `utf8mb4_general_ci`).
3. Import file `database.sql` ke database `hesk_db`.
4. Akses aplikasi:
   - Portal Utama: `http://localhost/helpdesk`
   - Login User: `http://localhost/helpdesk/login.php`
   - Panel Admin: `http://localhost/helpdesk/admin/`

---

## Setup via WSL

```bash
cd /mnt/c/xampp/htdocs/helpdesk
docker compose up -d
```

Akses di browser Windows: `http://localhost:8089`

Atau import manual via MySQL WSL:

```bash
sudo service mysql start
sudo mysql -u root -e "CREATE DATABASE hesk_db;"
sudo mysql -u root hesk_db < database.sql
```

---

## Akun Demo

**User / OPD**

- URL: `http://localhost:8089/login.php`
- Email: `user@lebakkab.go.id`
- Password: `User1234!`
- Tersedia 13 contoh tiket dari 11 kategori layanan TIK lengkap dengan riwayat balasan.

**Admin / Staff TIK**

- URL: `http://localhost:8089/admin/`
- Username: `Administrator`
- Password: `Admin1234!`

---

## REST API

Endpoint tersedia di `/api/` dengan response format JSON.

| Endpoint | Method | Keterangan |
| :--- | :---: | :--- |
| `/api/index.php` | `GET` | Status API & versi sistem |
| `/api/auth.php?action=login` | `POST` | Login akun (body: `email`, `password`) |
| `/api/auth.php?action=check` | `GET` | Cek status sesi login |
| `/api/auth.php?action=logout` | `POST` | Logout |
| `/api/categories.php` | `GET` | Daftar 11 kategori layanan TIK |
| `/api/tickets.php?action=list` | `GET` | Daftar tiket akun yang login |
| `/api/tickets.php?action=detail&track=ID` | `GET` | Detail tiket & riwayat percakapan |
| `/api/profile.php?action=get` | `GET` | Data profil user |
| `/api/profile.php?action=update` | `POST` | Update nama profil |

---

## Struktur Folder

```text
helpdesk/
├── admin/                 # Panel Admin & Staff TIK
├── api/                   # REST API Layer (Auth, Tickets, Categories, Profile)
│   ├── index.php
│   ├── auth.php
│   ├── categories.php
│   ├── tickets.php
│   └── profile.php
├── attachments/           # File lampiran tiket
├── cache/                 # Cache sistem
├── nginx/                 # Konfigurasi Nginx
│   └── nginx.conf
├── inc/                   # Core PHP libraries & fungsi HESK
├── theme/                 # Custom view & aset UI Diskominfo
│   └── hesk3/customer/
│       ├── account/       # View Login, Register, Profile
│       ├── create-ticket/ # View Form & Kategori Tiket
│       ├── view-ticket/   # View Status & Detail Tiket
│       ├── css/           # Custom stylesheet
│       ├── img/           # Asset gambar & logo
│       └── inc/           # Layout components (Header, Footer, Nav)
├── database.sql           # Dump database + data dummy
├── docker-compose.yml     # Konfigurasi Docker (Nginx, PHP-FPM, MySQL, phpMyAdmin)
├── Dockerfile             # PHP-FPM 8.2 image
├── hesk_settings.inc.php  # Konfigurasi utama (DB, feature flags, CAPTCHA)
├── index.php
├── login.php
├── register.php
├── profile.php
├── my_tickets.php
└── ticket.php
```