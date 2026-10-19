<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM petugas WHERE petugas_id='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data admin tidak ditemukan");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Petugas</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>EDIT PETUGAS</h2>

<form action="petugas_update.php" method="POST">

    <input type="hidden" name="petugas_id" value="<?= $data['petugas_id']; ?>">

    <label>Nama</label><br>
    <input type="text" name="nama"
           value="<?= htmlspecialchars($data['nama']); ?>" required>

    <br><br>

    <label>Username</label><br>
    <input type="text" name="username"
           value="<?= htmlspecialchars($data['username']); ?>" required>

    <br><br>

    <label>Password</label><br>
    <input type="text" name="password"
           value="<?= htmlspecialchars($data['password']); ?>" required>

    <br><br>

    <button type="submit" name="update">Update</button>

</form>

<br>

<a href="petugas.php">Kembali</a>

</main>
</body>
</html>