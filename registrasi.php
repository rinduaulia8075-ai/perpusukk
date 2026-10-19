<?php
include "koneksi.php";

if (isset($_POST['daftar'])) {

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $telpon = $_POST['telpon'];
    $alamat = $_POST['alamat'];

    // Cek username
    $cek = mysqli_query($koneksi, "
        SELECT * FROM anggota
        WHERE username='$username'
    ");

    if (mysqli_num_rows($cek) > 0) {

        $error = "Username sudah digunakan.";

    } else {

        // Admin default
        $admin_id = 1;

        $query = mysqli_query($koneksi, "
            INSERT INTO anggota
            (
                nama,
                username,
                password,
                jenis_kelamin,
                tempat_lahir,
                telpon,
                alamat,
                admin_id
            )
            VALUES
            (
                '$nama',
                '$username',
                '$password',
                '$jenis_kelamin',
                '$tempat_lahir',
                '$telpon',
                '$alamat',
                '$admin_id'
            )
        ");

        if ($query) {

            header("Location: login.php?daftar=berhasil");
            exit;

        } else {

            $error = "Registrasi gagal: " . mysqli_error($koneksi);

        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Registrasi Anggota</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="login-container">

    <div class="login-box">

        <h2>REGISTRASI ANGGOTA</h2>

        <p style="text-align:center; margin-bottom:20px;">
            Buat akun untuk meminjam buku.
        </p>

        <?php if (isset($error)) { ?>

            <div class="alert alert-danger">
                <?= htmlspecialchars($error); ?>
            </div>

        <?php } ?>

        <form action="registrasi.php" method="POST">

            <label>Nama Lengkap</label>

            <input
                type="text"
                name="nama"
                required
            >

            <label>Username</label>

            <input
                type="text"
                name="username"
                required
            >

            <label>Password</label>

            <input
                type="password"
                name="password"
                required
            >

            <label>Jenis Kelamin</label>

            <select name="jenis_kelamin" required>

                <option value="">
                    -- Pilih Jenis Kelamin --
                </option>

                <option value="Laki-laki">
                    Laki-laki
                </option>

                <option value="Perempuan">
                    Perempuan
                </option>

            </select>

            <label>Tempat Lahir</label>

            <input
                type="text"
                name="tempat_lahir"
            >

            <label>No. Telepon</label>

            <input
                type="text"
                name="telpon"
            >

            <label>Alamat</label>

            <textarea name="alamat"></textarea>

            <button type="submit" name="daftar">
                📝 Daftar
            </button>

        </form>

        <br>

        <p style="text-align:center;">
            Sudah punya akun?
        </p>

        <br>

        <a href="login.php">
            <button type="button">
                Kembali ke Login
            </button>
        </a>

    </div>

</div>

</body>

</html>