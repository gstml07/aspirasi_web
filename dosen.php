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
  <style>
    .aspirasi-list { list-style-type: none; padding: 0; }
    .aspirasi-item { 
      background: #fff; 
      border: 1px solid #ddd; 
      padding: 20px; 
      margin-bottom: 20px; 
      border-radius: 8px; 
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .aspirasi-meta { color: #666; font-size: 0.9em; }
    .aspirasi-isi { margin-top: 10px; line-height: 1.6; }
    .balasan-dosen {
      margin-top: 15px;
      padding: 15px;
      background-color: #e9f7ef;
      border-left: 4px solid #28a745;
      border-radius: 4px;
    }
    .form-balasan { margin-top: 15px; }
    .form-balasan textarea { min-height: 80px; }
    .form-balasan button { background-color: #007bff; }
    .form-balasan button:hover { background-color: #0056b3; }
    .info-sukses { color:green; text-align:center; background-color: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 15px;}
    .info-error { color:red; text-align:center; background-color: #ffdddd; padding: 10px; border-radius: 5px; margin-bottom: 15px;}
  </style>
</head>
<body>
  <div class="container" style="width: 80%; max-width: 800px;">
    <h2>Daftar Aspirasi Mahasiswa</h2>
    <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>.</p>

    <?php if(isset($_GET['status']) && $_GET['status'] == 'balasan_sukses') echo '<p class="info-sukses">Balasan berhasil disimpan!</p>'; ?>
    <?php if(isset($_GET['error'])) echo '<p class="info-error">' . htmlspecialchars($_GET['error']) . '</p>'; ?>

    <ul class="aspirasi-list">
      <?php if ($aspirasi_result && $aspirasi_result->num_rows > 0): ?>
        <?php while ($row = $aspirasi_result->fetch_assoc()): ?>
          <li class="aspirasi-item" id="aspirasi-<?php echo $row['id_aspirasi']; ?>">
            <p>
              <strong><?php echo htmlspecialchars($row['username']); ?></strong>
              <span class="aspirasi-meta"> (Kategori: <?php echo htmlspecialchars($row['jenis']); ?>)</span>
            </p>
            <div class="aspirasi-isi"><?php echo nl2br(htmlspecialchars($row['isi'])); ?></div>
            <em class="aspirasi-meta">Dikirim pada: <?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></em>

            <!-- Tampilkan balasan jika sudah ada -->
            <?php if (!empty($row['balasan'])): ?>
              <div class="balasan-dosen">
                <strong>Telah Dibalas:</strong>
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
        <li class="aspirasi-item">Belum ada aspirasi yang masuk.</li>
      <?php endif; ?>
    </ul>
    <br>
    <a href="logout.php" class="logout-link">Logout</a>
  </div>
</body>
</html>
