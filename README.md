Sistem Aspirasi Mahasiswa (PHP Native)
Sebuah aplikasi web sederhana yang dibangun menggunakan PHP native dan MySQL untuk menjembatani komunikasi antara mahasiswa dan dosen. Aplikasi ini memungkinkan mahasiswa untuk menyuarakan aspirasi mereka terkait berbagai kategori, dan memungkinkan dosen untuk memberikan tanggapan secara langsung.

✨ Fitur Utama
Otentikasi Pengguna: Sistem login yang aman untuk dua jenis peran:

Mahasiswa: Dapat mendaftar, login, mengirim, dan melihat riwayat aspirasi.

Dosen: Dapat login, melihat semua aspirasi yang masuk, dan memberikan balasan.

Registrasi Mahasiswa: Mahasiswa dapat membuat akun baru secara mandiri.

Pengajuan Aspirasi: Form pengajuan aspirasi dengan pilihan kategori (Fasilitas, Akademik, Layanan, dll.) menggunakan tombol interaktif.

Riwayat Aspirasi: Mahasiswa dapat melihat semua aspirasi yang pernah mereka kirim beserta balasan dari dosen.

Dashboard Dosen: Halaman khusus untuk dosen melihat dan mengelola semua aspirasi yang masuk dari mahasiswa.

Sistem Balasan: Dosen dapat menulis dan memperbarui balasan untuk setiap aspirasi.

💻 Teknologi yang Digunakan
Backend: PHP 8+ (Native, tanpa framework)

Database: MySQL / MariaDB

Frontend: HTML5, CSS3 (tanpa framework/library)

Server: Direkomendasikan menggunakan XAMPP atau Laragon.

🚀 Panduan Instalasi dan Setup
Untuk menjalankan proyek ini di lingkungan lokal Anda, ikuti langkah-langkah berikut:

1. Prasyarat
Pastikan Anda sudah menginstal paket server lokal seperti XAMPP atau Laragon yang sudah mencakup Apache, PHP, dan MySQL.

2. Clone Repositori
Buka terminal atau Git Bash, lalu clone repositori ini ke dalam folder htdocs (untuk XAMPP) atau www (untuk Laragon).

git clone [https://github.com/username-anda/nama-repositori-anda.git](https://github.com/username-anda/nama-repositori-anda.git)
cd nama-repositori-anda

3. Konfigurasi Database
Buka phpMyAdmin (http://localhost/phpmyadmin).

Buat database baru dengan nama aspirasi_db.

Tidak perlu impor file .sql. Kita akan menggunakan skrip setup otomatis.

4. Jalankan Skrip Setup Otomatis
Ini adalah langkah paling penting untuk memastikan database, tabel, dan akun default dibuat dengan benar.

Buka browser Anda dan akses file setup.php yang ada di dalam folder proyek.

Contoh URL: http://localhost/nama-repositori-anda/setup.php

Anda akan melihat halaman yang menampilkan proses pembuatan tabel dan akun. Jika berhasil, akan ada pesan "Setup Selesai!".

Skrip ini akan secara otomatis:

Menghapus tabel users dan aspirasi lama (jika ada).

Membuat kembali struktur tabel yang diperlukan.

Membuat 2 akun default dengan password yang di-hash secara aman.

5. Login dan Coba Aplikasi
Setelah setup berhasil, Anda bisa langsung login menggunakan akun default berikut:

Akun Dosen:

Username: dosen1

Password: dosen123

Akun Mahasiswa:

Username: mahasiswa1

Password: pass123

⚠️ PENTING!
Setelah setup berhasil dan Anda bisa login, segera hapus file setup.php dari folder proyek Anda untuk alasan keamanan.

📂 Struktur File
Berikut adalah penjelasan singkat tentang file-file utama dalam proyek ini:

.
├── 📄 index.php           # Halaman login utama

├── 📄 register.php        # Halaman registrasi untuk mahasiswa

├── 📄 mahasiswa.php       # Dashboard untuk mahasiswa (mengirim & melihat aspirasi)

├── 📄 dosen.php           # Dashboard untuk dosen (melihat & membalas aspirasi)

├── 📄 koneksi.php         # Menghubungkan aplikasi ke database

├── 📄 setup.php           # (HAPUS SETELAH DIGUNAKAN) Skrip untuk setup database

├── 📁 proses/

│   ├── 📄 proses_login.php

│   ├── 📄 proses_register.php

│   ├── 📄 proses_aspirasi.php

│   └── 📄 proses_balasan.php

└── 🎨 style.css           # File styling untuk semua halaman

🖼️ Tampilan Aplikasi (Contoh)
Halaman Login

Halaman Mahasiswa





Halaman Dosen



