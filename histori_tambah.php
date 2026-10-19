<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

if (isset($_POST['simpan'])) {

    $peminjaman_id = $_POST['peminjaman_id'];
    $jenis_aktivitas = $_POST['jenis_aktivitas'];
    $denda = $_POST['denda'];

    $query = mysqli_query($koneksi, "
        INSERT INTO histori
        (peminjaman_id, jenis_aktivitas, denda)
        VALUES
        ('$peminjaman_id', '$jenis_aktivitas', '$denda')
    ");

    if ($query) {

        header("Location: histori.php");
        exit;

    } else {

        echo "Gagal menambahkan histori: "
             . mysqli_error($koneksi);
    }
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
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Histori</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>TAMBAH HISTORI</h2>

<form action="histori_tambah.php" method="POST">

    <label>Peminjaman</label><br>

    <select name="peminjaman_id" required>

        <option value="">
            -- Pilih Peminjaman --
        </option>

        <?php while ($peminjaman = mysqli_fetch_assoc($query_peminjaman)) { ?>

            <option value="<?= $peminjaman['peminjaman_id']; ?>">

                <?= htmlspecialchars($peminjaman['nama_anggota']); ?>
                -
                <?= htmlspecialchars($peminjaman['judul_buku']); ?>

            </option>

        <?php } ?>

    </select>

    <br><br>

    <label>Jenis Aktivitas</label><br>

    <select name="jenis_aktivitas" required>

        <option value="">
            -- Pilih Aktivitas --
        </option>

        <option value="Peminjaman">
            Peminjaman
        </option>

        <option value="Pengembalian">
            Pengembalian
        </option>

        <option value="Kerusakan">
            Kerusakan
        </option>

        <option value="Terlambat">
            Terlambat
        </option>

    </select>

    <br><br>

    <label>Denda</label><br>

    <input
        type="number"
        name="denda"
        min="0"
        value="0"
        required
    >

    <br><br>

    <button type="submit" name="simpan">
        Simpan
    </button>

</form>

<br>

<a href="histori.php">
    Kembali
</a>

</main>
</body>
</html>