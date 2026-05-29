<?php
require_once '../config/database.php';

if (isset($_POST['update'])) {
    $id = $_POST['id_departemen'];
    $kode = $_POST['kode_dept'];
    $nama = $_POST['nama_departemen'];
    $lokasi = $_POST['lokasi'];

    // Query Update
    $query = "UPDATE departemen SET 
                kode_dept = '$kode', 
                nama_departemen = '$nama', 
                lokasi = '$lokasi' 
              WHERE id_departemen = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal update data: " . mysqli_error($conn);
    }
}
?>
