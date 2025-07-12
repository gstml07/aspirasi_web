<?php
session_start();
include 'koneksi.php';

// 1. Periksa apakah pengguna sudah login dan memiliki role sebagai mahasiswa
// Ini adalah langkah keamanan yang penting.
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa') {
    // Jika tidak, arahkan ke halaman login dengan pesan error
    header("Location: index.php?error=Anda harus login sebagai mahasiswa untuk mengirim aspirasi.");
    exit();
}

// 2. Ambil data dari form yang dikirim (method POST)
// Pastikan variabel ada sebelum digunakan untuk menghindari error
$user_id = $_SESSION['user_id'];
$jenis = isset($_POST['jenis']) ? trim($_POST['jenis']) : '';
$isi = isset($_POST['isi']) ? trim($_POST['isi']) : '';

// 3. Validasi input: pastikan kategori dan isi tidak kosong
if (empty($jenis) || empty($isi)) {
    // Jika ada yang kosong, kembalikan ke halaman mahasiswa dengan pesan error
    header("Location: mahasiswa.php?error=Kategori dan isi aspirasi harus diisi");
    exit();
}

// 4. Gunakan Prepared Statement untuk memasukkan data ke database
// Ini cara paling aman untuk mencegah serangan SQL Injection
$stmt = $koneksi->prepare("INSERT INTO aspirasi (user_id, jenis, isi) VALUES (?, ?, ?)");
// 'iss' berarti -> i: integer (user_id), s: string (jenis), s: string (isi)
$stmt->bind_param("iss", $user_id, $jenis, $isi);

// 5. Eksekusi query dan berikan feedback ke pengguna
if ($stmt->execute()) {
    // Jika berhasil, arahkan kembali ke halaman mahasiswa dengan pesan sukses
    header("Location: mahasiswa.php?status=sukses");
} else {
    // Jika gagal, tampilkan pesan error.
    // Pada aplikasi production, ini sebaiknya dicatat di log, bukan ditampilkan ke user.
    echo "Error: Gagal menyimpan aspirasi. Silakan coba lagi. " . $stmt->error;
}

// 6. Selalu tutup statement dan koneksi untuk membereskan resource
$stmt->close();
$koneksi->close();
exit(); // Hentikan eksekusi skrip
?>
