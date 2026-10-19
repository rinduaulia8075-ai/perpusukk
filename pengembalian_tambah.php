<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

if (isset($_POST['simpan'])) {

    $peminjaman_id = $_POST['peminjaman_id'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $petugas_id = $_POST['petugas_id'];
    $kondisi_buku = $_POST['kondisi_buku'];
    $admin_id = 1;

    // Ambil data peminjaman
    $query_peminjaman = mysqli_query($koneksi, "
        SELECT *
        FROM peminjaman
        WHERE peminjaman_id='$peminjaman_id'
        AND status='Dipinjam'
    ");

    $peminjaman = mysqli_fetch_assoc($query_peminjaman);

    if (!$peminjaman) {
        die("Peminjaman tidak ditemukan atau buku sudah dikembalikan.");
    }

    // =========================
    // HITUNG KETERLAMBATAN
    // =========================

    $tanggal_jatuh_tempo = $peminjaman['tanggal_jatuh_tempo'];

    $jatuh_tempo = new DateTime($tanggal_jatuh_tempo);
    $tanggal_kembali_obj = new DateTime($tanggal_kembali);

    if ($tanggal_kembali_obj > $jatuh_tempo) {

        $selisih = $jatuh_tempo->diff($tanggal_kembali_obj);
        $hari_terlambat = $selisih->days;

    } else {

        $hari_terlambat = 0;

    }

    // Denda keterlambatan
    $denda_per_hari = 1000;
    $denda_terlambat = $hari_terlambat * $denda_per_hari;


    // =========================
    // HITUNG DENDA KERUSAKAN
    // =========================

    if ($kondisi_buku == "Baik") {

        $denda_kerusakan = 0;

    } elseif ($kondisi_buku == "Rusak Ringan") {

        $denda_kerusakan = 5000;

    } elseif ($kondisi_buku == "Rusak Berat") {

        $denda_kerusakan = 20000;

    } else {

        $denda_kerusakan = 0;

    }


    // Total denda
    $denda_total = $denda_terlambat + $denda_kerusakan;


    // =========================
    // SIMPAN PENGEMBALIAN
    // =========================

    $query_pengembalian = mysqli_query($koneksi, "
        INSERT INTO pengembalian
        (
            peminjaman_id,
            tanggal_kembali,
            petugas_id,
            admin_id
        )
        VALUES
        (
            '$peminjaman_id',
            '$tanggal_kembali',
            '$petugas_id',
            '$admin_id'
        )
    ");

    if (!$query_pengembalian) {

        die("Gagal menyimpan pengembalian: " . mysqli_error($koneksi));

    }


    // =========================
    // UPDATE STATUS PEMINJAMAN
    // =========================

    $query_status = mysqli_query($koneksi, "
        UPDATE peminjaman
        SET status='Sudah Dikembalikan'
        WHERE peminjaman_id='$peminjaman_id'
    ");

    if (!$query_status) {

        die("Gagal mengubah status peminjaman: " . mysqli_error($koneksi));

    }


    // =========================
    // TAMBAH STOK BUKU
    // =========================

    $buku_id = $peminjaman['buku_id'];

    $query_stok = mysqli_query($koneksi, "
        UPDATE buku
        SET stok = stok + 1
        WHERE buku_id='$buku_id'
    ");

    if (!$query_stok) {

        die("Gagal mengembalikan stok buku: " . mysqli_error($koneksi));

    }


    // =========================
    // HISTORI OTOMATIS
    // =========================

    // Kalau terlambat
    if ($denda_terlambat > 0) {

        mysqli_query($koneksi, "
            INSERT INTO histori
            (
                peminjaman_id,
                jenis_aktivitas,
                denda
            )
            VALUES
            (
                '$peminjaman_id',
                'Terlambat',
                '$denda_terlambat'
            )
        ");
    }


    // Kalau rusak
    if ($denda_kerusakan > 0) {

        mysqli_query($koneksi, "
            INSERT INTO histori
            (
                peminjaman_id,
                jenis_aktivitas,
                denda
            )
            VALUES
            (
                '$peminjaman_id',
                'Kerusakan',
                '$denda_kerusakan'
            )
        ");
    }


    // Kalau tidak terlambat dan tidak rusak
    if ($denda_terlambat == 0 && $denda_kerusakan == 0) {

        mysqli_query($koneksi, "
            INSERT INTO histori
            (
                peminjaman_id,
                jenis_aktivitas,
                denda
            )
            VALUES
            (
                '$peminjaman_id',
                'Pengembalian',
                '0'
            )
        ");
    }


    header("Location: pengembalian.php");
    exit;
}


// =====================================================
// AMBIL PEMINJAMAN YANG MASIH DIPINJAM
// =====================================================

$query_peminjaman = mysqli_query($koneksi, "
    SELECT
        peminjaman.peminjaman_id,
        peminjaman.tanggal_jatuh_tempo,
        anggota.nama AS nama_anggota,
        buku.judul AS judul_buku
    FROM peminjaman

    JOIN anggota
    ON peminjaman.anggota_id = anggota.anggota_id

    JOIN buku
    ON peminjaman.buku_id = buku.buku_id

    WHERE peminjaman.status='Dipinjam'
");


// =====================================================
// AMBIL PETUGAS
// =====================================================

$query_petugas = mysqli_query($koneksi, "
    SELECT *
    FROM petugas
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Tambah Pengembalian</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>TAMBAH PENGEMBALIAN</h2>

<form action="pengembalian_tambah.php" method="POST">

    <label>Peminjaman</label>
    <br>

    <select name="peminjaman_id" required>

        <option value="">
            -- Pilih Peminjaman --
        </option>

        <?php while ($peminjaman = mysqli_fetch_assoc($query_peminjaman)) { ?>

            <option value="<?= $peminjaman['peminjaman_id']; ?>">

                <?= htmlspecialchars($peminjaman['nama_anggota']); ?>

                -

                <?= htmlspecialchars($peminjaman['judul_buku']); ?>

                -

                Jatuh tempo:

                <?= $peminjaman['tanggal_jatuh_tempo']; ?>

            </option>

        <?php } ?>

    </select>

    <br><br>


    <label>Tanggal Kembali</label>

    <br>

    <input 
    type="date" 
    name="tanggal_kembali" 
    value="<?= date('Y-m-d'); ?>"
    min="<?= date('Y-m-d'); ?>"
    required
>

    <br><br>


    <label>Kondisi Buku</label>

    <br>

    <select name="kondisi_buku" required>

        <option value="">
            -- Pilih Kondisi Buku --
        </option>

        <option value="Baik">
            Baik - Tidak Ada Denda
        </option>

        <option value="Rusak Ringan">
            Rusak Ringan - Rp5.000
        </option>

        <option value="Rusak Berat">
            Rusak Berat - Rp20.000
        </option>

    </select>

    <br><br>


    <label>Petugas</label>

    <br>

    <select name="petugas_id" required>

        <option value="">
            -- Pilih Petugas --
        </option>

        <?php while ($petugas = mysqli_fetch_assoc($query_petugas)) { ?>

            <option value="<?= $petugas['petugas_id']; ?>">

                <?= htmlspecialchars($petugas['nama']); ?>

            </option>

        <?php } ?>

    </select>

    <br><br>


    <p>
        💰 Denda keterlambatan dan kerusakan dihitung otomatis oleh sistem.
    </p>


    <button type="submit" name="simpan">
        Simpan Pengembalian
    </button>

</form>

<br>

<a href="pengembalian.php">

    <button type="button">
        Kembali
    </button>

</a>

</main>
</body>

</html>