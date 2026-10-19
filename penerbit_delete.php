<?php
include "koneksi.php";

$id = $_GET['id'] ?? '';

$cek = mysqli_query($koneksi, "
    SELECT * FROM buku
    WHERE penerbit_id='$id'
");

if (mysqli_num_rows($cek) > 0) {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penerbit Tidak Dapat Dihapus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="detail-buku">

    <h2>PENERBIT TIDAK DAPAT DIHAPUS</h2>

    <p>
        Penerbit ini tidak dapat dihapus karena masih digunakan
        oleh data buku.
    </p>

    <p>
        Data penerbit harus tetap disimpan agar hubungan dengan
        buku tetap terjaga.
    </p>

    <br>

    <a href="penerbit.php">
        <button>Kembali ke Data Penerbit</button>
    </a>

</div>

</body>
</html>

<?php
exit;
}

$query = mysqli_query($koneksi, "
    DELETE FROM penerbit
    WHERE penerbit_id='$id'
");

if ($query) {
    header("Location: penerbit.php");
    exit;
} else {
    echo "Penerbit gagal dihapus.";
}
?>