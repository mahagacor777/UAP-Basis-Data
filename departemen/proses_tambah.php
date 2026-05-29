<?php
require_once '../config/database.php';

// Cek apakah tombol simpan sudah ditekan
if (isset($_POST['simpan'])) {
    // Tangkap data dari form
    $kode = $_POST['kode_dept'];
    $nama = $_POST['nama_departemen'];
    $lokasi = $_POST['lokasi'];

    // Query insert data
    $query = "INSERT INTO departemen (kode_dept, nama_departemen, lokasi) VALUES ('$kode', '$nama', '$lokasi')";
    
    // Eksekusi query
    if (mysqli_query($conn, $query)) {
        // Jika sukses, kembali ke halaman index
        header("Location: index.php");
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($conn);
    }
}
?>
