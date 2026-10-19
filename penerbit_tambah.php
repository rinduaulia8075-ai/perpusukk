<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $admin_id = 1;

    $query = mysqli_query($koneksi, "INSERT INTO penerbit
        (nama, admin_id)
        VALUES
        ('$nama', '$admin_id')
    ");

    if ($query) {
        header("Location: penerbit.php");
        exit;
    } else {
        echo "Gagal menambahkan penerbit: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Penerbit</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>TAMBAH PENERBIT</h2>

<form action="penerbit_tambah.php" method="POST">

    <label>Nama Penerbit</label><br>
    <input type="text" name="nama" required>

    <br><br>

    <button type="submit" name="simpan">Simpan</button>

</form>

<br>

<a href="penerbit.php">Kembali</a>

</main>
</body>
</html>