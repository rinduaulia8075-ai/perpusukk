<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include "koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM petugas");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Petugas</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include "navbar.php"; ?>
<main class="page">

<h2>DATA PETUGAS</h2>

<a href="petugas_form.html">
    <button>Tambah Admin</button>
</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>ID PETUGAS</th>
        <th>Nama</th>
        <th>Username</th>
        <th>Password</th>
        <th>Aksi</th>
    </tr>

    <?php while ($data = mysqli_fetch_assoc($query)) { ?>

    <tr>

        <td><?= $data['petugas_id']; ?></td>

        <td><?= htmlspecialchars($data['nama']); ?></td>

        <td><?= htmlspecialchars($data['username']); ?></td>

        <td><?= htmlspecialchars($data['password']); ?></td>

        <td>

            <a href="petugas_edit.php?id=<?= $data['petugas_id']; ?>">
                <button>Edit</button>
            </a>

            <a href="petugas_delete.php?id=<?= $data['petugas_id']; ?>"
               onclick="return confirm('Yakin ingin menghapus admin ini?');">
                <button>Delete</button>
            </a>

        </td>

    </tr>

    <?php } ?>

</table>

</main>
</body>
</html>