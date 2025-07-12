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
</head>
<body>
  <div class="container" style="max-width: 800px;">
    <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Silakan sampaikan aspirasi Anda melalui form di bawah ini.</p>
    
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
      <input type="hidden" id="selected_category" name="jenis_kategori" value="">

      <textarea name="isi" placeholder="Tulis aspirasi Anda di sini..." required></textarea>
      <button type="submit">Kirim Aspirasi</button>
    </form>
    
    <!-- Bagian Riwayat Aspirasi -->
    <div class="riwayat-container">
        <h3 style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">Riwayat Aspirasi Anda</h3>
        <?php if ($aspirasi_result->num_rows > 0): ?>
            <ul class="riwayat-list">
            <?php while ($row = $aspirasi_result->fetch_assoc()): ?>
                <li class="aspirasi-item">
                    <strong>Kategori: <?php echo htmlspecialchars($row['jenis']); ?></strong>
                    <p class="aspirasi-isi"><?php echo nl2br(htmlspecialchars($row['isi'])); ?></p>
                    <em class="aspirasi-meta">Dikirim pada: <?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></em>

                    <?php if (!empty($row['balasan'])): ?>
                    <div class="balasan-dosen">
                        <strong>Balasan Dosen:</strong>
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
            <p>Anda belum pernah mengirimkan aspirasi.</p>
        <?php endif; ?>
    </div>

    <a href="logout.php" class="logout-link">Logout</a>
  </div>
</body>
</html>
