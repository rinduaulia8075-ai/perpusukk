<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM buku");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Buku</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>DATA BUKU</h2>

<a href="add_form.php">
    <button>Tambah Buku</button>
</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>Gambar</th>
        <th>Judul</th>
        <th>Penulis</th>
        <th>Sinopsis</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>

    <?php while ($data = mysqli_fetch_assoc($query)) { ?>

    <tr>

        <td>
            <?php if (($data['gambar'])) { ?>
                <img src="uploads/<?= htmlspecialchars($data['gambar']); ?>" 
                     width="100">
            <?php } else { ?>
                Tidak ada gambar
            <?php } ?>
        </td>

        <td><?= htmlspecialchars($data['judul']); ?></td>

        <td><?= htmlspecialchars($data['penulis']); ?></td>

        <td><?= htmlspecialchars($data['sinopsis']); ?></td>

        <td><?= $data['stok']; ?></td>

        <td>

            <a href="edit.php?id=<?= $data['buku_id']; ?>">
                <button>Edit</button>
            </a>

            <a href="detail.php?id=<?= $data['buku_id']; ?>">
                <button>Detail Buku</button>
            </a>

            <a href="delete.php?buku_id=<?= $data['buku_id']; ?>"
               onclick="return confirm('Yakin ingin menghapus buku ini?');">
                <button>Delete</button>
            </a>

        </td>

    </tr>

    <?php } ?>

</table>

</main>
</body>
</html>