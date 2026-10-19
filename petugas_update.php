<?php
include "koneksi.php";

if (isset($_POST['update'])) {

    $petugas_id = $_POST['petugas_id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "UPDATE petugas SET
        nama='$nama',
        username='$username',
        password='$password'
        WHERE petugas_id='$petugas_id'
    ");

    if ($query) {
        header("Location: petugas.php");
        exit;
    } else {
        echo "Gagal mengubah data Petuagas: " . mysqli_error($koneksi);
    }
}
?>