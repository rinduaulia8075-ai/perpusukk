<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM penerbit");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Penerbit</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>DATA PENERBIT</h2>

<a href="penerbit_tambah.php">
    <button>Tambah Penerbit</button>
</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>ID Penerbit</th>
    <th>Nama Penerbit</th>
    <th>Aksi</th>
</tr>

<?php while ($data = mysqli_fetch_assoc($query)) { ?>

<tr>
    <td><?= $data['penerbit_id']; ?></td>

    <td><?= htmlspecialchars($data['nama']); ?></td>

    <td>
        <a href="penerbit_edit.php?id=<?= $data['penerbit_id']; ?>">
            <button>Edit</button>
        </a>

        <a href="penerbit_delete.php?id=<?= $data['penerbit_id']; ?>"
           onclick="return confirm('Yakin ingin menghapus penerbit ini?');">
            <button>Delete</button>
        </a>
    </td>
</tr>

<?php } ?>

</table>

<br>

<a href="tampil.php">
    <button>Data Buku</button>
</a>

</main>
</body>
</html>