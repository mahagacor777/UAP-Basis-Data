<?php
require_once 'config.php';
$activePage = 'kehadiran';
$pageTitle = 'Data Kehadiran';

// Handle Tambah
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'])) {
    if (($_SESSION['role'] ?? '') === 'karyawan') {
        header("Location: kehadiran.php?error=Anda tidak memiliki hak akses");
        exit;
    }
    if ($_POST['aksi'] === 'tambah') {
        try {
            $stmt = $pdo->prepare("INSERT INTO kehadiran (id_karyawan, tanggal, jam_masuk, jam_keluar, status, keterangan)
                VALUES (?,?,?,?,?,?)");
            $stmt->execute([
                $_POST['id_karyawan'],
                $_POST['tanggal'],
                $_POST['jam_masuk'] ?: null,
                $_POST['jam_keluar'] ?: null,
                $_POST['status'],
                $_POST['keterangan'] ?: null
            ]);
            header("Location: kehadiran.php?success=Data kehadiran berhasil ditambahkan");
            exit;
        } catch (Exception $e) {
            $errorTambah = "Gagal: " . $e->getMessage();
        }
    } elseif ($_POST['aksi'] === 'hapus') {
        $pdo->prepare("DELETE FROM kehadiran WHERE id_kehadiran=?")->execute([$_POST['id_kehadiran']]);
        header("Location: kehadiran.php?success=Data kehadiran dihapus");
        exit;
    }
}

// Filter
$filterTgl = $_GET['tgl'] ?? date('Y-m');
$filterKaryawan = $_GET['karyawan'] ?? '';
if (($_SESSION['role'] ?? '') === 'karyawan') {
    $filterKaryawan = $_SESSION['id_karyawan'];
}

$where = "WHERE DATE_FORMAT(h.tanggal,'%Y-%m') = ?";
$params = [$filterTgl];
if ($filterKaryawan) {
    $where .= " AND h.id_karyawan=?";
    $params[] = $filterKaryawan;
}

$stmt = $pdo->prepare("
    SELECT h.*, k.nama_lengkap
    FROM kehadiran h
    JOIN karyawan k ON h.id_karyawan=k.id_karyawan
    $where
    ORDER BY h.tanggal DESC, k.nama_lengkap ASC
");
$stmt->execute($params);
$kehadiranList = $stmt->fetchAll();

$karyawanList = $pdo->query("SELECT id_karyawan, nama_lengkap FROM karyawan WHERE status_aktif='aktif' ORDER BY nama_lengkap")->fetchAll();

require_once 'layout/header.php';

$statusLabel = ['hadir'=>'Hadir','izin'=>'Izin','sakit'=>'Sakit','alpa'=>'Alpa','wfh'=>'WFH','dinas_luar'=>'Dinas Luar'];
$statusBadge = ['hadir'=>'success','izin'=>'info','sakit'=>'warning','alpa'=>'danger','wfh'=>'primary','dinas_luar'=>'secondary'];
?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <strong><i class="bi bi-calendar-check-fill me-2 text-success"></i>Data Kehadiran</strong>
            <?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Kehadiran
                </button>
            <?php endif; ?>
        </div>
        <form method="GET" class="d-flex gap-2 mt-3 flex-wrap">
            <input type="month" name="tgl" class="form-control form-control-sm" value="<?= $filterTgl ?>" style="max-width:160px;">
            <?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?>
                <select name="karyawan" class="form-select form-select-sm" style="max-width:200px;">
                    <option value="">Semua Karyawan</option>
                    <?php foreach ($karyawanList as $k): ?>
                        <option value="<?= $k['id_karyawan'] ?>" <?= $filterKaryawan == $k['id_karyawan'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_lengkap']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <button class="btn btn-sm btn-secondary"><i class="bi bi-search me-1"></i>Filter</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>#</th><th>Karyawan</th><th>Tanggal</th><th>Jam Masuk</th><th>Jam Keluar</th><th>Status</th><th>Keterangan</th><?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?><th>Aksi</th><?php endif; ?></tr>
            </thead>
            <tbody>
            <?php if (empty($kehadiranList)): ?>
                <tr><td colspan="<?= ($_SESSION['role'] ?? '') === 'karyawan' ? '7' : '8' ?>" class="text-center text-muted py-4">Tidak ada data kehadiran</td></tr>
            <?php endif; ?>
            <?php foreach ($kehadiranList as $i => $h): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($h['nama_lengkap']) ?></td>
                    <td><?= tgl($h['tanggal']) ?></td>
                    <td><?= !empty($h['jam_masuk']) && $h['jam_masuk'] !== '00:00:00' ? substr($h['jam_masuk'], 0, 5) : '-' ?></td>
                    <td><?= !empty($h['jam_keluar']) && $h['jam_keluar'] !== '00:00:00' ? substr($h['jam_keluar'], 0, 5) : '-' ?></td>
                    <td>
                        <span class="badge bg-<?= $statusBadge[$h['status']] ?? 'secondary' ?>">
                            <?= $statusLabel[$h['status']] ?? $h['status'] ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($h['keterangan'] ?? '-') ?></td>
                    <?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus data ini?')">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id_kehadiran" value="<?= $h['id_kehadiran'] ?>">
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white text-muted" style="font-size:.8rem;">
        <?php
        $rekap = array_count_values(array_column($kehadiranList, 'status'));
        foreach ($statusLabel as $k => $v):
            if (isset($rekap[$k])): ?>
                <span class="badge bg-<?= $statusBadge[$k] ?> me-1"><?= $v ?>: <?= $rekap[$k] ?></span>
        <?php endif; endforeach; ?>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Data Kehadiran</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="aksi" value="tambah">
                <div class="modal-body">
                    <?php if (isset($errorTambah)): ?>
                        <div class="alert alert-danger"><?= $errorTambah ?></div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Karyawan</label>
                        <select name="id_karyawan" class="form-select" required>
                            <option value="">— Pilih —</option>
                            <?php foreach ($karyawanList as $k): ?>
                                <option value="<?= $k['id_karyawan'] ?>"><?= htmlspecialchars($k['nama_lengkap']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="form-control">
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Jam Keluar</label>
                            <input type="time" name="jam_keluar" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <?php foreach ($statusLabel as $val => $lbl): ?>
                                <option value="<?= $val ?>"><?= $lbl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
