<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'SIMDK' ?> — SIMDK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 240px;
            --brand: #1e40af;
            --brand-dark: #1e3a8a;
        }
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--brand);
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 20px 16px 16px;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }
        .sidebar-brand h5 { color: #fff; margin: 0; font-weight: 700; font-size: 1.1rem; }
        .sidebar-brand small { color: rgba(255,255,255,.6); font-size: .72rem; }
        .sidebar-nav { padding: 12px 8px; flex: 1; }
        .nav-label {
            color: rgba(255,255,255,.45);
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 12px 8px 4px;
        }
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,.8);
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 2px;
            font-size: .875rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all .15s;
        }
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: rgba(255,255,255,.15);
            color: #fff;
        }
        .sidebar-nav .nav-link i { font-size: 1rem; width: 18px; text-align: center; }

        /* Main */
        #main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar h6 { margin: 0; color: #1e293b; font-weight: 600; }
        .topbar .breadcrumb { margin: 0; font-size: .8rem; }
        .page-body { padding: 24px; }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }
        .stat-card h4 { margin: 0; font-weight: 700; color: #1e293b; }
        .stat-card p { margin: 0; color: #64748b; font-size: .8rem; }

        /* Table */
        .card { border: 1px solid #e2e8f0; border-radius: 12px; }
        .card-header { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 16px 20px; border-radius: 12px 12px 0 0 !important; }
        .table th { color: #64748b; font-size: .78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; background: #f8fafc; }
        .table td { vertical-align: middle; font-size: .875rem; color: #334155; }
        .badge { font-size: .72rem; font-weight: 500; padding: .3em .65em; }

        /* Alert */
        .alert { border-radius: 10px; font-size: .875rem; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-brand">
        <h5><i class="bi bi-building-fill me-2"></i>SIMDK</h5>
        <small>Sistem Informasi Karyawan</small>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Utama</div>
        <a href="/simdk/" class="nav-link <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <?php if (($_SESSION['role'] ?? '') === 'karyawan'): ?>
            <div class="nav-label">Profil</div>
            <a href="/simdk/karyawan_form.php?id=<?= $_SESSION['id_karyawan'] ?>" class="nav-link <?= ($activePage ?? '') === 'karyawan_profil' ? 'active' : '' ?>">
                <i class="bi bi-person-bounding-box"></i> Profil Saya
            </a>
        <?php else: ?>
            <div class="nav-label">Data Master</div>
            <?php if (($_SESSION['role'] ?? '') === 'manajer'): ?>
                <a href="/simdk/karyawan_form.php?id=<?= $_SESSION['id_karyawan'] ?>" class="nav-link <?= ($activePage ?? '') === 'karyawan_profil' ? 'active' : '' ?>">
                    <i class="bi bi-person-bounding-box"></i> Profil Saya
                </a>
                <a href="/simdk/karyawan.php" class="nav-link <?= ($activePage ?? '') === 'karyawan' ? 'active' : '' ?>">
                    <i class="bi bi-people-fill"></i> Karyawan
                </a>
                <a href="/simdk/tunjangan.php" class="nav-link <?= ($activePage ?? '') === 'tunjangan' ? 'active' : '' ?>">
                    <i class="bi bi-cash-coin"></i> Tunjangan
                </a>
            <?php else: ?>
                <a href="/simdk/karyawan.php" class="nav-link <?= ($activePage ?? '') === 'karyawan' ? 'active' : '' ?>">
                    <i class="bi bi-people-fill"></i> Karyawan
                </a>
                <a href="/simdk/departemen.php" class="nav-link <?= ($activePage ?? '') === 'departemen' ? 'active' : '' ?>">
                    <i class="bi bi-diagram-3-fill"></i> Departemen
                </a>
                <a href="/simdk/jabatan.php" class="nav-link <?= ($activePage ?? '') === 'jabatan' ? 'active' : '' ?>">
                    <i class="bi bi-briefcase-fill"></i> Jabatan
                </a>
                <a href="/simdk/tunjangan.php" class="nav-link <?= ($activePage ?? '') === 'tunjangan' ? 'active' : '' ?>">
                    <i class="bi bi-cash-coin"></i> Tunjangan
                </a>
            <?php endif; ?>
        <?php endif; ?>

        <div class="nav-label">Operasional</div>
        <a href="/simdk/kehadiran.php" class="nav-link <?= ($activePage ?? '') === 'kehadiran' ? 'active' : '' ?>">
            <i class="bi bi-calendar-check-fill"></i> Kehadiran
        </a>
        <a href="/simdk/cuti.php" class="nav-link <?= ($activePage ?? '') === 'cuti' ? 'active' : '' ?>">
            <i class="bi bi-calendar-x-fill"></i> Cuti
        </a>
        <a href="/simdk/penggajian.php" class="nav-link <?= ($activePage ?? '') === 'penggajian' ? 'active' : '' ?>">
            <i class="bi bi-cash-stack"></i> <?= (($_SESSION['role'] ?? '') === 'karyawan') ? 'Riwayat Gaji' : 'Penggajian' ?>
        </a>
        <a href="/simdk/kontrak.php" class="nav-link <?= ($activePage ?? '') === 'kontrak' ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-text-fill"></i> <?= (($_SESSION['role'] ?? '') === 'karyawan') ? 'Kontrak Saya' : 'Kontrak' ?>
        </a>
        <div class="nav-label">Sistem</div>
        <a href="/simdk/logout.php" class="nav-link" onclick="return confirm('Apakah Anda yakin ingin keluar?')" style="color: #fca5a5;">
            <i class="bi bi-box-arrow-right" style="color: #fca5a5;"></i> Logout
        </a>
    </nav>
    <div style="padding:12px 16px;border-top:1px solid rgba(255,255,255,.1);">
        <small style="color:rgba(255,255,255,.4);font-size:.7rem;">v1.0 &copy; 2026</small>
    </div>
</div>

<!-- Main -->
<div id="main">
    <div class="topbar">
        <div>
            <h6><?= $pageTitle ?? 'Dashboard' ?></h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/simdk/" class="text-decoration-none">Home</a></li>
                    <?php if (($activePage ?? '') !== 'dashboard'): ?>
                    <li class="breadcrumb-item active"><?= $pageTitle ?? '' ?></li>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-muted" style="font-size:.8rem;"><i class="bi bi-clock me-1"></i><?= date('d M Y, H:i') ?></span>
            <?php if (isset($_SESSION['nama_karyawan'])): ?>
                <span class="badge bg-light text-dark border px-2.5 py-1.5" style="font-size: 0.8rem; font-weight: 500;">
                    <i class="bi bi-person-circle me-1 text-primary"></i><?= htmlspecialchars($_SESSION['nama_karyawan']) ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="page-body">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_GET['success']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-x-circle-fill me-2"></i><?= htmlspecialchars($_GET['error']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
