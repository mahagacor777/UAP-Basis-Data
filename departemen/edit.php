<?php
require_once '../config/database.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Ambil data departemen berdasarkan ID tersebut
$query = "SELECT * FROM departemen WHERE id_departemen = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Departemen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-header bg-warning">
                <h4 class="mb-0">Edit Data Departemen</h4>
            </div>
            <div class="card-body">
                <form action="proses_edit.php" method="POST">
                    <input type="hidden" name="id_departemen" value="<?= $data['id_departemen']; ?>">
                    
                    <div class="mb-3">
                        <label>Kode Departemen</label>
                        <input type="text" name="kode_dept" class="form-control" value="<?= $data['kode_dept']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Nama Departemen</label>
                        <input type="text" name="nama_departemen" class="form-control" value="<?= $data['nama_departemen']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" value="<?= $data['lokasi']; ?>" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" name="update" class="btn btn-primary">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
