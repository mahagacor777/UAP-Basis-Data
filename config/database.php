<?php
// Pengaturan koneksi database Laragon
$host = "localhost"; // Server localhost Laragon
$user = "root";      // User default MySQL Laragon
$pass = "";          // Password default (kosong)
$db   = "db_simdk";  // Nama database yang  buat

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Mengecek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
