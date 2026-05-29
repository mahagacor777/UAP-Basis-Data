<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMDK - Sistem Informasi Manajemen Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">SIMDK</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="departemen/index.php">Kelola Departemen</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container fade-in-down">
            <h1 class="display-4 fw-bold mb-4">Selamat Datang di SIMDK</h1>
            <p class="lead mb-5">Sistem Informasi Manajemen Data Karyawan yang terpusat, modern, dan efisien.</p>
            <a href="departemen/index.php" class="btn btn-light btn-lg px-5 rounded-pill shadow-sm">Mulai Kelola Data</a>
        </div>
    </section>

    <section class="py-5 bg-light text-center">
        <div class="container">
            <h2 class="mb-4">Fitur Utama</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="p-4 bg-white shadow-sm rounded">
                        <h4 class="fw-bold">Manajemen Terpusat</h4>
                        <p class="text-muted">Kelola data karyawan, departemen, dan jabatan dalam satu platform.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white shadow-sm rounded">
                        <h4 class="fw-bold">UI/UX Modern</h4>
                        <p class="text-muted">Desain antarmuka yang bersih dan responsif di berbagai perangkat.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white shadow-sm rounded">
                        <h4 class="fw-bold">Akses Cepat</h4>
                        <p class="text-muted">Dibangun dengan Native PHP untuk performa dan waktu muat yang ringan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2026 SIMDK - Perusahaan Anda. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
