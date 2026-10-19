<?php
include_once "auth.php";
wajib_role(['admin', 'petugas']);
include "koneksi.php";

if (isset($_POST['update'])) {

    $peminjaman_id = $_POST['peminjaman_id'];
    $anggota_id = $_POST['anggota_id'];
    $buku_id = $_POST['buku_id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $lama_pinjam = $_POST['lama_pinjam'];
    $tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
    $keterangan = $_POST['keterangan'];
    $status = $_POST['status'];

    $query = mysqli_query($koneksi, "UPDATE peminjaman SET
        anggota_id='$anggota_id',
        buku_id='$buku_id',
        tanggal_pinjam='$tanggal_pinjam',
        lama_pinjam='$lama_pinjam',
        tanggal_jatuh_tempo='$tanggal_jatuh_tempo',
        keterangan='$keterangan',
        status='$status'
        WHERE peminjaman_id='$peminjaman_id'
    ");

    if ($query) {
        header("Location: peminjaman.php");
        exit;
    } else {
        echo "Gagal mengubah peminjaman: " . mysqli_error($koneksi);
    }
}
?>