# 🏺 Spirit Guide - E-Commerce System

![Spirit Guide Banner](https://img.shields.io/badge/Status-Development-gold?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-7.4+-blue?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-DB-orange?style=for-the-badge&logo=mysql)
![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css)

**Spirit Guide** adalah platform e-commerce eksklusif yang dirancang dengan estetika minimalis dan mewah. Sistem ini mengintegrasikan katalog produk, manajemen stok pintar, dan sistem pemesanan langsung melalui WhatsApp.

---

## ✨ Fitur Utama

* **🛒 Seamless Checkout**: Integrasi keranjang belanja dan formulir checkout dalam satu halaman untuk konversi yang lebih cepat.
* **📦 Smart Stock Management**: Sistem secara otomatis memfilter barang yang habis saat checkout untuk mencegah pesanan stok minus.
* **💬 WhatsApp Integration**: Pesanan dikirim langsung ke WhatsApp Admin dengan rincian barang, total harga, dan data pelanggan yang rapi.
* **⭐ Product Reviews**: Fitur ulasan pelanggan yang interaktif langsung di modal produk.
* **🔍 Real-time Search**: Pencarian produk instan tanpa perlu memuat ulang halaman.
* **👑 Premium Admin Dashboard**: Panel kontrol dengan tampilan *glassmorphism* untuk mengelola produk, pesanan, dan pengguna.

---

## 📂 Struktur Folder

Project ini menggunakan struktur folder modular untuk memudahkan pengembangan:

```text
Spirit_Guide/
├── admin/                  # Dashboard & Manajemen Admin
├── assets/                 # Gambar Produk & Statis
├── config/                 # Konfigurasi Database
├── partials/               # Komponen Reusable (Navbar, Footer)
├── src/                    # Asset Front-end (CSS & JS)
├── index.php               # Halaman Utama
├── katalog.php             # Katalog Produk & Filter
└── checkout.php            # Keranjang & Form Pemesanan

CARA INSTALASI 

1. Clone Repository
Bash
git clone [https://github.com/Aellz29/Spirit_Guide.git](https://github.com/Aellz29/Spirit_Guide.git)

2. Konfigurasi Database
Buat database baru di MySQL (misal: spirit_guide_db).
Impor file .sql yang disediakan (jika ada).
Sesuaikan kredensial database di file config/db.php.

3.Jalankan di Server Lokal
Gunakan XAMPP/Laragon dan arahkan ke folder project.
Akses melalui localhost/Spirit_Guide.

🛠️ Teknologi yang Digunakan
Backend: PHP Native
Database: MySQLi
Frontend: Tailwind CSS & Vanilla JavaScript
Fonts: Plus Jakarta Sans

📝 Catatan Pengembangan
Sistem ini terus dikembangkan untuk meningkatkan performa dan keamanan. Fokus saat ini adalah pada sinkronisasi stok real-time dan enkripsi data pengguna.

developed by Team Spirit Guide