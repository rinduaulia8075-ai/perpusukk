<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $telpon = $_POST['telpon'];
    $alamat = $_POST['alamat'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ID admin yang membuat/menambahkan anggota
    $admin_id = 1;

    $query = mysqli_query($koneksi, "INSERT INTO anggota
        (nama, jenis_kelamin, telpon, alamat, username, password, admin_id)
        VALUES
        ('$nama', '$jenis_kelamin', '$telpon', '$alamat', '$username', '$password', '$admin_id')
    ");

    if ($query) {
        header("Location: anggota.php");
        exit;
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Anggota</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>TAMBAH ANGGOTA</h2>

<form action="anggota_tambah.php" method="POST">

    <label>Nama</label><br>
    <input type="text" name="nama" required>

    <br><br>

    <label>Jenis Kelamin</label><br>
    <select name="jenis_kelamin" required>
        <option value="">-- Pilih --</option>
        <option value="Laki-laki">Laki-laki</option>
        <option value="Perempuan">Perempuan</option>
    </select>

    <br><br>

    <label>Telpon</label><br>
    <input type="text" name="telpon" required>

    <br><br>

    <label>Alamat</label><br>
    <input type="text" name="alamat" required>

    <br><br>

    <label>Username</label><br>
    <input type="text" name="username" required>

    <br><br>

    <label>Password</label><br>
    <input type="password" name="password" required>

    <br><br>

    <button type="submit" name="simpan">Simpan</button>

</form>

<br>

<a href="anggota.php">Kembali</a>

</main>
</body>
</html>