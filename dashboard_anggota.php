<?php
session_start();
include "koneksi.php";
include "auth.php";
wajib_role(['anggota']);

$aid = (int)$_SESSION['anggota_id'];

$stmt = mysqli_prepare($koneksi, "SELECT p.peminjaman_id, p.tanggal_jatuh_tempo, p.status, b.judul
    FROM peminjaman p JOIN buku b ON p.buku_id = b.buku_id
    WHERE p.anggota_id = ? ORDER BY p.peminjaman_id DESC");
mysqli_stmt_bind_param($stmt, "i", $aid);
mysqli_stmt_execute($stmt);
$rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

$jml = ['menunggu'=>0, 'dipinjam'=>0, 'kembali'=>0];
foreach ($rows as $r) {
    $k = kelas_status($r['status']);
    if (isset($jml[$k])) { $jml[$k]++; }
}
$terbaru = array_slice($rows, 0, 3);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Anggota</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<main class="page">

  <div class="welcome">
    <h1>Selamat datang, <?= e($_SESSION['nama']); ?> 👋</h1>
    <p>Pilih buku yang ingin kamu baca, lalu ajukan peminjaman.</p>
  </div>

  <div class="grid c3" style="margin-bottom:26px">
    <div class="card stat"><b><?= $jml['menunggu']; ?></b><span>Menunggu persetujuan</span></div>
    <div class="card stat"><b><?= $jml['dipinjam']; ?></b><span>Sedang dipinjam</span></div>
    <div class="card stat"><b><?= $jml['kembali']; ?></b><span>Sudah dikembalikan</span></div>
  </div>

  <div class="grid c2" style="margin-bottom:30px">
    <a href="tampil.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">📚</span><h3>Daftar Buku</h3><p>Lihat koleksi dan pinjam buku.</p>
    </a>
    <a href="peminjaman.php" class="card menu-card" style="display:block;color:inherit">
      <span class="ico">📖</span><h3>Peminjaman Saya</h3><p>Lihat detail dan status peminjaman.</p>
    </a>
  </div>

  <?php if (count($terbaru) > 0) { ?>
  <h3>Peminjaman Terbaru</h3>
  <table>
    <tr><th>Buku</th><th>Jatuh Tempo</th><th>Status</th><th>Aksi</th></tr>
    <?php foreach ($terbaru as $t) { ?>
    <tr>
      <td><?= e($t['judul']); ?></td>
      <td><?= tanggal_id($t['tanggal_jatuh_tempo']); ?></td>
      <td><span class="badge <?= kelas_status($t['status']); ?>"><?= e($t['status']); ?></span></td>
      <td><a href="peminjaman_detail.php?id=<?= (int)$t['peminjaman_id']; ?>"><button>Detail</button></a></td>
    </tr>
    <?php } ?>
  </table>
  <?php } ?>
</main>
</body>
</html>
