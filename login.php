<?php
session_start();
include "koneksi.php";
include "auth.php";

/* Sudah login? langsung ke dashboard */
$tujuan = ['admin'=>'dashboard_admin.php','petugas'=>'dashboard_petugas.php','anggota'=>'dashboard_anggota.php'];
if (!empty($_SESSION['role']) && isset($tujuan[$_SESSION['role']])) {
    header("Location: " . $tujuan[$_SESSION['role']]);
    exit;
}

$error = '';

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    /* [tabel, kolom id, role] — urutan sama seperti sebelumnya */
    $akun = [
        ['admin',   'admin_id',   'admin'],
        ['petugas', 'petugas_id', 'petugas'],
        ['anggota', 'anggota_id', 'anggota'],
    ];

    foreach ($akun as $a) {
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM {$a[0]} WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($res)) {
            if (cek_password($password, $row['password'])) {
                session_regenerate_id(true);
                $_SESSION[$a[1]] = $row[$a[1]];
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['role'] = $a[2];
                header("Location: " . $tujuan[$a[2]]);
                exit;
            }
        }
    }

    $error = "Username atau password salah.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<main class="page">
  <div class="auth-wrap">

    <h2>Masuk ke Perpustakaan</h2>
    <p class="sub">Silakan login untuk melanjutkan.</p>

    <?php if (isset($_GET['registered'])) { ?>
        <div class="alert ok">Registrasi berhasil! Silakan login dengan akun Anda.</div>
    <?php } ?>
    <?php if (isset($_GET['keluar'])) { ?>
        <div class="alert info">Anda berhasil logout.</div>
    <?php } ?>
    <?php if ($error != '') { ?>
        <div class="alert err"><?= e($error); ?></div>
    <?php } ?>

    <form action="" method="POST">
        <label>Username</label>
        <input type="text" name="username" value="<?= e($_POST['username'] ?? ''); ?>" required autofocus>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login" style="width:100%">Login</button>
    </form>

    <p class="alt">Belum punya akun? <a href="register.php"><b>Registrasi sebagai anggota</b></a></p>
  </div>
</main>
</body>
</html>
