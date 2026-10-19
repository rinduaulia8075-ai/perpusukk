<?php
session_start();
include "koneksi.php";
include "auth.php";
wajib_role(['petugas', 'admin']);

$menunggu = hitung($koneksi, "SELECT COUNT(*) FROM peminjaman WHERE status='Menunggu'");
$dipinjam = hitung($koneksi, "SELECT COUNT(*) FROM peminjaman WHERE status='Dipinjam'");
$buku     = hitung($koneksi, "SELECT COUNT(*) FROM buku");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<main class="page">
  <div class="welcome">
    <h1>Dashboard Petugas 🧑‍💼</h1>
    <p>Halo, <?= e($_SESSION['nama']); ?>. Kelola peminjaman, pengembalian, dan histori.</p>
  </div>

  <div class="grid c3" style="margin-bottom:26px">
    <div class="card stat"><b><?= $menunggu; ?></b><span>Menunggu persetujuan</span></div>
    <div class="card stat"><b><?= $dipinjam; ?></b><span>Sedang dipinjam</span></div>
    <div class="card stat"><b><?= $buku; ?></b><span>Judul buku</span></div>
  </div>

  <div class="grid c3">
    <a href="peminjaman.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">📖</span><h3>Peminjaman</h3><p>Setujui atau tolak peminjaman anggota.</p>
    </a>
    <a href="pengembalian.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">🔄</span><h3>Pengembalian</h3><p>Catat pengembalian buku.</p>
    </a>
    <a href="histori.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">📋</span><h3>Histori</h3><p>Riwayat aktivitas perpustakaan.</p>
    </a>
  </div>
</main>
</body>
</html>
