<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "
    SELECT * FROM pengembalian
    WHERE pengembalian_id='$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data pengembalian tidak ditemukan");
}

$query_peminjaman = mysqli_query($koneksi, "
    SELECT
        peminjaman.peminjaman_id,
        anggota.nama AS nama_anggota,
        buku.judul AS judul_buku
    FROM peminjaman
    JOIN anggota
        ON peminjaman.anggota_id = anggota.anggota_id
    JOIN buku
        ON peminjaman.buku_id = buku.buku_id
");

$query_petugas = mysqli_query($koneksi, "
    SELECT * FROM petugas
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Pengembalian</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>EDIT PENGEMBALIAN</h2>

<form action="pengembalian_update.php" method="POST">

    <input
        type="hidden"
        name="pengembalian_id"
        value="<?= $data['pengembalian_id']; ?>"
    >

    <label>Peminjaman</label><br>

    <select name="peminjaman_id" required>

        <?php while ($peminjaman = mysqli_fetch_assoc($query_peminjaman)) { ?>

            <option
                value="<?= $peminjaman['peminjaman_id']; ?>"
                <?= ($peminjaman['peminjaman_id'] == $data['peminjaman_id']) ? 'selected' : ''; ?>
            >

                <?= htmlspecialchars($peminjaman['nama_anggota']); ?>
                -
                <?= htmlspecialchars($peminjaman['judul_buku']); ?>

            </option>

        <?php } ?>

    </select>

    <br><br>

    <label>Tanggal Kembali</label><br>

    <input
        type="date"
        name="tanggal_kembali"
        value="<?= $data['tanggal_kembali']; ?>"
        required
    >

    <br><br>

    <label>Petugas</label><br>

    <select name="petugas_id" required>

        <?php while ($petugas = mysqli_fetch_assoc($query_petugas)) { ?>

            <option
                value="<?= $petugas['petugas_id']; ?>"
                <?= ($petugas['petugas_id'] == $data['petugas_id']) ? 'selected' : ''; ?>
            >

                <?= htmlspecialchars($petugas['nama']); ?>

            </option>

        <?php } ?>

    </select>

    <br><br>

    <button type="submit" name="update">
        Update
    </button>

</form>

<br>

<a href="pengembalian.php">
    Kembali
</a>

</main>
</body>
</html>