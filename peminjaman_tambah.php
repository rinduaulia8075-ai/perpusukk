<?php
session_start();
include "auth.php";

/* Peminjaman hanya dilakukan oleh anggota lewat halaman buku. */
wajib_login();
if ($_SESSION['role'] == 'anggota') {
    header("Location: tampil.php");
    exit;
}
akses_ditolak('Peminjaman baru hanya dapat dilakukan oleh anggota melalui akunnya sendiri.');
