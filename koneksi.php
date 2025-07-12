<?php
/**
 * koneksi.php
 * File untuk menghubungkan aplikasi ke database MySQL.
 */

// Pengaturan dasar koneksi database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "aspirasi_db";

// Membuat koneksi menggunakan mysqli
$koneksi = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Mengatur pelaporan error
// Selama development, tampilkan semua error.
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek jika koneksi gagal
if ($koneksi->connect_error) {
    // Hentikan eksekusi dan tampilkan pesan error.
    // Pesan ini lebih baik untuk development, jangan tampilkan detail di production.
    die("Koneksi Database Gagal: " . $koneksi->connect_error);
}

// Mengatur character set ke utf8mb4 untuk mendukung berbagai karakter
$koneksi->set_charset("utf8mb4");

?>
