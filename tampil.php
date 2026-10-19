<?php

session_start();
include "koneksi.php";

$role = $_SESSION['role'] ?? '';

$query = mysqli_query($koneksi, "
    SELECT * FROM buku
");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Buku</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>DATA BUKU</h2>

<?php if ($role == 'admin' || $role == 'petugas') { ?>

    <a href="add_form.php">
        <button>Tambah Buku</button>
    </a>

<?php } ?>

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

        <?php if (!empty($data['gambar'])) { ?>

            <img
                src="uploads/<?= htmlspecialchars($data['gambar']); ?>"
                width="100"
            >

        <?php } else { ?>

            Tidak ada gambar

        <?php } ?>

    </td>

    <td>
        <?= htmlspecialchars($data['judul']); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['penulis']); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['sinopsis']); ?>
    </td>

    <td>
        <?= $data['stok']; ?>
    </td>

    <td>

        <!-- SEMUA USER BOLEH DETAIL -->

        <a href="detail.php?id=<?= $data['buku_id']; ?>">
            <button>Detail Buku</button>
        </a>


        <!-- KHUSUS ADMIN DAN PETUGAS -->

        <?php if ($role == 'admin' || $role == 'petugas') { ?>

            <a href="edit.php?id=<?= $data['buku_id']; ?>">
                <button>Edit</button>
            </a>

            <a
                href="delete.php?buku_id=<?= $data['buku_id']; ?>"
                onclick="return confirm('Yakin ingin menghapus buku ini?');"
            >
                <button>Delete</button>
            </a>

        <?php } ?>

    </td>

</tr>

<?php } ?>

</table>

<br>

<?php if ($role == 'anggota') { ?>

    <a href="dashboard_anggota.php">
        <button>Kembali ke Dashboard</button>
    </a>

<?php } elseif ($role == 'petugas') { ?>

    <a href="dashboard_petugas.php">
        <button>Kembali ke Dashboard</button>
    </a>

<?php } elseif ($role == 'admin') { ?>

    <a href="dashboard_admin.php">
        <button>Kembali ke Dashboard</button>
    </a>

<?php } else { ?>

    <a href="index.php">
        <button>Kembali</button>
    </a>

<?php } ?>

</main>
</body>
</html>