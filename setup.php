<?php
/**
 * setup.php - Skrip untuk Inisialisasi Database
 * * CARA PENGGUNAAN:
 * 1. Simpan file ini dengan nama `setup.php` di folder proyek Anda.
 * 2. Buka file ini di browser (misal: http://localhost/proyek-aspirasi/setup.php).
 * 3. Skrip akan otomatis menghapus tabel lama dan membuat yang baru dengan data login yang benar.
 * 4. Setelah setup berhasil, SEGERA HAPUS FILE INI dari server Anda demi keamanan.
 */

// 1. Sertakan file koneksi database
include 'koneksi.php';

// Tampilan dasar untuk feedback
echo "<!DOCTYPE html><html lang='id'><head><meta charset='UTF-8'><title>Setup Database</title>";
echo "<style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; padding: 20px; background-color: #f0f2f5; color: #333; }
        .container { max-width: 800px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        p { margin: 10px 0; }
        .sukses { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; }
        .error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; }
        .warning { color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba; padding: 15px; border-radius: 5px; font-weight: bold; }
        code { background: #e9ecef; padding: 3px 6px; border-radius: 4px; font-family: 'SF Mono', 'Courier New', monospace; }
        a { background-color: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
        a:hover { background-color: #0056b3; }
      </style>";
echo "</head><body><div class='container'>";
echo "<h1>Proses Setup Database Sistem Aspirasi</h1>";

try {
    // 2. Hapus tabel lama untuk memastikan awal yang bersih
    $koneksi->query("DROP TABLE IF EXISTS `aspirasi`");
    echo "<p class='sukses'>&#10004; Tabel 'aspirasi' lama (jika ada) berhasil dihapus.</p>";

    $koneksi->query("DROP TABLE IF EXISTS `users`");
    echo "<p class='sukses'>&#10004; Tabel 'users' lama (jika ada) berhasil dihapus.</p>";

    // 3. Buat ulang tabel `users`
    $sqlUsers = "CREATE TABLE `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `username` varchar(50) NOT NULL,
      `password` varchar(255) NOT NULL,
      `role` enum('mahasiswa','dosen') NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `username` (`username`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    if ($koneksi->query($sqlUsers) === TRUE) {
        echo "<p class='sukses'>&#10004; Tabel 'users' berhasil dibuat.</p>";
    } else {
        throw new Exception("Gagal membuat tabel 'users': " . $koneksi->error);
    }

    // 4. Buat ulang tabel `aspirasi`
    $sqlAspirasi = "CREATE TABLE `aspirasi` (
      `id_aspirasi` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `jenis` varchar(50) NOT NULL,
      `isi` text NOT NULL,
      `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
      `balasan` text DEFAULT NULL,
      `tanggal_balasan` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id_aspirasi`),
      KEY `user_id` (`user_id`),
      CONSTRAINT `aspirasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    if ($koneksi->query($sqlAspirasi) === TRUE) {
        echo "<p class='sukses'>&#10004; Tabel 'aspirasi' berhasil dibuat.</p>";
    } else {
        throw new Exception("Gagal membuat tabel 'aspirasi': " . $koneksi->error);
    }

    // 5. Siapkan data user default dan HASH passwordnya
    // Ini adalah langkah kunci: password di-hash oleh environment PHP Anda sendiri.
    $passwordMahasiswa = 'pass123';
    $hashMahasiswa = password_hash($passwordMahasiswa, PASSWORD_DEFAULT);

    $passwordDosen = 'dosen123';
    $hashDosen = password_hash($passwordDosen, PASSWORD_DEFAULT);

    // 6. Masukkan user default ke tabel users menggunakan prepared statement
    $stmt = $koneksi->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");

    // Insert mahasiswa
    $userMahasiswa = 'mahasiswa1';
    $roleMahasiswa = 'mahasiswa';
    $stmt->bind_param("sss", $userMahasiswa, $hashMahasiswa, $roleMahasiswa);
    if ($stmt->execute()) {
        echo "<p class='sukses'>&#10004; User default 'mahasiswa1' berhasil dibuat. Password: <code>pass123</code></p>";
    } else {
        throw new Exception("Gagal membuat user 'mahasiswa1': " . $stmt->error);
    }

    // Insert dosen
    $userDosen = 'dosen1';
    $roleDosen = 'dosen';
    $stmt->bind_param("sss", $userDosen, $hashDosen, $roleDosen);
    if ($stmt->execute()) {
        echo "<p class='sukses'>&#10004; User default 'dosen1' berhasil dibuat. Password: <code>dosen123</code></p>";
    } else {
        throw new Exception("Gagal membuat user 'dosen1': " . $stmt->error);
    }

    $stmt->close();
    $koneksi->close();

    echo "<h2>Setup Selesai!</h2>";
    echo "<p class='sukses'>Database dan user default telah berhasil dikonfigurasi. Anda sekarang bisa login.</p>";
    echo "<p class='warning'>PENTING: Demi keamanan, segera hapus file <code>setup.php</code> ini dari server Anda!</p>";
    echo "<a href='index.php'>Kembali ke Halaman Login</a>";

} catch (Exception $e) {
    echo "<p class='error'><strong>Terjadi Kesalahan:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Pastikan detail koneksi di <code>koneksi.php</code> sudah benar dan user database memiliki hak akses untuk CREATE dan DROP tabel.</p>";
}

echo "</div></body></html>";
?>
