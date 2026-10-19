<?php
include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "DELETE FROM admin WHERE admin_id='$id'");

if ($query) {
    header("Location: admin.php");
    exit;
} else {
    echo "Gagal menghapus admin: " . mysqli_error($koneksi);
}
?>