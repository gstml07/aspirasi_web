<?php
session_start();

// Jika sudah login, arahkan ke halaman sesuai role
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'mahasiswa') {
        header("Location: mahasiswa.php");
    } else if ($_SESSION['role'] == 'dosen') {
        header("Location: dosen.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Aspirasi</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="login-container">
    <div class="login-header" style="display: flex; justify-content: center; align-items: center; margin-bottom: 20px;">
      <img src="logo/254721151_utb_kotak.png" width="100px" alt="Logo Sistem Aspirasi" class="logo">
    </div>

    <h2>Login Sistem Aspirasi</h2>
    
    <?php
    // Menampilkan pesan error atau sukses dari URL
    if (isset($_GET['error'])) {
        echo '<p class="info-box info-error">' . htmlspecialchars($_GET['error']) . '</p>';
    }
    if (isset($_GET['success'])) {
        echo '<p class="info-box info-sukses">' . htmlspecialchars($_GET['success']) . '</p>';
    }
    ?>

    <form action="proses_login.php" method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    
    <div class="switch-form">
      Belum punya akun? <a href="register.php">Daftar di sini</a>
    </div>
  </div>
</body>
</html>
