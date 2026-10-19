<?php
session_start();
include "koneksi.php";
include "auth.php";
wajib_role(['admin', 'petugas']);

$id = (int)($_GET['id'] ?? 0);

$stmt = mysqli_prepare($koneksi, "UPDATE peminjaman SET status='Ditolak'
    WHERE peminjaman_id = ? AND status = 'Menunggu'");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

/* Stok dikembalikan karena sudah dikurangi saat anggota mengajukan */
if (mysqli_stmt_affected_rows($stmt) > 0) {
    $s = mysqli_prepare($koneksi, "UPDATE buku SET stok = stok + 1
        WHERE buku_id = (SELECT buku_id FROM peminjaman WHERE peminjaman_id = ?)");
    mysqli_stmt_bind_param($s, "i", $id);
    try { mysqli_stmt_execute($s); } catch (Throwable $ex) {}
}

header("Location: peminjaman.php");
exit;
