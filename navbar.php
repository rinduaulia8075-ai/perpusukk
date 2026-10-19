<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_r = $_SESSION['role'] ?? '';
$_n = $_SESSION['nama'] ?? '';
$_dash = ['admin'=>'dashboard_admin.php','petugas'=>'dashboard_petugas.php','anggota'=>'dashboard_anggota.php'][$_r] ?? 'index.php';
?>
<header class="topbar">
  <div class="topbar-inner">
    <a class="brand" href="<?= $_dash; ?>">📚 Perpustakaan</a>

    <nav class="topnav">
      <?php if ($_r != '') { ?><a href="<?= $_dash; ?>">Dashboard</a><?php } else { ?><a href="index.php">Beranda</a><?php } ?>
      <a href="tampil.php">Daftar Buku</a>
      <?php if ($_r == 'anggota') { ?>
        <a href="peminjaman.php">Peminjaman Saya</a>
      <?php } elseif ($_r == 'admin' || $_r == 'petugas') { ?>
        <a href="peminjaman.php">Peminjaman</a>
        <a href="pengembalian.php">Pengembalian</a>
        <a href="histori.php">Histori</a>
      <?php } ?>
    </nav>

    <div class="topuser">
      <?php if ($_r != '') { ?>
        <span>👤 <?= htmlspecialchars($_n, ENT_QUOTES, 'UTF-8'); ?> <small>(<?= htmlspecialchars($_r, ENT_QUOTES, 'UTF-8'); ?>)</small></span>
        <a class="btn-top out" href="logout.php">Logout</a>
      <?php } else { ?>
        <a class="btn-top ghost" href="login.php">Masuk</a>
        <a class="btn-top" href="register.php">Registrasi</a>
      <?php } ?>
    </div>
  </div>
</header>
