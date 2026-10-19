<?php
session_start();
include "koneksi.php";
include "auth.php";

wajib_login();

if (!isset($_GET['id'])) {
    akses_ditolak('Data peminjaman tidak ditemukan.');
}
$id   = (int)$_GET['id'];
$role = $_SESSION['role'];

$stmt = mysqli_prepare($koneksi, "SELECT p.*,
        a.nama AS nama_anggota, a.telpon, a.alamat, a.username,
        b.judul, b.penulis, b.ISBN, b.gambar, b.tahun_terbit, b.jumlah_halaman,
        k.nama_kategori
    FROM peminjaman p
    JOIN anggota a ON p.anggota_id = a.anggota_id
    JOIN buku b ON p.buku_id = b.buku_id
    LEFT JOIN kategori k ON b.kategori_id = k.kategori_id
    WHERE p.peminjaman_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$d = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$d) {
    akses_ditolak('Data peminjaman tidak ditemukan.');
}

/* Anggota hanya boleh melihat peminjamannya sendiri */
if ($role == 'anggota' && (int)$d['anggota_id'] !== (int)$_SESSION['anggota_id']) {
    akses_ditolak('Anda hanya dapat melihat peminjaman milik Anda sendiri.');
}

/* Sisa hari / keterlambatan */
$info_waktu = '';
$denda = 0;
if (strtolower($d['status']) == 'dipinjam') {
    $selisih = (int)floor((strtotime($d['tanggal_jatuh_tempo']) - strtotime(date('Y-m-d'))) / 86400);
    if ($selisih >= 0) {
        $info_waktu = "Sisa waktu pinjam: $selisih hari";
    } else {
        $telat = abs($selisih);
        $denda = $telat * 1000;
        $info_waktu = "Terlambat $telat hari (perkiraan denda Rp " . number_format($denda, 0, ',', '.') . ")";
    }
}
$st = strtolower($d['status']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman #<?= (int)$d['peminjaman_id']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<main class="page">

  <?php if (isset($_GET['baru'])) { ?>
    <div class="alert ok">✅ Peminjaman berhasil diajukan! Silakan menunggu persetujuan petugas, lalu ambil buku di perpustakaan.</div>
  <?php } ?>

  <div class="page-head">
    <div>
      <div class="loan-no">Peminjaman #<?= (int)$d['peminjaman_id']; ?></div>
      <h2>Detail Peminjaman</h2>
    </div>
    <span class="badge <?= kelas_status($d['status']); ?>" style="font-size:1rem"><?= e($d['status']); ?></span>
  </div>

  <?php if ($info_waktu != '') { ?><div class="alert <?= $denda > 0 ? 'err' : 'info'; ?>"><?= e($info_waktu); ?></div><?php } ?>
  <?php if ($st == 'menunggu') { ?><div class="alert info">Menunggu persetujuan petugas perpustakaan.</div><?php } ?>
  <?php if ($st == 'ditolak') { ?><div class="alert err">Peminjaman ini ditolak oleh petugas.</div><?php } ?>

  <div class="loan">
    <div class="cover">
      <?php if (!empty($d['gambar'])) { ?>
        <img src="uploads/<?= e($d['gambar']); ?>" alt="Sampul buku">
      <?php } else { ?><div class="nocover">📚</div><?php } ?>
    </div>

    <div>
      <h3>Data Buku</h3>
      <table class="detail">
        <tr><td>Judul</td><td><?= e($d['judul']); ?></td></tr>
        <tr><td>Penulis</td><td><?= e($d['penulis']); ?></td></tr>
        <tr><td>Kategori</td><td><?= e($d['nama_kategori'] ?? '-'); ?></td></tr>
        <tr><td>ISBN</td><td><?= e($d['ISBN']); ?></td></tr>
        <tr><td>Tahun Terbit</td><td><?= e($d['tahun_terbit']); ?></td></tr>
        <tr><td>Jumlah Halaman</td><td><?= e($d['jumlah_halaman']); ?></td></tr>
      </table>

      <h3 style="margin-top:22px">Data Peminjaman</h3>
      <table class="detail">
        <tr><td>Peminjam</td><td><?= e($d['nama_anggota']); ?> (<?= e($d['username']); ?>)</td></tr>
        <tr><td>No. Telepon</td><td><?= e($d['telpon']); ?></td></tr>
        <tr><td>Alamat</td><td><?= e($d['alamat']); ?></td></tr>
        <tr><td>Tanggal Pinjam</td><td><?= tanggal_id($d['tanggal_pinjam']); ?></td></tr>
        <tr><td>Lama Pinjam</td><td><?= (int)$d['lama_pinjam']; ?> hari</td></tr>
        <tr><td>Jatuh Tempo</td><td><b><?= tanggal_id($d['tanggal_jatuh_tempo']); ?></b></td></tr>
        <tr><td>Keterangan</td><td><?= $d['keterangan'] != '' ? e($d['keterangan']) : '-'; ?></td></tr>
        <tr><td>Status</td><td><span class="badge <?= kelas_status($d['status']); ?>"><?= e($d['status']); ?></span></td></tr>
        <tr><td>Denda keterlambatan</td><td>Rp 1.000 per hari setelah jatuh tempo</td></tr>
      </table>

      <div class="actions-row">
        <a class="btn" href="peminjaman.php"><?= $role == 'anggota' ? 'Peminjaman Saya' : 'Data Peminjaman'; ?></a>
        <?php if ($role == 'anggota') { ?><a class="btn light" href="tampil.php">Pinjam Buku Lain</a><?php } ?>
        <?php if (($role == 'admin' || $role == 'petugas') && $st == 'menunggu') { ?>
          <a class="btn ok" href="peminjaman_disetujui.php?id=<?= (int)$d['peminjaman_id']; ?>">Setujui</a>
          <a class="btn danger" href="peminjaman_ditolak.php?id=<?= (int)$d['peminjaman_id']; ?>"
             onclick="return confirm('Tolak peminjaman ini?');">Tolak</a>
        <?php } ?>
        <a class="btn light" href="#" onclick="window.print();return false;">🖨 Cetak</a>
      </div>
    </div>
  </div>
</main>
</body>
</html>
