<?php
session_start();

// Jika sudah login, tidak perlu ke halaman register
if (isset($_SESSION['role'])) {
    header("Location: mahasiswa.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Sistem Aspirasi</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="login-container">
    <h2>Registrasi Akun Mahasiswa</h2>
    
    <?php
    // Menampilkan pesan error dari URL
    if (isset($_GET['error'])) {
        echo '<p class="info-box info-error">' . htmlspecialchars($_GET['error']) . '</p>';
    }
    ?>

    <form action="proses_register.php" method="post">
      <input type="text" name="username" placeholder="Buat Username" required>
      <input type="password" name="password" placeholder="Buat Password" required>
      <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
      <button type="submit">Daftar</button>
    </form>

    <div class="switch-form">
      Sudah punya akun? <a href="index.php">Login di sini</a>
    </div>
  </div>
</body>
</html>
