<?php
session_start();
include "koneksi.php";
include "auth.php";

/* Hanya ANGGOTA yang boleh meminjam */
wajib_role(['anggota'], 'Peminjaman buku hanya dapat dilakukan oleh anggota. Silakan login sebagai anggota.');

$anggota_id = (int)$_SESSION['anggota_id'];

if (!isset($_GET['id'])) {
    akses_ditolak('Buku tidak ditemukan.');
}
$buku_id = (int)$_GET['id'];

$stmt = mysqli_prepare($koneksi, "SELECT buku.*, kategori.nama_kategori
    FROM buku LEFT JOIN kategori ON buku.kategori_id = kategori.kategori_id
    WHERE buku.buku_id = ?");
mysqli_stmt_bind_param($stmt, "i", $buku_id);
mysqli_stmt_execute($stmt);
$buku = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$buku) {
    akses_ditolak('Data buku tidak ditemukan.');
}

/* Apakah anggota ini masih punya pinjaman aktif untuk buku yang sama? */
$stmt = mysqli_prepare($koneksi, "SELECT peminjaman_id FROM peminjaman
    WHERE anggota_id = ? AND buku_id = ? AND status IN ('Menunggu','Dipinjam') LIMIT 1");
mysqli_stmt_bind_param($stmt, "ii", $anggota_id, $buku_id);
mysqli_stmt_execute($stmt);
$aktif = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$error = '';

if (isset($_POST['simpan'])) {

    $lama_pinjam = (int)$_POST['lama_pinjam'];
    $keterangan  = trim($_POST['keterangan']);

    if ($aktif) {
        $error = "Anda masih memiliki pinjaman aktif untuk buku ini.";
    } elseif ($lama_pinjam < 1 || $lama_pinjam > 30) {
        $error = "Lama pinjam harus antara 1 sampai 30 hari.";
    } else {

        $tanggal_pinjam      = date('Y-m-d');
        $tanggal_jatuh_tempo = date('Y-m-d', strtotime("$tanggal_pinjam + $lama_pinjam days"));
        $status              = "Menunggu";
        $admin_id            = admin_default_id($koneksi);

        try {
            mysqli_begin_transaction($koneksi);

            /* Kurangi stok hanya jika masih ada (aman dari rebutan stok) */
            $s1 = mysqli_prepare($koneksi, "UPDATE buku SET stok = stok - 1 WHERE buku_id = ? AND stok > 0");
            mysqli_stmt_bind_param($s1, "i", $buku_id);
            mysqli_stmt_execute($s1);

            if (mysqli_stmt_affected_rows($s1) < 1) {
                mysqli_rollback($koneksi);
                $error = "Maaf, stok buku sedang habis.";
            } else {
                $s2 = mysqli_prepare($koneksi, "INSERT INTO peminjaman
                    (tanggal_pinjam, lama_pinjam, tanggal_jatuh_tempo, keterangan, status, anggota_id, admin_id, buku_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($s2, "sisssiii",
                    $tanggal_pinjam, $lama_pinjam, $tanggal_jatuh_tempo, $keterangan, $status,
                    $anggota_id, $admin_id, $buku_id);
                mysqli_stmt_execute($s2);
                $id_baru = mysqli_insert_id($koneksi);

                mysqli_commit($koneksi);

                /* Tampilkan detail peminjaman yang baru dibuat */
                header("Location: peminjaman_detail.php?id=" . $id_baru . "&baru=1");
                exit;
            }
        } catch (Throwable $ex) {
            mysqli_rollback($koneksi);
            $error = "Gagal melakukan peminjaman: " . $ex->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<main class="page">
  <div class="page-head"><h2>📖 Pinjam Buku</h2></div>

  <?php if ($error != '') { ?><div class="alert err"><?= e($error); ?></div><?php } ?>
  <?php if ($aktif) { ?>
    <div class="alert info">Anda sudah meminjam buku ini.
      <a href="peminjaman_detail.php?id=<?= (int)$aktif['peminjaman_id']; ?>"><b>Lihat detail peminjaman</b></a></div>
  <?php } ?>

  <div class="loan">
    <div class="cover">
      <?php if (!empty($buku['gambar'])) { ?>
        <img src="uploads/<?= e($buku['gambar']); ?>" alt="Sampul">
      <?php } else { ?><div class="nocover">📚</div><?php } ?>
    </div>

    <div>
      <div class="card" style="margin-bottom:20px">
        <h3><?= e($buku['judul']); ?></h3>
        <p class="muted">Penulis: <?= e($buku['penulis']); ?>
          <?php if (!empty($buku['nama_kategori'])) { ?> &middot; Kategori: <?= e($buku['nama_kategori']); ?><?php } ?></p>
        <p>Stok tersedia: <b><?= (int)$buku['stok']; ?></b></p>
      </div>

      <form action="" method="POST">
        <label>Tanggal Pinjam</label>
        <input type="text" value="<?= tanggal_id(date('Y-m-d')); ?>" readonly>

        <label>Lama Pinjam (hari, maksimal 30)</label>
        <input type="number" name="lama_pinjam" min="1" max="30" value="<?= e($_POST['lama_pinjam'] ?? 7); ?>" required>

        <label>Keterangan (opsional)</label>
        <textarea name="keterangan"><?= e($_POST['keterangan'] ?? ''); ?></textarea>

        <button type="submit" name="simpan" <?= ($aktif || $buku['stok'] <= 0) ? 'disabled' : ''; ?>>
          <?= $buku['stok'] <= 0 ? 'Stok Habis' : 'Pinjam Buku'; ?>
        </button>
        <a class="btn light" href="detail.php?id=<?= (int)$buku['buku_id']; ?>" style="margin-top:22px">Kembali</a>
      </form>
    </div>
  </div>
</main>
</body>
</html>
