<?php
include_once "auth.php";
wajib_role(['admin', 'petugas']);
include "koneksi.php";

if (isset($_POST['update'])) {

    $peminjaman_id = (int) $_POST['peminjaman_id'];
    $anggota_id = (int) $_POST['anggota_id'];
    $buku_id = (int) $_POST['buku_id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $lama_pinjam = (int) $_POST['lama_pinjam'];
    $tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
    $keterangan = $_POST['keterangan'];
    $status = $_POST['status'];

    // Ambil identitas pengguna dari session
    $role = $_SESSION['role'];
    $pengguna_id = (int) $_SESSION[$role . '_id'];
    $nama_pengguna = $_SESSION['nama'];

    // Ambil status sebelumnya
    $cek = mysqli_query($koneksi, "
        SELECT status FROM peminjaman
        WHERE peminjaman_id='$peminjaman_id'
    ");

    $data_lama = mysqli_fetch_assoc($cek);

    if (!$data_lama) {
        die("Data peminjaman tidak ditemukan.");
    }

    $status_lama = $data_lama['status'];

    // Update peminjaman
    $query = mysqli_query($koneksi, "UPDATE peminjaman SET
        anggota_id='$anggota_id',
        buku_id='$buku_id',
        tanggal_pinjam='$tanggal_pinjam',
        lama_pinjam='$lama_pinjam',
        tanggal_jatuh_tempo='$tanggal_jatuh_tempo',
        keterangan='" . mysqli_real_escape_string($koneksi, $keterangan) . "',
        status='" . mysqli_real_escape_string($koneksi, $status) . "'
        WHERE peminjaman_id='$peminjaman_id'
    ");

    if ($query) {

        // Tentukan jenis aktivitas
        if ($status_lama != $status) {
            $aktivitas = "Ubah Status Peminjaman";
        } else {
            $aktivitas = "Edit Peminjaman";
        }

        $detail = "Pengguna $nama_pengguna mengubah peminjaman ID #$peminjaman_id. Status: $status_lama menjadi $status.";

        // Simpan ke log transaksi
        $stmt = mysqli_prepare($koneksi, "
            INSERT INTO log_transaksi
            (pengguna_id, nama_pengguna, role, aktivitas, keterangan)
            VALUES (?, ?, ?, ?, ?)
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "issss",
            $pengguna_id,
            $nama_pengguna,
            $role,
            $aktivitas,
            $detail
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: peminjaman.php");
        exit;

    } else {
        echo "Gagal mengubah peminjaman: " . mysqli_error($koneksi);
    }
}
?>