<?php
require_once '../config/database.php';

// Ambil ID dari URL yang dikirim oleh tombol hapus
$id = $_GET['id'];

// Query Delete
$query = "DELETE FROM departemen WHERE id_departemen = '$id'";

if (mysqli_query($conn, $query)) {
    // Kembali ke index setelah berhasil dihapus
    header("Location: index.php");
} else {
    echo "Gagal menghapus data: " . mysqli_error($conn);
}
?>
