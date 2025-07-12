<?php
session_start();
include 'koneksi.php';

// Cek jika user tidak login atau bukan mahasiswa
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: index.php?error=Anda harus login sebagai mahasiswa");
    exit;
}

// Ambil riwayat aspirasi untuk mahasiswa yang sedang login
$user_id = $_SESSION['user_id'];
$stmt_aspirasi = $koneksi->prepare("SELECT jenis, isi, tanggal, balasan, tanggal_balasan FROM aspirasi WHERE user_id = ? ORDER BY tanggal DESC");
$stmt_aspirasi->bind_param("i", $user_id);
$stmt_aspirasi->execute();
$aspirasi_result = $stmt_aspirasi->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mahasiswa - Sistem Aspirasi</title>
  <link rel="stylesheet" href="style.css">
  <!-- Tambahkan Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      font-family: 'Montserrat', Arial, sans-serif;
      background: url('background/background.jpg') no-repeat center center fixed;
      background-size: cover;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      animation: fadeIn 1s;
    }
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background: rgba(60, 80, 200, 0.45);
      z-index: 0;
      pointer-events: none;
    }
    .container {
      position: relative;
      z-index: 1;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(40, 40, 90, 0.18);
      padding: 40px 32px 32px 32px;
      width: 100%;
      max-width: 800px;
      margin: 40px auto;
      animation: slideUp 0.7s;
    }
    @keyframes slideUp {
      from { transform: translateY(40px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    h2 {
      font-weight: 700;
      color: #2575fc;
      margin-bottom: 18px;
      letter-spacing: 1px;
      text-align: center;
    }
    h3 {
      font-weight: 700;
      color: #2575fc;
      margin-bottom: 12px;
      letter-spacing: 1px;
      text-align: left;
    }
    .info-box {
      margin: 10px 0 18px 0;
      padding: 10px;
      border-radius: 8px;
      font-size: 0.98em;
      font-weight: 500;
      box-shadow: 0 2px 8px rgba(40,40,90,0.07);
      text-align: center;
    }
    .info-error { background: #ffe5e5; color: #d8000c; }
    .info-sukses { background: #e5ffe5; color: #007c21; }
    form {
      display: flex;
      flex-direction: column;
      gap: 16px;
      margin-bottom: 10px;
    }
    .category-selector {
      display: flex;
      gap: 18px;
      margin-bottom: 8px;
      flex-wrap: wrap;
    }
    .category-selector input[type="radio"] {
      accent-color: #2575fc;
      margin-right: 5px;
    }
    .category-selector label {
      font-weight: 500;
      color: #2575fc;
      margin-right: 10px;
      cursor: pointer;
      transition: color 0.2s;
    }
    .category-selector input[type="radio"]:checked + label {
      color: #6a11cb;
    }
    textarea {
      min-height: 80px;
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #dbeafe;
      font-family: 'Montserrat', Arial, sans-serif;
      font-size: 1em;
      background: #f5f8ff;
      transition: border 0.2s;
      outline: none;
    }
    textarea:focus {
      border-color: #2575fc;
      background: #eaf4ff;
    }
    button[type="submit"] {
      padding: 12px 24px;
      background: linear-gradient(90deg, #2575fc 0%, #6a11cb 100%);
      color: #fff;
      font-weight: 700;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(40,40,90,0.10);
      transition: background 0.2s, transform 0.2s;
      display: block;
    }
    button[type="submit"]:hover {
      background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
      transform: scale(1.04);
    }
    .riwayat-container {
      margin-top: 32px;
    }
    .riwayat-list {
      list-style-type: none;
      padding: 0;
      margin: 0;
    }
    .aspirasi-item {
      background: #f5f8ff;
      border: 1px solid #dbeafe;
      padding: 24px;
      margin-bottom: 24px;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(40,40,90,0.07);
      transition: box-shadow 0.2s;
    }
    .aspirasi-item:hover {
      box-shadow: 0 4px 18px rgba(40,40,90,0.13);
    }
    .aspirasi-meta { color: #666; font-size: 0.98em; }
    .aspirasi-isi { margin-top: 10px; line-height: 1.6; font-size: 1.05em; }
    .balasan-dosen {
      margin-top: 18px;
      padding: 18px;
      background-color: #e9f7ef;
      border-left: 4px solid #28a745;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(40, 90, 40, 0.07);
    }
    .logout-link {
      display: inline-block;
      margin-top: 18px;
      padding: 10px 24px;
      background: linear-gradient(90deg, #2575fc 0%, #6a11cb 100%);
      color: #fff;
      font-weight: 700;
      border-radius: 8px;
      text-decoration: none;
      box-shadow: 0 2px 8px rgba(40,40,90,0.10);
      transition: background 0.2s, transform 0.2s;
    }
    .logout-link:hover {
      background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
      transform: scale(1.04);
    }
    @media (max-width: 600px) {
      .container {
        padding: 18px 6px 12px 6px;
        max-width: 98vw;
      }
      h2 { font-size: 1.2em; }
      .aspirasi-item { padding: 12px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p style="text-align:center; color:#444; font-size:1.08em;">Silakan sampaikan aspirasi Anda melalui form di bawah ini.</p>
    
    <?php 
    if (isset($_GET['status']) && $_GET['status'] == 'sukses') echo '<p class="info-box info-sukses">Aspirasi berhasil dikirim!</p>';
    if (isset($_GET['error'])) echo '<p class="info-box info-error">' . htmlspecialchars($_GET['error']) . '</p>'; 
    ?>

    <form action="proses_aspirasi.php" method="post" id="formAspirasi">
      <p style="margin-bottom: 5px; font-weight: 500;">Pilih Kategori Aspirasi:</p>
      <div class="category-selector">
        <input type="radio" id="kat_fasilitas" name="jenis" value="Fasilitas" required>
        <label for="kat_fasilitas">Fasilitas</label>
        <input type="radio" id="kat_akademik" name="jenis" value="Akademik">
        <label for="kat_akademik">Akademik</label>
        <input type="radio" id="kat_layanan" name="jenis" value="Layanan">
        <label for="kat_layanan">Layanan</label>
        <input type="radio" id="kat_lainnya" name="jenis" value="Lainnya">
        <label for="kat_lainnya">Lainnya</label>
      </div>
      <textarea name="isi" placeholder="Tulis aspirasi Anda di sini..." required></textarea>
      <button type="submit">Kirim Aspirasi</button>
    </form>
    
    <div class="riwayat-container">
        <h3>Riwayat Aspirasi Anda</h3>
        <?php if ($aspirasi_result->num_rows > 0): ?>
            <ul class="riwayat-list">
            <?php while ($row = $aspirasi_result->fetch_assoc()): ?>
                <li class="aspirasi-item">
                    <strong style="color:#2575fc;">Kategori: <?php echo htmlspecialchars($row['jenis']); ?></strong>
                    <p class="aspirasi-isi"><?php echo nl2br(htmlspecialchars($row['isi'])); ?></p>
                    <em class="aspirasi-meta">Dikirim pada: <?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></em>
                    <?php if (!empty($row['balasan'])): ?>
                    <div class="balasan-dosen">
                        <strong style="color:#28a745;">Balasan Dosen:</strong>
                        <p><?php echo nl2br(htmlspecialchars($row['balasan'])); ?></p>
                        <em class="aspirasi-meta">Dibalas pada: <?php echo date('d M Y, H:i', strtotime($row['tanggal_balasan'])); ?></em>
                    </div>
                    <?php else: ?>
                    <div style="margin-top:10px; font-style:italic; color:#888;">Menunggu balasan...</div>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p style="text-align:center;">Anda belum pernah mengirimkan aspirasi.</p>
        <?php endif; ?>
    </div>
    <a href="logout.php" class="logout-link">Logout</a>
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
    }
  </style>
</body>
</html>
