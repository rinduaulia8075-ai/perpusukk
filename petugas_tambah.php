<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $admin_id = 1;

    $query = mysqli_query($koneksi, "INSERT INTO petugas
        (nama, username, password, admin_id)
        VALUES
        ('$nama', '$username', '$password', '$admin_id')
    ");

    if ($query) {
        header("Location: petugas.php");
        exit;
    } else {
        echo "Gagal menambahkan Petugas: " . mysqli_error($koneksi);
    }
}
?>