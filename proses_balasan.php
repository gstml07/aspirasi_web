<?php
session_start();
include 'koneksi.php';

// 1. Periksa apakah pengguna adalah dosen yang sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'dosen') {
    header("Location: index.php?error=Akses ditolak. Anda harus login sebagai dosen.");
    exit();
}

// 2. Pastikan permintaan adalah POST dan data yang diperlukan ada
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_aspirasi']) && isset($_POST['balasan'])) {
    
    // 3. Ambil dan bersihkan data input
    $id_aspirasi = $_POST['id_aspirasi'];
    $balasan = trim($_POST['balasan']);

    // Validasi dasar: balasan tidak boleh kosong
    if (empty($balasan)) {
        header("Location: dosen.php?error=Balasan tidak boleh kosong.");
        exit();
    }

    // 4. Gunakan Prepared Statement untuk UPDATE data balasan
    $stmt = $koneksi->prepare("UPDATE aspirasi SET balasan = ?, tanggal_balasan = NOW() WHERE id_aspirasi = ?");
    $stmt->bind_param("si", $balasan, $id_aspirasi);

    // 5. Eksekusi dan berikan feedback
    if ($stmt->execute()) {
        header("Location: dosen.php?status=balasan_sukses#aspirasi-" . $id_aspirasi);
    } else {
        // Sebaiknya error ini dicatat di log, bukan ditampilkan langsung
        header("Location: dosen.php?error=Gagal menyimpan balasan.");
    }

    $stmt->close();
    $koneksi->close();

} else {
    // Jika akses tidak sah, kembalikan ke halaman dosen
    header("Location: dosen.php");
    exit();
}
?>
