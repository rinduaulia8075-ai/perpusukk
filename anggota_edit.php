<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM anggota WHERE anggota_id='$id'");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data anggota tidak ditemukan");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Anggota</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>EDIT ANGGOTA</h2>

<form action="anggota_update.php" method="POST">

    <!-- ID disimpan supaya tahu data mana yang diedit -->
    <input type="hidden" name="anggota_id"
           value="<?= $data['anggota_id']; ?>">

    <label>Nama</label><br>
    <input type="text" name="nama"
           value="<?= htmlspecialchars($data['nama']); ?>"
           required>

    <br><br>

    <label>Jenis Kelamin</label><br>
    <select name="jenis_kelamin" required>

        <option value="Laki-laki"
            <?= ($data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>
            Laki-laki
        </option>

        <option value="Perempuan"
            <?= ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>
            Perempuan
        </option>

    </select>
        <br><br>

         <label>Tempat Lahir</label><br>
         <input type="text" name="tem"
           value="<?= htmlspecialchars($data['alamat']); ?>"
           required>

    <br><br>
    <br><br>

    <label>Telpon</label><br>
    <input type="text" name="telpon"
           value="<?= htmlspecialchars($data['telpon']); ?>"
           required>

    <br><br>

    <label>Alamat</label><br>
    <input type="text" name="alamat"
           value="<?= htmlspecialchars($data['alamat']); ?>"
           required>

    <br><br>

    <label>Username</label><br>
    <input type="text" name="username"
           value="<?= htmlspecialchars($data['username']); ?>"
           required>

    <br><br>
    <label>Password</label><br>
    <input type="text" name="password"
           value="<?= htmlspecialchars($data['password']); ?>"
           required>

    <br><br>

    <button type="submit" name="update">Update</button>

</form>

<br>

<a href="anggota.php">Kembali</a>

</main>
</body>
</html>