<?php
session_start();
include "koneksi.php";
include "auth.php";
wajib_role(['admin', 'petugas']);

$id = (int)($_GET['id'] ?? 0);

$stmt = mysqli_prepare($koneksi, "UPDATE peminjaman SET status='Dipinjam'
    WHERE peminjaman_id = ? AND status = 'Menunggu'");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

/* Catat ke histori hanya jika benar-benar berubah dari Menunggu */
if (mysqli_stmt_affected_rows($stmt) > 0) {
    $h = mysqli_prepare($koneksi, "INSERT INTO histori (peminjaman_id, jenis_aktivitas, denda)
        VALUES (?, 'Peminjaman', 0)");
    mysqli_stmt_bind_param($h, "i", $id);
    mysqli_stmt_execute($h);
}

header("Location: peminjaman.php");
exit;
