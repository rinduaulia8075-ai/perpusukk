<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM kategori");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Kategori</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>DATA KATEGORI</h2>

<a href="kategori_tambah.php">
    <button>Tambah Kategori</button>
</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>ID Kategori</th>
    <th>Nama Kategori</th>
    <th>Aksi</th>
</tr>

<?php while ($data = mysqli_fetch_assoc($query)) { ?>

<tr>

    <td><?= $data['kategori_id']; ?></td>

    <td>
        <?= htmlspecialchars($data['nama_kategori']); ?>
    </td>

    <td>

        <a href="kategori_edit.php?id=<?= $data['kategori_id']; ?>">
            <button>Edit</button>
        </a>

        <a href="kategori_delete.php?id=<?= $data['kategori_id']; ?>"
           onclick="return confirm('Yakin ingin menghapus kategori ini?');">
            <button>Delete</button>
        </a>

    </td>

</tr>

<?php } ?>

</table>

<br>

<a href="dashboard_admin.php">
    <button>Kembali</button>
</a>

</main>
</body>
</html>