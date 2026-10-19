<?php
include "koneksi.php";

if (isset($_POST['update'])) {

    $admin_id = $_POST['admin_id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "UPDATE admin SET
        nama='$nama',
        username='$username',
        password='$password'
        WHERE admin_id='$admin_id'
    ");

    if ($query) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Gagal mengubah data admin: " . mysqli_error($koneksi);
    }
}
?>