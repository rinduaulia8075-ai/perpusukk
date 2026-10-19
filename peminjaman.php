<?php
session_start();
include "koneksi.php";
include "auth.php";

wajib_login();
$role = $_SESSION['role'];
$anggota = ($role == 'anggota');

$sql = "SELECT p.*, a.nama AS nama_anggota, b.judul AS judul_buku
        FROM peminjaman p
        JOIN anggota a ON p.anggota_id = a.anggota_id
        JOIN buku b ON p.buku_id = b.buku_id";

if ($anggota) {
    /* Anggota hanya melihat peminjaman miliknya sendiri */
    $sql .= " WHERE p.anggota_id = ? ORDER BY p.peminjaman_id DESC";
    $stmt = mysqli_prepare($koneksi, $sql);
    $aid = (int)$_SESSION['anggota_id'];
    mysqli_stmt_bind_param($stmt, "i", $aid);
} else {
    $sql .= " ORDER BY p.peminjaman_id DESC";
    $stmt = mysqli_prepare($koneksi, $sql);
}
mysqli_stmt_execute($stmt);
$rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

$jml = ['menunggu'=>0, 'dipinjam'=>0, 'kembali'=>0, 'ditolak'=>0];
foreach ($rows as $r) { $jml[kelas_status($r['status'])]++; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $anggota ? 'Peminjaman Saya' : 'Data Peminjaman'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<main class="page">
  <div class="page-head">
    <h2><?= $anggota ? '📖 Peminjaman Saya' : '📖 Data Peminjaman'; ?></h2>
    <?php if ($anggota) { ?><a class="btn" href="tampil.php">+ Pinjam Buku</a><?php } ?>
  </div>

  <div class="grid c4" style="margin-bottom:22px">
    <div class="card stat"><b><?= $jml['menunggu']; ?></b><span>Menunggu</span></div>
    <div class="card stat"><b><?= $jml['dipinjam']; ?></b><span>Sedang Dipinjam</span></div>
    <div class="card stat"><b><?= $jml['kembali']; ?></b><span>Dikembalikan</span></div>
    <div class="card stat"><b><?= $jml['ditolak']; ?></b><span>Ditolak</span></div>
  </div>

  <?php if (count($rows) == 0) { ?>
    <div class="card" style="text-align:center">
      <p class="muted">Belum ada data peminjaman.</p>
      <?php if ($anggota) { ?><a class="btn" href="tampil.php">Cari Buku untuk Dipinjam</a><?php } ?>
    </div>
  <?php } else { ?>

  <table>
    <tr>
      <th>No</th>
      <?php if (!$anggota) { ?><th>Anggota</th><?php } ?>
      <th>Buku</th>
      <th>Tanggal Pinjam</th>
      <th>Lama</th>
      <th>Jatuh Tempo</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
    <?php foreach ($rows as $data) { ?>
    <tr>
      <td>#<?= (int)$data['peminjaman_id']; ?></td>
      <?php if (!$anggota) { ?><td><?= e($data['nama_anggota']); ?></td><?php } ?>
      <td><?= e($data['judul_buku']); ?></td>
      <td><?= tanggal_id($data['tanggal_pinjam']); ?></td>
      <td><?= (int)$data['lama_pinjam']; ?> hari</td>
      <td><?= tanggal_id($data['tanggal_jatuh_tempo']); ?></td>
      <td><span class="badge <?= kelas_status($data['status']); ?>"><?= e($data['status']); ?></span></td>
      <td>
        <a href="peminjaman_detail.php?id=<?= (int)$data['peminjaman_id']; ?>"><button>Detail</button></a>
        <?php if (!$anggota) { ?>
          <?php if (strtolower($data['status']) == 'menunggu') { ?>
            <a href="peminjaman_disetujui.php?id=<?= (int)$data['peminjaman_id']; ?>"><button>Setujui</button></a>
            <a href="peminjaman_ditolak.php?id=<?= (int)$data['peminjaman_id']; ?>"
               onclick="return confirm('Tolak peminjaman ini?');"><button>Tolak</button></a>
          <?php } ?>
          <a href="peminjaman_edit.php?id=<?= (int)$data['peminjaman_id']; ?>"><button>Proses</button></a>
          <a href="peminjaman_delete.php?id=<?= (int)$data['peminjaman_id']; ?>"
             onclick="return confirm('Yakin ingin menghapus peminjaman ini?');"><button>Delete</button></a>
        <?php } ?>
      </td>
    </tr>
    <?php } ?>
  </table>
  <?php } ?>

  <?php if (!$anggota) { ?>
    <p class="muted" style="margin-top:14px">Peminjaman baru hanya dapat diajukan oleh anggota melalui akun masing-masing.</p>
    <a class="btn light" href="tampil_petugas.php">Data Buku</a>
  <?php } ?>
</main>
</body>
</html>
