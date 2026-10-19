<?php

if (!isset($_SESSION['role'])) {
    $dashboard = "login.php";
} elseif ($_SESSION['role'] == 'admin') {
    $dashboard = "dashboard_admin.php";
} elseif ($_SESSION['role'] == 'petugas') {
    $dashboard = "dashboard_petugas.php";
} elseif ($_SESSION['role'] == 'anggota') {
    $dashboard = "dashboard_anggota.php";
} else {
    $dashboard = "login.php";
}
?>

<a href="<?= $dashboard; ?>">
    <button type="button">Dashboard</button>
</a>