<?php
session_start();
include 'koneksi.php';

// Cek jika user tidak login atau bukan dosen
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'dosen') {
    header("Location: index.php?error=Anda harus login sebagai dosen");
    exit;
}

// Query untuk mengambil semua data aspirasi yang dibutuhkan
$query_aspirasi = "SELECT a.id_aspirasi, a.jenis, a.isi, a.tanggal, a.balasan, a.tanggal_balasan, u.username 
                   FROM aspirasi a 
                   JOIN users u ON a.user_id = u.id 
                   ORDER BY a.tanggal DESC";
$aspirasi_result = $koneksi->query($query_aspirasi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dosen - Daftar Aspirasi</title>
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
    .aspirasi-list { list-style-type: none; padding: 0; }
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
    .form-balasan { margin-top: 18px; }
    .form-balasan textarea { 
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
    .form-balasan textarea:focus {
      border-color: #2575fc;
      background: #eaf4ff;
    }
    .form-balasan button { 
      margin-top: 10px;
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
    .form-balasan button:hover { 
      background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
      transform: scale(1.04);
    }
    .info-sukses { color: #007c21; text-align: center; background-color: #e5ffe5; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-weight: 500;}
    .info-error { color: #d8000c; text-align: center; background-color: #ffe5e5; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-weight: 500;}
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
    <h2>Daftar Aspirasi Mahasiswa</h2>
    <p style="text-align:center; color:#444; font-size:1.08em;">Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>.</p>

    <?php if(isset($_GET['status']) && $_GET['status'] == 'balasan_sukses') echo '<p class="info-sukses">Balasan berhasil disimpan!</p>'; ?>
    <?php if(isset($_GET['error'])) echo '<p class="info-error">' . htmlspecialchars($_GET['error']) . '</p>'; ?>

    <ul class="aspirasi-list">
      <?php if ($aspirasi_result && $aspirasi_result->num_rows > 0): ?>
        <?php while ($row = $aspirasi_result->fetch_assoc()): ?>
          <li class="aspirasi-item" id="aspirasi-<?php echo $row['id_aspirasi']; ?>">
            <p>
              <strong style="color:#2575fc;"><?php echo htmlspecialchars($row['username']); ?></strong>
              <span class="aspirasi-meta"> (Kategori: <?php echo htmlspecialchars($row['jenis']); ?>)</span>
            </p>
            <div class="aspirasi-isi"><?php echo nl2br(htmlspecialchars($row['isi'])); ?></div>
            <em class="aspirasi-meta">Dikirim pada: <?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></em>

            <!-- Tampilkan balasan jika sudah ada -->
            <?php if (!empty($row['balasan'])): ?>
              <div class="balasan-dosen">
                <strong style="color:#28a745;">Telah Dibalas:</strong>
                <p><?php echo nl2br(htmlspecialchars($row['balasan'])); ?></p>
                <em class="aspirasi-meta">Dibalas pada: <?php echo date('d M Y, H:i', strtotime($row['tanggal_balasan'])); ?></em>
              </div>
            <?php endif; ?>

            <!-- Form untuk mengirim atau memperbarui balasan -->
            <div class="form-balasan">
              <form action="proses_balasan.php" method="post">
                <input type="hidden" name="id_aspirasi" value="<?php echo $row['id_aspirasi']; ?>">
                <textarea name="balasan" placeholder="Tulis balasan di sini..."><?php echo htmlspecialchars($row['balasan'] ?? ''); ?></textarea>
                <button type="submit"><?php echo !empty($row['balasan']) ? 'Perbarui Balasan' : 'Kirim Balasan'; ?></button>
              </form>
            </div>
          </li>
        <?php endwhile; ?>
      <?php else: ?>
        <li class="aspirasi-item" style="text-align:center;">Belum ada aspirasi yang masuk.</li>
      <?php endif; ?>
    </ul>
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
