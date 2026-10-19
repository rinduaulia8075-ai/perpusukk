<?php
include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "DELETE FROM petugas WHERE petugas_id='$id'");

if ($query) {
    header("Location: petugas.php");
    exit;
} else {
    echo "Gagal menghapus Petugas: " . mysqli_error($koneksi);
}
?>