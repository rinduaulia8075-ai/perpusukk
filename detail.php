<?php

session_start();
include "koneksi.php";

$role = $_SESSION['role'] ?? '';

if (!isset($_GET['id'])) {
    die("Buku tidak ditemukan");
}

$id = $_GET['id'];

$query = mysqli_query($koneksi, "
    SELECT 
        buku.*,
        kategori.nama_kategori
    FROM buku
    LEFT JOIN kategori
        ON buku.kategori_id = kategori.kategori_id
    WHERE buku.buku_id='$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data buku tidak ditemukan";
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Buku</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>DETAIL BUKU</h2>
<?php if (!empty($data['gambar'])) { ?>
    <p><img src="uploads/<?= htmlspecialchars($data['gambar']); ?>" alt="Sampul" style="max-width:200px"></p>
<?php } ?>

<table class="detail" border="1" cellpadding="10" cellspacing="0">

<tr>
    <td>Judul</td>
    <td>
        <?= htmlspecialchars($data['judul']); ?>
    </td>
</tr>

<tr>
    <td>Kategori</td>
    <td>
        <?= htmlspecialchars($data['nama_kategori']); ?>
    </td>
</tr>

<tr>
    <td>Penulis</td>
    <td>
        <?= htmlspecialchars($data['penulis']); ?>
    </td>
</tr>

<tr>
    <td>ISBN</td>
    <td>
        <?= htmlspecialchars($data['ISBN']); ?>
    </td>
</tr>

<tr>
    <td>Jumlah Halaman</td>
    <td>
        <?= $data['jumlah_halaman']; ?>
    </td>
</tr>

<tr>
    <td>Stok</td>
    <td>
        <?= $data['stok']; ?>
    </td>
</tr>

<tr>
    <td>Tahun Terbit</td>
    <td>
        <?= $data['tahun_terbit']; ?>
    </td>
</tr>

<tr>
    <td>Sinopsis</td>
    <td>
        <?= htmlspecialchars($data['sinopsis']); ?>
    </td>
</tr>

</table>

<br>


<!-- ================================= -->
<?php if ($role == '') { ?>
    <a href="login.php"><button>Login untuk Meminjam Buku</button></a>
<?php } ?>
<!-- KHUSUS ANGGOTA -->
<!-- ================================= -->

<?php if ($role == 'anggota') { ?>

    <?php if ($data['stok'] > 0) { ?>

        <a href="pinjam_buku.php?id=<?= $data['buku_id']; ?>">

            <button>
                📖 Pinjam Buku
            </button>

        </a>

    <?php } else { ?>

        <button disabled>
            Stok Habis
        </button>

    <?php } ?>

<?php } ?>


<br><br>

<?php if ($role == 'anggota') { ?>

    <a href="tampil.php">
        <button>Kembali</button>
    </a>

<?php } elseif ($role == 'petugas') { ?>

    <a href="tampil.php">
        <button>Kembali</button>
    </a>

<?php } elseif ($role == 'admin') { ?>

    <a href="tampil.php">
        <button>Kembali</button>
    </a>

<?php } ?>

</main>
</body>
</html>