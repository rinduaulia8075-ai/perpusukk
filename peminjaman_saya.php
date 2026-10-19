<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['anggota_id']) || $_SESSION['role'] != 'anggota') {
    die("Halaman ini hanya untuk anggota.");
}

$anggota_id = $_SESSION['anggota_id'];

$query = mysqli_query($koneksi, "
    SELECT
        peminjaman.*,
        buku.judul AS judul_buku
    FROM peminjaman
    JOIN buku
        ON peminjaman.buku_id = buku.buku_id
    WHERE peminjaman.anggota_id = '$anggota_id'
    ORDER BY peminjaman.peminjaman_id DESC
");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Peminjaman Saya</title>
</head>

<body>

<h2>PEMINJAMAN SAYA</h2>

<p>
    Berikut adalah data peminjaman buku milik Anda.
</p>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>Buku</th>
    <th>Tanggal Pinjam</th>
    <th>Lama Pinjam</th>
    <th>Jatuh Tempo</th>
    <th>Status</th>
</tr>

<?php while ($data = mysqli_fetch_assoc($query)) { ?>

<tr>
    <td>
        <?= htmlspecialchars($data['judul_buku']); ?>
    </td>

    <td>
        <?= $data['tanggal_pinjam']; ?>
    </td>

    <td>
        <?= $data['lama_pinjam']; ?> hari
    </td>

    <td>
        <?= $data['tanggal_jatuh_tempo']; ?>
    </td>

    <td>
        <?= $data['status']; ?>
    </td>

</tr>

<?php } ?>

</table>

<br>
<br>
<?php include "tombol_dashboard.php"; ?>
</body>
</html>