<?php
require_once 'config.php';
$activePage = 'dashboard';
$pageTitle = 'Dashboard';

// Stats & Data based on Role
if (isset($_SESSION['role']) && $_SESSION['role'] === 'karyawan') {
    $id_karyawan = $_SESSION['id_karyawan'];
    
    // Personal Stats
    $hadirHariIni = $pdo->prepare("SELECT COUNT(*) FROM kehadiran WHERE id_karyawan=? AND status='hadir'");
    $hadirHariIni->execute([$id_karyawan]);
    $hadirCount = $hadirHariIni->fetchColumn();
    
    $cutiMenunggu = $pdo->prepare("SELECT COUNT(*) FROM cuti WHERE id_karyawan=? AND status='menunggu'");
    $cutiMenunggu->execute([$id_karyawan]);
    $cutiCount = $cutiMenunggu->fetchColumn();
    
    $gajiBlmDibayar = $pdo->prepare("SELECT COUNT(*) FROM penggajian WHERE id_karyawan=? AND status_bayar='belum_dibayar'");
    $gajiBlmDibayar->execute([$id_karyawan]);
    $gajiCount = $gajiBlmDibayar->fetchColumn();
    
    // Contract status
    $kontrakTerbaru = $pdo->prepare("SELECT status_kontrak, tanggal_selesai FROM kontrak_kerja WHERE id_karyawan=? ORDER BY tanggal_mulai DESC LIMIT 1");
    $kontrakTerbaru->execute([$id_karyawan]);
    $kontrak = $kontrakTerbaru->fetch();
    
    // Recent Attendance
    $kehadiranList = $pdo->prepare("SELECT * FROM kehadiran WHERE id_karyawan=? ORDER BY tanggal DESC LIMIT 5");
    $kehadiranList->execute([$id_karyawan]);
    $kehadiran = $kehadiranList->fetchAll();
    
    // Recent Leaves
    $cutiList = $pdo->prepare("SELECT * FROM cuti WHERE id_karyawan=? ORDER BY tanggal_mulai DESC LIMIT 5");
    $cutiList->execute([$id_karyawan]);
    $cuti = $cutiList->fetchAll();
} else {
    // Admin Stats
    $totalKaryawan   = $pdo->query("SELECT COUNT(*) FROM karyawan WHERE status_aktif='aktif'")->fetchColumn();
    $hadirHariIni    = $pdo->query("SELECT COUNT(*) FROM kehadiran WHERE tanggal=CURDATE() AND status='hadir'")->fetchColumn();
    $cutiMenunggu    = $pdo->query("SELECT COUNT(*) FROM cuti WHERE status='menunggu'")->fetchColumn();
    $gajiBlmDibayar  = $pdo->query("SELECT COUNT(*) FROM penggajian WHERE status_bayar='belum_dibayar'")->fetchColumn();
    
    // Latest Employees
    $karyawanList = $pdo->query("
        SELECT k.nama_lengkap, k.email, k.status_aktif, d.nama_departemen, j.nama_jabatan, k.tanggal_masuk
        FROM karyawan k
        JOIN departemen d ON k.id_departemen=d.id_departemen
        JOIN jabatan j ON k.id_jabatan=j.id_jabatan
        ORDER BY k.dibuat_pada DESC LIMIT 5
    ")->fetchAll();
    
    // Latest Payrolls
    $penggajianList = $pdo->query("
        SELECT p.*, k.nama_lengkap
        FROM penggajian p
        JOIN karyawan k ON p.id_karyawan=k.id_karyawan
        ORDER BY p.dibuat_pada DESC LIMIT 5
    ")->fetchAll();
}

require_once 'layout/header.php';
?>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'karyawan'): ?>
    <!-- Karyawan Dashboard -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-calendar-check-fill"></i></div>
                <div>
                    <h4><?= $hadirCount ?></h4>
                    <p>Total Hadir</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fef9c3;color:#ca8a04;"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <h4><?= $cutiCount ?></h4>
                    <p>Cuti Menunggu</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <h4><?= $gajiCount ?></h4>
                    <p>Gaji Belum Dibayar</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="bi bi-file-earmark-text-fill"></i></div>
                <div>
                    <h4 style="font-size: 1rem;"><?= $kontrak ? ucfirst($kontrak['status_kontrak']) : 'Belum Ada' ?></h4>
                    <p>Status Kontrak</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Kehadiran Terbaru Karyawan -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong><i class="bi bi-calendar-check me-2 text-primary"></i>Kehadiran Terakhir</strong>
                    <a href="/simdk/kehadiran.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($kehadiran)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada riwayat kehadiran</td></tr>
                        <?php endif; ?>
                        <?php foreach ($kehadiran as $kh): ?>
                            <tr>
                                <td><?= tgl($kh['tanggal']) ?></td>
                                <td><code><?= htmlspecialchars($kh['jam_masuk'] ?? '—') ?></code></td>
                                <td><code><?= htmlspecialchars($kh['jam_pulang'] ?? '—') ?></code></td>
                                <td>
                                    <?php
                                    $sc = ['hadir'=>'success','sakit'=>'info','izin'=>'warning','alpa'=>'danger'];
                                    $status = $kh['status'];
                                    ?>
                                    <span class="badge bg-<?= $sc[$status] ?? 'secondary' ?>"><?= ucfirst($status) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cuti Terbaru Karyawan -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong><i class="bi bi-calendar-x me-2 text-warning"></i>Pengajuan Cuti Terakhir</strong>
                    <a href="/simdk/cuti.php" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Jenis</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($cuti)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada riwayat cuti</td></tr>
                        <?php endif; ?>
                        <?php foreach ($cuti as $c): ?>
                            <tr>
                                <td><?= tgl($c['tanggal_mulai']) ?></td>
                                <td><?= tgl($c['tanggal_selesai']) ?></td>
                                <td><?= htmlspecialchars($c['jenis_cuti']) ?></td>
                                <td>
                                    <?php
                                    $sc = ['disetujui'=>'success','ditolak'=>'danger','menunggu'=>'warning'];
                                    $status = $c['status'];
                                    ?>
                                    <span class="badge bg-<?= $sc[$status] ?? 'secondary' ?>"><?= ucfirst($status) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Admin Dashboard -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="bi bi-people-fill"></i></div>
                <div>
                    <h4><?= $totalKaryawan ?></h4>
                    <p>Karyawan Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-calendar-check-fill"></i></div>
                <div>
                    <h4><?= $hadirHariIni ?></h4>
                    <p>Hadir Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fef9c3;color:#ca8a04;"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <h4><?= $cutiMenunggu ?></h4>
                    <p>Cuti Menunggu</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <h4><?= $gajiBlmDibayar ?></h4>
                    <p>Gaji Belum Dibayar</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Karyawan Terbaru -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong><i class="bi bi-people me-2 text-primary"></i>Karyawan Terbaru</strong>
                    <a href="/simdk/karyawan.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr>
                            <th>Nama</th><th>Departemen</th><th>Jabatan</th><th>Masuk</th><th>Status</th>
                        </tr></thead>
                        <tbody>
                        <?php foreach ($karyawanList as $k): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:600;"><?= htmlspecialchars($k['nama_lengkap']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($k['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($k['nama_departemen']) ?></td>
                                <td><?= htmlspecialchars($k['nama_jabatan']) ?></td>
                                <td><?= tgl($k['tanggal_masuk']) ?></td>
                                <td>
                                    <?php
                                    $sc = ['aktif'=>'success','tidak_aktif'=>'secondary','pensiun'=>'info','resign'=>'danger'];
                                    $status = $k['status_aktif'];
                                    ?>
                                    <span class="badge bg-<?= $sc[$status] ?? 'secondary' ?>"><?= ucfirst($status) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Penggajian -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong><i class="bi bi-cash me-2 text-success"></i>Penggajian Terakhir</strong>
                    <a href="/simdk/penggajian.php" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Karyawan</th><th>Gaji Bersih</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php foreach ($penggajianList as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                                <td style="font-weight:600;"><?= rupiah($p['gaji_bersih']) ?></td>
                                <td>
                                    <?php if ($p['status_bayar'] === 'sudah_dibayar'): ?>
                                        <span class="badge bg-success">Lunas</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Belum</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'layout/footer.php'; ?>
