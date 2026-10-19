<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

include "koneksi.php";

if (isset($_POST['simpan'])) {

    $nama_kategori = $_POST['nama_kategori'];

    $admin_id = 1;

    $query = mysqli_query($koneksi, "
        INSERT INTO kategori
        (nama_kategori, admin_id)
        VALUES
        ('$nama_kategori', '$admin_id')
    ");

    if ($query) {

        header("Location: kategori.php");
        exit;

    } else {

        echo "Gagal menambahkan kategori: "
             . mysqli_error($koneksi);

    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>TAMBAH KATEGORI</h2>

<form action="kategori_tambah.php" method="POST">

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <td>Nama Kategori</td>
    <td>
        <input
            type="text"
            name="nama_kategori"
            required
        >
    </td>
</tr>

</table>

<br>

<button type="submit" name="simpan">
    Simpan
</button>

<a href="kategori.php">
    <button type="button">Kembali</button>
</a>

</form>

</main>
</body>
</html>