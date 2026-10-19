<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "INSERT INTO admin
        (nama, username, password)
        VALUES
        ('$nama', '$username', '$password')
    ");

    if ($query) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Gagal menambahkan admin: " . mysqli_error($koneksi);
    }
}
?>