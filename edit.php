<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM buku WHERE buku_id = '$id'");
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
    <title>Edit Data Buku</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>Edit Data Buku</h2>

<form action="update.php" method="POST">

    <input type="hidden" name="buku_id" value="<?= $data['buku_id']; ?>">

    <label>Judul</label><br>
    <input type="text" name="judul" value="<?= $data['judul']; ?>" required>
    <br><br>

    <label>Penulis</label><br>
    <input type="text" name="penulis" value="<?= $data['penulis']; ?>" required>
    <br><br>

    <label>Sinopsis</label><br>
    <textarea name="sinopsis" required><?= $data['sinopsis']; ?></textarea>
    <br><br>

    <label>Stok</label><br>
    <input type="number" name="stok" value="<?= $data['stok']; ?>" min="0" required>
    <br><br>

    <button type="submit">Update Data</button>

</form>

<br>
<a href="tampil_petugas.php">Kembali</a>

</main>
</body>
</html>