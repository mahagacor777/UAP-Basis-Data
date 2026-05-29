<?php
// Hubungkan dengan file koneksi (naik satu folder ke config)
require_once '../config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Departemen - SIMDK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Departemen</h2>
            <div>
                <a href="../index.php" class="btn btn-secondary">Kembali ke Beranda</a>
                <a href="tambah.php" class="btn btn-primary">Tambah Data Baru</a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode Dept</th>
                            <th>Nama Departemen</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query ambil data dari tabel departemen
                        $query = "SELECT * FROM departemen ORDER BY id_departemen DESC";
                        $result = mysqli_query($conn, $query);
                        $no = 1;

                        // Perulangan untuk menampilkan data baris per baris
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['kode_dept']; ?></td>
                            <td><?= $row['nama_departemen']; ?></td>
                            <td><?= $row['lokasi']; ?></td>
                            <td>
                                <a href="edit.php?id=<?= $row['id_departemen']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapus.php?id=<?= $row['id_departemen']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
