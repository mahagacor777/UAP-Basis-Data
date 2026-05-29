<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Departemen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Tambah Departemen Baru</h4>
            </div>
            <div class="card-body">
                <form action="proses_tambah.php" method="POST">
                    <div class="mb-3">
                        <label>Kode Departemen</label>
                        <input type="text" name="kode_dept" class="form-control" required placeholder="Contoh: HRD">
                    </div>
                    <div class="mb-3">
                        <label>Nama Departemen</label>
                        <input type="text" name="nama_departemen" class="form-control" required placeholder="Contoh: Human Resource">
                    </div>
                    <div class="mb-3">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" required placeholder="Contoh: Jakarta">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" name="simpan" class="btn btn-success">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
