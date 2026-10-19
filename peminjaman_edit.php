<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once "auth.php";
wajib_role(['admin', 'petugas']);
include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "
    SELECT * FROM peminjaman
    WHERE peminjaman_id='$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data peminjaman tidak ditemukan");
}

$query_anggota = mysqli_query($koneksi, "SELECT * FROM anggota");
$query_buku = mysqli_query($koneksi, "SELECT * FROM buku");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Peminjaman</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>EDIT PEMINJAMAN</h2>

<form action="peminjaman_update.php" method="POST">

    <input type="hidden"
           name="peminjaman_id"
           value="<?= $data['peminjaman_id']; ?>">

    <label>Anggota</label><br>
    <select name="anggota_id" required>

        <?php while ($anggota = mysqli_fetch_assoc($query_anggota)) { ?>

            <option value="<?= $anggota['anggota_id']; ?>"
                <?= ($anggota['anggota_id'] == $data['anggota_id']) ? 'selected' : ''; ?>>

                <?= htmlspecialchars($anggota['nama']); ?>

            </option>

        <?php } ?>

    </select>

    <br><br>

    <label>Buku</label><br>
    <select name="buku_id" required>

        <?php while ($buku = mysqli_fetch_assoc($query_buku)) { ?>
            <option value="<?= $buku['buku_id']; ?>"
                <?= ($buku['buku_id'] == $data['buku_id']) ? 'selected' : ''; ?>>

                <?= htmlspecialchars($buku['judul']); ?>

            </option>

        <?php } ?>

    </select>

    <br><br>

    <label>Tanggal Pinjam</label><br>
    <input type="date"
           name="tanggal_pinjam"
           value="<?= $data['tanggal_pinjam']; ?>"
           required>

    <br><br>

    <label>Lama Pinjam</label><br>
    <input type="number"
           name="lama_pinjam"
           value="<?= $data['lama_pinjam']; ?>"
           min="1"
           required>

    <br><br>

    <label>Tanggal Jatuh Tempo</label><br>
    <input type="date"
           name="tanggal_jatuh_tempo"
           value="<?= $data['tanggal_jatuh_tempo']; ?>">

    <br><br>

    <label>Keterangan</label><br>
    <textarea name="keterangan"><?= htmlspecialchars($data['keterangan']); ?></textarea>

    <br><br>

    <label>Status</label><br>
    <select name="status" required>

        <option value="Menunggu"
            <?= (strtolower($data['status']) == 'menunggu') ? 'selected' : ''; ?>>
            Menunggu
        </option>
        <option value="Dipinjam"
            <?= ($data['status'] == 'Dipinjam') ? 'selected' : ''; ?>>
            Dipinjam
        </option>

        <option value="Sudah Dikembalikan"
            <?= ($data['status'] == 'Sudah Dikembalikan') ? 'selected' : ''; ?>>
            Sudah Dikembalikan
        </option>

        <option value="Ditolak"
            <?= (strtolower($data['status']) == 'ditolak') ? 'selected' : ''; ?>>
            DITOLAK
        </option>

    </select>

    <br><br>

    <button type="submit" name="update">Update</button>

</form>

<br>

<a href="peminjaman.php">Kembali</a>

</main>
</body>
</html>