<?php
session_start();
include "koneksi.php";
include "auth.php";
wajib_role(['admin']);

$buku     = hitung($koneksi, "SELECT COUNT(*) FROM buku");
$anggota  = hitung($koneksi, "SELECT COUNT(*) FROM anggota");
$menunggu = hitung($koneksi, "SELECT COUNT(*) FROM peminjaman WHERE status='Menunggu'");
$dipinjam = hitung($koneksi, "SELECT COUNT(*) FROM peminjaman WHERE status='Dipinjam'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<main class="page">
  <div class="welcome">
    <h1>Dashboard Admin 📚</h1>
    <p>Halo, <?= e($_SESSION['nama']); ?>. Kelola seluruh data perpustakaan.</p>
  </div>

  <div class="grid c4" style="margin-bottom:26px">
    <div class="card stat"><b><?= $buku; ?></b><span>Judul buku</span></div>
    <div class="card stat"><b><?= $anggota; ?></b><span>Anggota</span></div>
    <div class="card stat"><b><?= $menunggu; ?></b><span>Menunggu persetujuan</span></div>
    <div class="card stat"><b><?= $dipinjam; ?></b><span>Sedang dipinjam</span></div>
  </div>

  <div class="grid c4">
    <a href="tampil.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">📚</span><h3>Buku</h3><p>Kelola data buku.</p>
    </a>
    <a href="anggota.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">👥</span><h3>Anggota</h3><p>Kelola data anggota.</p>
    </a>
    <a href="kategori.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">🏷️</span><h3>Kategori</h3><p>Kelola kategori buku.</p>
    </a>
    <a href="penerbit.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">🏢</span><h3>Penerbit</h3><p>Kelola data penerbit.</p>
    </a>
    <a href="petugas.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">🧑‍💼</span><h3>Petugas</h3><p>Kelola data petugas.</p>
    </a>
    <a href="peminjaman.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">📖</span><h3>Peminjaman</h3><p>Setujui dan pantau peminjaman.</p>
    </a>
    <a href="pengembalian.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">🔄</span><h3>Pengembalian</h3><p>Catat pengembalian buku.</p>
    </a>
    <a href="histori.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">📋</span><h3>Histori</h3><p>Riwayat aktivitas.</p>
    </a>
  </div>
</main>
</body>
</html>
