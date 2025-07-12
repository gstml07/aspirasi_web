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
  <!-- Tambahkan Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      font-family: 'Montserrat', Arial, sans-serif;
      /* Ganti background dengan gambar */
      background: url('background/background.jpg') no-repeat center center fixed;
      background-size: cover;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      animation: fadeIn 1s;
    }
    /* Overlay biru transparan di atas gambar */
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background: rgba(60, 80, 200, 0.45);
      z-index: 0;
      pointer-events: none;
    }
    .login-container {
      /* Pastikan login box di atas overlay */
      position: relative;
      z-index: 1;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(40, 40, 90, 0.18);
      padding: 40px 32px 32px 32px;
      width: 100%;
      max-width: 370px;
      text-align: center;
      animation: slideUp 0.7s;
    }
    @keyframes slideUp {
      from { transform: translateY(40px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    .login-header img {
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(40, 40, 90, 0.12);
      transition: transform 0.2s;
    }
    .login-header img:hover {
      transform: scale(1.08) rotate(-2deg);
    }
    h2 {
      font-weight: 700;
      color: #2575fc;
      margin-bottom: 18px;
      letter-spacing: 1px;
    }
    .info-box {
      margin: 10px 0 18px 0;
      padding: 10px;
      border-radius: 8px;
      font-size: 0.98em;
      font-weight: 500;
      box-shadow: 0 2px 8px rgba(40,40,90,0.07);
    }
    .info-error { background: #ffe5e5; color: #d8000c; }
    .info-sukses { background: #e5ffe5; color: #007c21; }
    form {
      display: flex;
      flex-direction: column;
      gap: 16px;
      margin-bottom: 10px;
    }
    input[type="text"], input[type="password"] {
      padding: 12px;
      border: 1px solid #dbeafe;
      border-radius: 8px;
      font-size: 1em;
      background: #f5f8ff;
      transition: border 0.2s;
      outline: none;
    }
    input[type="text"]:focus, input[type="password"]:focus {
      border-color: #2575fc;
      background: #eaf4ff;
    }
    button[type="submit"] {
      padding: 12px;
      background: linear-gradient(90deg, #2575fc 0%, #6a11cb 100%);
      color: #fff;
      font-weight: 700;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(40,40,90,0.10);
      transition: background 0.2s, transform 0.2s;
    }
    button[type="submit"]:hover {
      background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
      transform: scale(1.04);
    }
    .switch-form {
      margin-top: 18px;
      font-size: 0.98em;
      color: #444;
    }
    .switch-form a {
      color: #2575fc;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.2s;
    }
    .switch-form a:hover {
      color: #6a11cb;
      text-decoration: underline;
    }
    @media (max-width: 480px) {
      .login-container {
        padding: 24px 8px 18px 8px;
        max-width: 98vw;
      }
      h2 { font-size: 1.2em; }
    }
  </style>
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
    <!-- Social Media Section -->
    <div class="social-media" style="margin-top:18px; display:flex; justify-content:center; gap:18px;">
      <a href="https://www.instagram.com/utb.univ/" target="_blank" class="ig-float" title="Kunjungi Instagram UTB">
        <img src="logo/instagram-1-svgrepo-com.svg" alt="Instagram" style="width:44px; height:44px;">
      </a>
      <a href="http://m.utb-univ.id/" target="_blank" class="web-float" title="Kunjungi Website UTB">
        <img src="logo/website-5793.svg" alt="Website" style="width:44px; height:44px;">
      </a>
    </div>
  </div>
  <!-- WhatsApp Floating Button -->
  <a href="https://wa.me/6287720772777" target="_blank" class="wa-float" title="Hubungi via WhatsApp">
    <img src="logo/whatsapp-symbol-logo-svgrepo-com.svg" alt="WhatsApp" style="width:52px; height:52px;">
  </a>
  <style>
    .wa-float {
      position: fixed;
      right: 24px;
      bottom: 24px;
      z-index: 99;
      background: #25d366;
      border-radius: 50%;
      box-shadow: 0 4px 18px rgba(40,40,90,0.18);
      padding: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: box-shadow 0.2s, transform 0.2s;
      cursor: pointer;
    }
    .wa-float:hover {
      box-shadow: 0 8px 32px rgba(40,40,90,0.28);
      transform: scale(1.08);
      background: #1ebe57;
    }
    .wa-float img {
      display: block;
    }
    .social-media .ig-float img,
    .social-media .web-float img {
      transition: transform 0.2s;
    }
    .social-media .ig-float:hover img {
      transform: scale(1.12) rotate(-8deg);
      filter: drop-shadow(0 2px 8px #6a11cb);
    }
    .social-media .web-float:hover img {
      transform: scale(1.12) rotate(8deg);
      filter: drop-shadow(0 2px 8px #2575fc);
    }
    @media (max-width: 600px) {
      .wa-float {
        right: 12px;
        bottom: 12px;
        padding: 7px;
      }
      .wa-float img {
        width: 40px;
        height: 40px;
      }
      .social-media .ig-float img,
      .social-media .web-float img {
        width: 34px;
        height: 34px;
      }
    }
  </style>
</body>
</html>
