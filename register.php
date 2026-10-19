<?php
session_start();
include "koneksi.php";
include "auth.php";

if (!empty($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$v = ['nama'=>'','jenis_kelamin'=>'','telpon'=>'','alamat'=>'','username'=>''];

if (isset($_POST['daftar'])) {

    foreach ($v as $k => $x) {
        $v[$k] = trim($_POST[$k] ?? '');
    }
    $password   = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if ($v['nama'] == '' || $v['telpon'] == '' || $v['alamat'] == '') {
        $error = "Semua kolom wajib diisi.";
    } elseif (!in_array($v['jenis_kelamin'], ['Laki-laki', 'Perempuan'], true)) {
        $error = "Pilih jenis kelamin.";
    } elseif (!preg_match('/^[A-Za-z0-9_.]{4,30}$/', $v['username'])) {
        $error = "Username 4-30 karakter: huruf, angka, titik, atau garis bawah.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak sama.";
    } else {

        /* username tidak boleh sama dengan akun lain (admin/petugas/anggota) */
        $dipakai = false;
        foreach (['admin', 'petugas', 'anggota'] as $tbl) {
            $s = mysqli_prepare($koneksi, "SELECT 1 FROM $tbl WHERE username = ? LIMIT 1");
            mysqli_stmt_bind_param($s, "s", $v['username']);
            mysqli_stmt_execute($s);
            if (mysqli_fetch_assoc(mysqli_stmt_get_result($s))) {
                $dipakai = true;
            }
        }

        if ($dipakai) {
            $error = "Username sudah dipakai, silakan pilih yang lain.";
        } elseif (!pastikan_kolom_password($koneksi)) {
            $error = "Kolom password perlu diperbesar. Jalankan di phpMyAdmin: ALTER TABLE anggota MODIFY password VARCHAR(255) NOT NULL;";
        } else {
            $hash     = password_hash($password, PASSWORD_DEFAULT);
            $admin_id = admin_default_id($koneksi);

            try {
                $stmt = mysqli_prepare($koneksi, "INSERT INTO anggota
                    (nama, jenis_kelamin, telpon, alamat, username, password, admin_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ssssssi",
                    $v['nama'], $v['jenis_kelamin'], $v['telpon'], $v['alamat'], $v['username'], $hash, $admin_id);
                mysqli_stmt_execute($stmt);
                header("Location: login.php?registered=1");
                exit;
            } catch (Throwable $ex) {
                $error = "Registrasi gagal: " . $ex->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Anggota - Perpustakaan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>

<main class="page">
  <div class="auth-wrap" style="max-width:620px">

    <h2>Registrasi Anggota</h2>
    <p class="sub">Buat akun untuk dapat meminjam buku.</p>

    <?php if ($error != '') { ?>
        <div class="alert err"><?= e($error); ?></div>
    <?php } ?>

    <form action="" method="POST">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= e($v['nama']); ?>" required>

        <div class="row2">
            <div>
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" <?= $v['jenis_kelamin']=='Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?= $v['jenis_kelamin']=='Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div>
                <label>No. Telepon</label>
                <input type="text" name="telpon" value="<?= e($v['telpon']); ?>" required>
            </div>
        </div>

        <label>Alamat</label>
        <input type="text" name="alamat" value="<?= e($v['alamat']); ?>" required>

        <label>Username</label>
        <input type="text" name="username" value="<?= e($v['username']); ?>" required>

        <div class="row2">
            <div>
                <label>Password</label>
                <input type="password" name="password" minlength="6" required>
            </div>
            <div>
                <label>Ulangi Password</label>
                <input type="password" name="konfirmasi" minlength="6" required>
            </div>
        </div>

        <button type="submit" name="daftar" style="width:100%">Daftar Sekarang</button>
    </form>

    <p class="alt">Sudah punya akun? <a href="login.php"><b>Login di sini</b></a></p>
  </div>
</main>
</body>
</html>
