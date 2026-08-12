# Helpdesk TIK Diskominfo Kabupaten Lebak

Sistem pelayanan & helpdesk TIK berbasis HESK 3 yang dikustomisasi dengan UI/UX Diskominfo Kab. Lebak, dilengkapi dengan REST API layer internal dan data dummy untuk pengujian.

---

## Cara Setup di Lokal

### Prasyarat
- XAMPP atau Laragon (PHP 8.x, MySQL/MariaDB, Apache)
- Browser modern

### Langkah Instalasi
1. Clone atau ekstrak repositori ini ke folder `htdocs` (misal: `C:/xampp/htdocs/helpdesk`).
2. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`), lalu buat database baru dengan nama `hesk_db` (Collation: `utf8mb4_general_ci`).
3. Import file `database.sql` yang ada di root direktori ke database `hesk_db`.
4. Jalankan aplikasi lewat browser:
   - **Portal Utama**: `http://localhost/helpdesk`
   - **Login User**: `http://localhost/helpdesk/login.php`
   - **Panel Admin**: `http://localhost/helpdesk/admin/`

---

## Akun Demo (Data Dummy)

**User / OPD**
- URL: `http://localhost/helpdesk/login.php`
- Email: `user@lebakkab.go.id`
- Password: `User1234!`
- *Catatan: Sudah ada 13 contoh tiket dari 11 kategori layanan TIK lengkap dengan riwayat balasan.*

**Admin / Staff TIK**
- URL: `http://localhost/helpdesk/admin/`
- Username: `Administrator`
- Password: `Admin1234!`

---

## Dokumentasi REST API (`/api/`)

Aplikasi ini menyediakan REST API layer (JSON) untuk integrasi frontend atau aplikasi mobile:

| Endpoint | Method | Keterangan |
| :--- | :---: | :--- |
| `/api/index.php` | `GET` | Status API & versi sistem |
| `/api/auth.php?action=login` | `POST` | Login akun (menerima JSON `email` & `password`) |
| `/api/auth.php?action=check` | `GET` | Cek status sesi login |
| `/api/auth.php?action=logout` | `POST` | Logout dari sistem |
| `/api/categories.php` | `GET` | Mengambil 11 kategori layanan TIK |
| `/api/tickets.php?action=list` | `GET` | Mengambil daftar tiket akun yang login |
| `/api/tickets.php?action=detail&track=ID` | `GET` | Detail tiket & riwayat percakapan |
| `/api/profile.php?action=get` | `GET` | Mengambil data profil user |
| `/api/profile.php?action=update` | `POST` | Update nama profil user |

---

## Struktur Folder

```text
helpdesk/
├── admin/                 # Panel Admin & Staff TIK
├── api/                   # REST API Layer (Auth, Tickets, Categories, Profile)
│   ├── index.php          # Router & JSON dispatcher
│   ├── auth.php           # Auth endpoint
│   ├── categories.php     # Endpoint kategori layanan
│   ├── tickets.php        # Endpoint daftar & detail tiket
│   └── profile.php        # Endpoint manajemen profil
├── attachments/           # File lampiran tiket
├── cache/                 # Cache sistem
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
├── hesk_settings.inc.php  # Konfigurasi utama (DB, feature flags, CAPTCHA)
├── index.php              # Beranda
├── login.php              # Halaman Login
├── register.php           # Halaman Register
├── profile.php            # Pengaturan Akun
├── my_tickets.php         # Daftar Tiket Saya
└── ticket.php             # Detail Tiket