<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM penerbit
    WHERE penerbit_id='$id'");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data penerbit tidak ditemukan");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Penerbit</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>EDIT PENERBIT</h2>

<form action="penerbit_update.php" method="POST">

    <input type="hidden"
           name="penerbit_id"
           value="<?= $data['penerbit_id']; ?>">

    <label>Nama Penerbit</label><br>

    <input type="text"
           name="nama"
           value="<?= htmlspecialchars($data['nama']); ?>"
           required>

    <br><br>

    <button type="submit" name="update">Update</button>

</form>

<br>

<a href="penerbit.php">Kembali</a>

</main>
</body>
</html>