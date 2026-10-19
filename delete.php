<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

include "koneksi.php";

$id = $_GET['buku_id'];


// Cek apakah buku masih digunakan di peminjaman
$cek = mysqli_query($koneksi, "
    SELECT * 
    FROM peminjaman
    WHERE buku_id='$id'
");


// Kalau masih ada peminjaman
if (mysqli_num_rows($cek) > 0) {

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Buku Tidak Dapat Dihapus</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>BUKU TIDAK DAPAT DIHAPUS</h2>

<p>
    Buku ini tidak dapat dihapus karena sudah digunakan
    dalam data peminjaman.
</p>

<p>
    <b>Ketentuan:</b> Data buku yang sudah memiliki riwayat
    peminjaman tidak boleh dihapus agar data peminjaman
    tetap tersimpan.
</p>

<br>

<a href="tampil.php">
    <button>Kembali ke Data Buku</button>
</a>

</main>
</body>

</html>

<?php

    exit;
}


// Kalau tidak pernah digunakan di peminjaman,
// baru boleh dihapus

$query = mysqli_query(
    $koneksi,
    "DELETE FROM buku WHERE buku_id='$id'"
);


if ($query) {

    header("Location: tampil.php");
    exit;

} else {

    echo "Buku gagal dihapus: "
         . mysqli_error($koneksi);

}

?>