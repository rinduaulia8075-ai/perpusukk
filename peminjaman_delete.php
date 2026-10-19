<?php
session_start();
include "koneksi.php";
include "auth.php";
wajib_role(['admin', 'petugas']);

$id = (int)($_GET['id'] ?? $_GET['peminjaman_id'] ?? 0);

/* Ambil data dulu: bila masih Menunggu/Dipinjam, stok buku dikembalikan */
$s = mysqli_prepare($koneksi, "SELECT buku_id, status FROM peminjaman WHERE peminjaman_id = ?");
mysqli_stmt_bind_param($s, "i", $id);
mysqli_stmt_execute($s);
$p = mysqli_fetch_assoc(mysqli_stmt_get_result($s));

if (!$p) {
    header("Location: peminjaman.php");
    exit;
}

try {
    $d = mysqli_prepare($koneksi, "DELETE FROM peminjaman WHERE peminjaman_id = ?");
    mysqli_stmt_bind_param($d, "i", $id);
    mysqli_stmt_execute($d);

    if (in_array(strtolower($p['status']), ['menunggu', 'dipinjam'], true)) {
        $u = mysqli_prepare($koneksi, "UPDATE buku SET stok = stok + 1 WHERE buku_id = ?");
        $bid = (int)$p['buku_id'];
        mysqli_stmt_bind_param($u, "i", $bid);
        mysqli_stmt_execute($u);
    }
    header("Location: peminjaman.php");
    exit;
} catch (Throwable $ex) {
    akses_ditolak("Peminjaman tidak dapat dihapus karena sudah memiliki data pengembalian/histori terkait.", "Tidak Dapat Dihapus");
}
