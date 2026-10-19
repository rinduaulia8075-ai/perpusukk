<?php
include "koneksi.php";

$id = $_POST['buku_id'];
$judul = $_POST['judul'];
$penulis = $_POST['penulis'];
$sinopsis = $_POST['sinopsis'];
$stok = $_POST['stok'];

$query = mysqli_query($koneksi, "UPDATE buku SET
    judul = '$judul',
    penulis = '$penulis',
    sinopsis = '$sinopsis',
    stok = '$stok'
    WHERE buku_id = '$id'
");

if ($query) {
    header("Location: tampil_petugas.php");
    exit;
} else {
    echo "Gagal mengupdate data: " . mysqli_error($koneksi);
}
?>