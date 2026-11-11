<?php
session_start();
include "koneksi.php";
include "auth.php";

wajib_role(['admin', 'petugas']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: peminjaman.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$alasan = trim($_POST['alasan'] ?? '');

if ($id <= 0 || $alasan === '') {
    die("ID peminjaman dan alasan penolakan wajib diisi.");
}

// Ambil data peminjaman
$stmt = mysqli_prepare($koneksi, "
    SELECT buku_id
    FROM peminjaman
    WHERE peminjaman_id = ?
    AND status = 'Menunggu'
");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    die("Peminjaman tidak ditemukan atau sudah diproses.");
}

$buku_id = (int)$data['buku_id'];

// Mulai transaksi database
mysqli_begin_transaction($koneksi);

try {

    // Ubah status dan simpan alasan
    $update = mysqli_prepare($koneksi, "
        UPDATE peminjaman
        SET status = 'Ditolak',
            keterangan = ?
        WHERE peminjaman_id = ?
        AND status = 'Menunggu'
    ");

    mysqli_stmt_bind_param($update, "si", $alasan, $id);
    mysqli_stmt_execute($update);

    if (mysqli_stmt_affected_rows($update) !== 1) {
        throw new Exception("Gagal mengubah status peminjaman.");
    }

    // Kembalikan stok buku
    $stok = mysqli_prepare($koneksi, "
        UPDATE buku
        SET stok = stok + 1
        WHERE buku_id = ?
    ");

    mysqli_stmt_bind_param($stok, "i", $buku_id);
    mysqli_stmt_execute($stok);

    if (mysqli_stmt_affected_rows($stok) !== 1) {
        throw new Exception("Gagal mengembalikan stok buku.");
    }

    // Identitas pengguna dari session
    $role = $_SESSION['role'];
    $pengguna_id = (int)$_SESSION[$role . '_id'];
    $nama = $_SESSION['nama'];

    // Catat log transaksi
    $aktivitas = "Penolakan Peminjaman";
    $keterangan_log = "Peminjaman ID #$id ditolak. Alasan: $alasan";

    $log = mysqli_prepare($koneksi, "
        INSERT INTO log_transaksi
        (pengguna_id, nama_pengguna, role, aktivitas, keterangan)
        VALUES (?, ?, ?, ?, ?)
    ");

    mysqli_stmt_bind_param(
        $log,
        "issss",
        $pengguna_id,
        $nama,
        $role,
        $aktivitas,
        $keterangan_log
    );

    mysqli_stmt_execute($log);

    mysqli_commit($koneksi);

    header("Location: peminjaman.php");
    exit;

} catch (Throwable $e) {

    mysqli_rollback($koneksi);
    die("Gagal menolak peminjaman: " . $e->getMessage());
}
?>