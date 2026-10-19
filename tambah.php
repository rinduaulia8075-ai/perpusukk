<?php

include "koneksi.php";

if (isset($_POST['simpan'])) {

    $judul = $_POST['judul'];
    $kategori_id = $_POST['kategori_id'];
    $penulis = $_POST['penulis'];
    $ISBN = $_POST['ISBN'];
    $jumlah_halaman = $_POST['jumlah_halaman'];
    $stok = $_POST['stok'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $sinopsis = $_POST['sinopsis'];

    // sementara
    $penerbit_id = 1;
    $admin_id = 1;

    // gambar
    $nama_gambar = $_FILES['gambar']['name'];
    $tmp_gambar = $_FILES['gambar']['tmp_name'];
    $error_gambar = $_FILES['gambar']['error'];

    $folder = __DIR__ . "/uploads/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $gambar = basename($nama_gambar);

    if ($error_gambar == 0) {

        $berhasil_upload = move_uploaded_file(
            $tmp_gambar,
            $folder . $gambar
        );

        if (!$berhasil_upload) {
            die("Gambar gagal dipindahkan ke folder uploads.");
        }

    } else {

        die("Terjadi error saat upload gambar.");

    }

    $query = mysqli_query($koneksi, "

        INSERT INTO buku
        (
            judul,
            kategori_id,
            penerbit_id,
            admin_id,
            ISBN,
            penulis,
            jumlah_halaman,
            stok,
            tahun_terbit,
            sinopsis,
            gambar
        )

        VALUES
        (
            '$judul',
            '$kategori_id',
            '$penerbit_id',
            '$admin_id',
            '$ISBN',
            '$penulis',
            '$jumlah_halaman',
            '$stok',
            '$tahun_terbit',
            '$sinopsis',
            '$gambar'
        )

    ");

    if ($query) {

        header("Location: tampil_petugas.php");
        exit;

    } else {

        echo "Gagal menambahkan data: "
             . mysqli_error($koneksi);

    }
}
?>