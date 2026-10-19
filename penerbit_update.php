<?php
include "koneksi.php";

if (isset($_POST['update'])) {

    $penerbit_id = $_POST['penerbit_id'];
    $nama = $_POST['nama'];

    $query = mysqli_query($koneksi, "UPDATE penerbit SET
        nama='$nama'
        WHERE penerbit_id='$penerbit_id'
    ");

    if ($query) {
        header("Location: penerbit.php");
        exit;
    } else {
        echo "Gagal mengubah penerbit: " . mysqli_error($koneksi);
    }
}
?>