<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

include "koneksi.php";

$query = mysqli_query($koneksi, "
    SELECT
        histori.*,
        anggota.nama AS nama_anggota,
        buku.judul AS judul_buku

    FROM histori

    JOIN peminjaman
    ON histori.peminjaman_id = peminjaman.peminjaman_id

    JOIN anggota
    ON peminjaman.anggota_id = anggota.anggota_id

    JOIN buku
    ON peminjaman.buku_id = buku.buku_id

    ORDER BY histori.tanggal_histori DESC
");

if (!$query) {

    die("Query gagal: " . mysqli_error($koneksi));

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Data Histori</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>DATA HISTORI</h2>

<p>
    Histori dibuat otomatis oleh sistem berdasarkan aktivitas peminjaman dan pengembalian.
</p>

<table border="1" cellpadding="10" cellspacing="0">

<tr>

    <th>ID</th>

    <th>Anggota</th>

    <th>Buku</th>

    <th>Tanggal Histori</th>

    <th>Jenis Aktivitas</th>

    <th>Denda</th>

</tr>


<?php while ($data = mysqli_fetch_assoc($query)) { ?>

<tr>

    <td>
        <?= $data['histori_id']; ?>
    </td>

    <td>
        <?= htmlspecialchars($data['nama_anggota']); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['judul_buku']); ?>
    </td>

    <td>
        <?= $data['tanggal_histori']; ?>
    </td>

    <td>
        <?= $data['jenis_aktivitas']; ?>
    </td>

    <td>
        Rp <?= number_format($data['denda'], 0, ',', '.'); ?>
    </td>

</tr>

<?php } ?>

</table>

<br>

<a href="pengembalian.php">

    <button>
        Data Pengembalian
    </button>

</a>

</main>
</body>

</html>