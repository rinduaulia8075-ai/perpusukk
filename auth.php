<?php
/* =========================================================
   FILE BANTU: sesi, hak akses, dan fungsi umum
   ========================================================= */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($teks)
{
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

function tanggal_id($tgl)
{
    if (empty($tgl) || $tgl == '0000-00-00') {
        return '-';
    }
    $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $t = strtotime($tgl);
    return date('j', $t) . ' ' . $bulan[(int)date('n', $t)] . ' ' . date('Y', $t);
}

function kelas_status($status)
{
    $s = strtolower(trim((string)$status));
    if ($s == 'menunggu') return 'menunggu';
    if ($s == 'dipinjam') return 'dipinjam';
    if ($s == 'ditolak')  return 'ditolak';
    return 'kembali';
}

function wajib_login()
{
    if (empty($_SESSION['role'])) {
        header("Location: login.php");
        exit;
    }
}

function akses_ditolak($pesan = 'Anda tidak memiliki akses ke halaman ini.', $judul = 'Akses Ditolak')
{
    http_response_code(403);
    $role = $_SESSION['role'] ?? '';
    $dash = ['admin'=>'dashboard_admin.php','petugas'=>'dashboard_petugas.php','anggota'=>'dashboard_anggota.php'][$role] ?? 'login.php';
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">'
       . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
       . '<title>' . e($judul) . '</title><link rel="stylesheet" href="style.css"></head><body>';
    include __DIR__ . '/navbar.php';
    echo '<main class="page"><div class="auth-wrap"><div class="card" style="text-align:center">'
       . '<h2>🔒 ' . e($judul) . '</h2><p>' . e($pesan) . '</p>'
       . '<a class="btn" href="' . $dash . '">Kembali</a></div></div></main></body></html>';
    exit;
}

function wajib_role(array $roles, $pesan = 'Anda tidak memiliki akses ke halaman ini.')
{
    wajib_login();
    if (!in_array($_SESSION['role'], $roles, true)) {
        akses_ditolak($pesan);
    }
}

/* Cocokkan password: mendukung password lama (teks biasa) dan hash baru */
function cek_password($input, $tersimpan)
{
    $tersimpan = (string)$tersimpan;
    if (preg_match('/^\$2[ayb]\$|^\$argon2/', $tersimpan)) {
        return password_verify($input, $tersimpan);
    }
    return hash_equals($tersimpan, (string)$input);
}

/* ID admin pertama, dipakai untuk kolom admin_id */
function admin_default_id($koneksi)
{
    $r = mysqli_query($koneksi, "SELECT admin_id FROM admin ORDER BY admin_id ASC LIMIT 1");
    $d = $r ? mysqli_fetch_assoc($r) : null;
    return $d ? (int)$d['admin_id'] : 1;
}

/* Pastikan kolom password anggota cukup panjang untuk hash */
function pastikan_kolom_password($koneksi)
{
    $r = mysqli_query($koneksi, "SELECT CHARACTER_MAXIMUM_LENGTH AS n FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'anggota' AND COLUMN_NAME = 'password'");
    $d = $r ? mysqli_fetch_assoc($r) : null;
    if ($d && $d['n'] !== null && (int)$d['n'] < 100) {
        try {
            mysqli_query($koneksi, "ALTER TABLE anggota MODIFY password VARCHAR(255) NOT NULL");
        } catch (Throwable $ex) {
            return false;
        }
    }
    return true;
}

/* Hitung jumlah baris: hitung($koneksi, "SELECT COUNT(*) FROM buku") */
function hitung($koneksi, $sql)
{
    try {
        $r = mysqli_query($koneksi, $sql);
        $d = $r ? mysqli_fetch_row($r) : null;
        return $d ? (int)$d[0] : 0;
    } catch (Throwable $ex) {
        return 0;
    }
}
