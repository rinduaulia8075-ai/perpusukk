<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$query = mysqli_query($koneksi, "
    SELECT
        pengembalian.*,
        anggota.nama AS nama_anggota,
        buku.judul AS judul_buku,
        petugas.nama AS nama_petugas
    FROM pengembalian
    JOIN peminjaman
        ON pengembalian.peminjaman_id = peminjaman.peminjaman_id
    JOIN anggota
        ON peminjaman.anggota_id = anggota.anggota_id
    JOIN buku
        ON peminjaman.buku_id = buku.buku_id
    JOIN petugas
        ON pengembalian.petugas_id = petugas.petugas_id
");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pengembalian</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>DATA PENGEMBALIAN</h2>

<a href="pengembalian_tambah.php">
    <button>Tambah Pengembalian</button>
</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>ID</th>
    <th>Anggota</th>
    <th>Buku</th>
    <th>Tanggal Kembali</th>
    <th>Petugas</th>
    <th>Aksi</th>
</tr>

<?php while ($data = mysqli_fetch_assoc($query)) { ?>

<tr>

    <td><?= $data['pengembalian_id']; ?></td>

    <td>
        <?= htmlspecialchars($data['nama_anggota']); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['judul_buku']); ?>
    </td>

    <td>
        <?= $data['tanggal_kembali']; ?>
    </td>

    <td>
        <?= htmlspecialchars($data['nama_petugas']); ?>
    </td>

    <td>

        <a href="pengembalian_edit.php?id=<?= $data['pengembalian_id']; ?>">
            <button>Edit</button>
        </a>

        <a href="pengembalian_delete.php?id=<?= $data['pengembalian_id']; ?>"
           onclick="return confirm('Yakin ingin menghapus data pengembalian ini?');">
            <button>Delete</button>
        </a>

    </td>

</tr>

<?php } ?>

</table>

<br>

<a href="peminjaman.php">
    <button>Data Peminjaman</button>
</a>

</main>
</body>
</html>