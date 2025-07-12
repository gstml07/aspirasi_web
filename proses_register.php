<?php
include 'koneksi.php';

// 1. Pastikan request adalah POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: register.php");
    exit();
}

// 2. Ambil data dari form dan bersihkan
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);

// 3. Validasi input
if (empty($username) || empty($password) || empty($confirm_password)) {
    header("Location: register.php?error=Semua field harus diisi!");
    exit();
}

if (strlen($password) < 6) {
    header("Location: register.php?error=Password minimal harus 6 karakter!");
    exit();
}

if ($password !== $confirm_password) {
    header("Location: register.php?error=Konfirmasi password tidak cocok!");
    exit();
}

// 4. Cek apakah username sudah ada di database
$stmt = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header("Location: register.php?error=Username sudah digunakan, silakan pilih yang lain.");
    $stmt->close();
    $koneksi->close();
    exit();
}
$stmt->close();

// 5. Hash password sebelum disimpan ke database
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = 'mahasiswa'; // Role default untuk pendaftaran adalah mahasiswa

// 6. Simpan user baru ke database
$stmt = $koneksi->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashed_password, $role);

if ($stmt->execute()) {
    // Jika berhasil, arahkan ke halaman login dengan pesan sukses
    header("Location: index.php?success=Registrasi berhasil! Silakan login.");
} else {
    // Jika gagal, arahkan kembali dengan pesan error
    header("Location: register.php?error=Terjadi kesalahan. Gagal mendaftar.");
}

$stmt->close();
$koneksi->close();
exit();
?>
