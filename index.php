<?php
session_start();
include "koneksi.php";
include "auth.php";

$role  = $_SESSION['role'] ?? '';
$dash  = ['admin'=>'dashboard_admin.php','petugas'=>'dashboard_petugas.php','anggota'=>'dashboard_anggota.php'][$role] ?? '';
$terbaru = [];
try {
    $q = mysqli_query($koneksi, "SELECT buku_id, judul, penulis, gambar FROM buku ORDER BY buku_id DESC LIMIT 4");
    if ($q) { $terbaru = mysqli_fetch_all($q, MYSQLI_ASSOC); }
} catch (Throwable $ex) {}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<main class="page">

  <section class="hero">
    <h1>Selamat Datang di Perpustakaan</h1>
    <p>Temukan buku favoritmu, ajukan peminjaman secara online, dan pantau status peminjamanmu kapan saja.</p>
    <div class="actions">
      <?php if ($role != '') { ?>
        <a class="btn" href="<?= $dash; ?>">Ke Dashboard</a>
        <a class="btn light" href="logout.php">Logout</a>
      <?php } else { ?>
        <a class="btn" href="register.php">Registrasi Anggota</a>
        <a class="btn light" href="login.php">Masuk</a>
      <?php } ?>
    </div>
  </section>

  <h2 style="text-align:center">Tentang Aplikasi</h2>
  <div class="grid c3" style="margin-bottom:34px">
    <div class="card menu-card"><span class="ico">📚</span><h3>Koleksi Buku</h3><p>Lihat daftar buku dan informasi lengkap setiap judul.</p></div>
    <div class="card menu-card"><span class="ico">📖</span><h3>Peminjaman</h3><p>Anggota dapat mengajukan peminjaman dan melihat detailnya.</p></div>
    <div class="card menu-card"><span class="ico">🔄</span><h3>Pengembalian</h3><p>Petugas mencatat pengembalian dan denda secara otomatis.</p></div>
  </div>

  <?php if (count($terbaru) > 0) { ?>
  <h2 style="text-align:center">Buku Terbaru</h2>
  <div class="grid c4">
    <?php foreach ($terbaru as $b) { ?>
    <a class="card book-card" href="detail.php?id=<?= (int)$b['buku_id']; ?>" style="color:inherit">
      <?php if (!empty($b['gambar'])) { ?><img src="uploads/<?= e($b['gambar']); ?>" alt=""><?php } else { ?><div class="nocover">📚</div><?php } ?>
      <div class="in"><h3 style="margin:0"><?= e($b['judul']); ?></h3><span class="muted"><?= e($b['penulis']); ?></span></div>
    </a>
    <?php } ?>
  </div>
  <?php } ?>
</main>

<footer class="foot">&copy; <?= date('Y'); ?> Sistem Informasi Perpustakaan</footer>
</body>
</html>
