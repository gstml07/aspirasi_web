<?php
session_start();
include 'koneksi.php';

// 1. Ambil input dari form dan bersihkan spasi jika ada
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// 2. Validasi dasar agar tidak ada input kosong
if (empty($username) || empty($password)) {
    // Jika kosong, kembalikan ke halaman login dengan pesan error
    header("Location: index.php?error=Username dan Password tidak boleh kosong");
    exit();
}

// 3. Gunakan Prepared Statement untuk mencegah SQL Injection
$stmt = $koneksi->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// 4. Periksa apakah username ditemukan
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // 5. Verifikasi password yang diinput dengan hash dari database
    if (password_verify($password, $user['password'])) {
        // Jika password cocok, buat session baru yang aman
        session_regenerate_id(true); // Mencegah serangan session fixation
        
        // Simpan informasi pengguna ke dalam session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // 6. Arahkan pengguna berdasarkan rolenya
        if ($user['role'] == 'mahasiswa') {
            header("Location: mahasiswa.php");
        } else if ($user['role'] == 'dosen') {
            header("Location: dosen.php");
        }
        exit(); // Hentikan eksekusi skrip setelah redirect
    }
}

// 7. Jika username tidak ditemukan atau password salah, kembalikan ke halaman login
header("Location: index.php?error=Username atau Password salah");
$stmt->close();
$koneksi->close();
exit();
?>
