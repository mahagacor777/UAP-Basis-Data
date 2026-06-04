<?php
if (php_sapi_name() !== 'cli') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $currentPage = basename($_SERVER['PHP_SELF']);
    if ($currentPage !== 'login.php' && !isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'simdk');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("<div style='font-family:sans-serif;padding:30px;background:#fff3cd;border:1px solid #ffc107;border-radius:8px;margin:20px;'>
        <h3>⚠️ Koneksi Database Gagal</h3>
        <p>Pastikan Laragon sudah berjalan dan database <strong>simdk</strong> sudah diimport.</p>
        <code>" . $e->getMessage() . "</code>
    </div>");
}

function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function tgl($date) {
    if (!$date) return '-';
    $bulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    $d = explode('-', $date);
    return $d[2] . ' ' . $bulan[(int)$d[1]] . ' ' . $d[0];
}
