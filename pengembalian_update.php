<?php
include "koneksi.php";

if (isset($_POST['update'])) {

    $pengembalian_id = $_POST['pengembalian_id'];
    $peminjaman_id = $_POST['peminjaman_id'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $petugas_id = $_POST['petugas_id'];

    $query = mysqli_query($koneksi, "
        UPDATE pengembalian SET
            peminjaman_id='$peminjaman_id',
            tanggal_kembali='$tanggal_kembali',
            petugas_id='$petugas_id'
        WHERE pengembalian_id='$pengembalian_id'
    ");

    if ($query) {

        header("Location: pengembalian.php");
        exit;

    } else {

        echo "Gagal mengubah data pengembalian: "
             . mysqli_error($koneksi);
    }
}
?>