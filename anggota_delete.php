<?php
include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "DELETE FROM anggota WHERE anggota_id = '$id'");

if ($query) {
    header("Location: anggota.php");
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>