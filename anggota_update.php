<?php
include "koneksi.php";

if (isset($_POST['update'])) {

    $anggota_id = $_POST['anggota_id'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $telpon = $_POST['telpon'];
    $alamat = $_POST['alamat'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "UPDATE anggota SET
        nama='$nama',
        jenis_kelamin='$jenis_kelamin',
        telpon='$telpon',
        alamat='$alamat',
        username='$username',
        password='$password'
        WHERE anggota_id='$anggota_id'
    ");

    if ($query) {
        header("Location: anggota.php");
        exit;
    } else {
        echo "Gagal mengubah data: " . mysqli_error($koneksi);
    }
}
?>