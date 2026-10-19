<?php
include "koneksi.php";

$id = $_GET['id'] ?? '';

$cek = mysqli_query($koneksi, "
    SELECT * FROM pengembalian
    WHERE pengembalian_id='$id'
");

if (mysqli_num_rows($cek) == 0) {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Tidak Ditemukan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="detail-buku">

    <h2>DATA TIDAK DITEMUKAN</h2>

    <p>
        Data pengembalian yang dipilih tidak ditemukan.
    </p>

    <br>

    <a href="pengembalian.php">
        <button>Kembali ke Data Pengembalian</button>
    </a>

</div>

</body>
</html>

<?php
exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengembalian Tidak Dapat Dihapus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="detail-buku">

    <h2>PENGEMBALIAN TIDAK DAPAT DIHAPUS</h2>

    <p>
        Data pengembalian tidak dapat dihapus karena merupakan
        bagian dari riwayat transaksi perpustakaan.
    </p>

    <p>
        Data ini tetap disimpan agar histori peminjaman dan
        pengembalian tetap lengkap.
    </p>

    <br>

    <a href="pengembalian.php">
        <button>Kembali ke Data Pengembalian</button>
    </a>

</div>

</body>
</html>