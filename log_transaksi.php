<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "koneksi.php";

$query = mysqli_query($koneksi, "
    SELECT *
    FROM log_transaksi
    ORDER BY tanggal DESC
");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php include "navbar.php"; ?>

<main class="page">

    <h2>LOG TRANSAKSI</h2>

    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>ID</th>
            <th>Pengguna</th>
            <th>Role</th>
            <th>Aktivitas</th>
            <th>Keterangan</th>
            <th>Tanggal</th>
        </tr>

        <?php while ($data = mysqli_fetch_assoc($query)) { ?>

        <tr>
            <td><?= $data['log_id']; ?></td>

            <td>
                <?= htmlspecialchars($data['nama_pengguna']); ?>
            </td>

            <td>
                <?= htmlspecialchars($data['role']); ?>
            </td>

            <td>
                <?= htmlspecialchars($data['aktivitas']); ?>
            </td>

            <td>
                <?= htmlspecialchars($data['keterangan']); ?>
            </td>

            <td>
                <?= $data['tanggal']; ?>
            </td>
        </tr>

        <?php } ?>

    </table>

</main>

</body>
</html>