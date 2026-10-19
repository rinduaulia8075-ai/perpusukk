<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$query_kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>TAMBAH BUKU</h2>

<form action="tambah.php" method="POST" enctype="multipart/form-data">

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <td>Judul</td>
        <td>
            <input type="text" name="judul" required>
        </td>
    </tr>

    <tr>
        <td>Kategori</td>
        <td>
            <select name="kategori_id" required>

                <option value="">-- Pilih Kategori --</option>

                <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) { ?>

                    <option value="<?= $kategori['kategori_id']; ?>">
                        <?= htmlspecialchars($kategori['nama_kategori']); ?>
                    </option>

                <?php } ?>

            </select>
        </td>
    </tr>

    <tr>
        <td>Penulis</td>
        <td>
            <input type="text" name="penulis" required>
        </td>
    </tr>

    <tr>
        <td>ISBN</td>
        <td>
            <input type="text" name="ISBN">
        </td>
    </tr>

    <tr>
        <td>Jumlah Halaman</td>
        <td>
            <input type="number" name="jumlah_halaman" min="1">
        </td>
    </tr>

    <tr>
        <td>Stok</td>
        <td>
            <input type="number" name="stok" min="0" required>
        </td>
    </tr>

    <tr>
        <td>Tahun Terbit</td>
        <td>
            <input type="number" name="tahun_terbit" min="1900" max="2100">
        </td>
    </tr>

    <tr>
        <td>Sinopsis</td>
        <td>
            <textarea name="sinopsis" rows="5" cols="40"></textarea>
        </td>
    </tr>

    <tr>
        <td>Gambar</td>
        <td>
            <input type="file" name="gambar" accept="image/*" required>
        </td>
    </tr>

</table>

<br>

<button type="submit" name="simpan">
    Simpan Buku
</button>

<a href="tampil.php">
    <button type="button">Kembali</button>
</a>

</form>

</main>
</body>
</html>