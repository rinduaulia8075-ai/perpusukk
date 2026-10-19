<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM anggota");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Anggota</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>DATA ANGGOTA</h2>

<a href="anggota_tambah.html">
    <button>Tambah Anggota</button>
</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>Nama</th>
        <th>Jenis Kelamin</th>
        <th>Telpon</th>
        <th>Alamat</th>
        <th>Username</th>
        <th>Aksi</th>
    </tr>

    <?php while ($data = mysqli_fetch_assoc($query)) { ?>

    <tr>

        <td><?= htmlspecialchars($data['nama']); ?></td>

        <td><?= htmlspecialchars($data['jenis_kelamin']); ?></td>

        <td><?= htmlspecialchars($data['telpon']); ?></td>

        <td><?= htmlspecialchars($data['alamat']); ?></td>

        <td><?= htmlspecialchars($data['username']); ?></td>

        <td>
            <a href="anggota_edit.php?id=<?= $data['anggota_id']; ?>">
                <button>Edit</button>
            </a>

            <a href="anggota_delete.php?id=<?= $data['anggota_id']; ?>"
               onclick="return confirm('Yakin ingin menghapus anggota ini?');">
                <button>Delete</button>
            </a>
        </td>

    </tr>

    <?php } ?>

</table>

<br>

<a href="anggota.php">
    <button>Data Anggota</button>
</a>

</main>
</body>
</html>