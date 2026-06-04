<?php
require_once 'config.php';
$activePage = 'cuti';
$pageTitle = 'Data Cuti';

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'approve' || $aksi === 'reject') {
        if (($_SESSION['role'] ?? '') === 'karyawan') {
            header("Location: cuti.php?error=Anda tidak memiliki hak akses");
            exit;
        }
        $status = $aksi === 'approve' ? 'disetujui' : 'ditolak';
        $pdo->prepare("UPDATE cuti SET status=?, tanggal_approval=NOW() WHERE id_cuti=?")
            ->execute([$status, $_POST['id_cuti']]);
        header("Location: cuti.php?success=Status cuti berhasil diperbarui");
        exit;
    } elseif ($aksi === 'tambah') {
        try {
            $mulai = $_POST['tanggal_mulai'];
            $selesai = $_POST['tanggal_selesai'];
            $jumlah = (int)((strtotime($selesai) - strtotime($mulai)) / 86400) + 1;
            $id_karyawan = (($_SESSION['role'] ?? '') === 'karyawan') ? $_SESSION['id_karyawan'] : $_POST['id_karyawan'];
            $stmt = $pdo->prepare("INSERT INTO cuti (id_karyawan, jenis_cuti, tanggal_mulai, tanggal_selesai, jumlah_hari, alasan, status)
                VALUES (?,?,?,?,?,?,'menunggu')");
            $stmt->execute([$id_karyawan, $_POST['jenis_cuti'], $mulai, $selesai, $jumlah, $_POST['alasan']]);
            header("Location: cuti.php?success=Pengajuan cuti berhasil ditambahkan");
            exit;
        } catch (Exception $e) {
            $errorTambah = $e->getMessage();
        }
    } elseif ($aksi === 'hapus') {
        if (($_SESSION['role'] ?? '') === 'karyawan') {
            $pdo->prepare("DELETE FROM cuti WHERE id_cuti=? AND id_karyawan=?")->execute([$_POST['id_cuti'], $_SESSION['id_karyawan']]);
        } else {
            $pdo->prepare("DELETE FROM cuti WHERE id_cuti=?")->execute([$_POST['id_cuti']]);
        }
        header("Location: cuti.php?success=Data cuti dihapus");
        exit;
    }
}

// Filter
$filterStatus = $_GET['status'] ?? '';
if (($_SESSION['role'] ?? '') === 'karyawan') {
    $where = $filterStatus ? "WHERE c.status=? AND c.id_karyawan=?" : "WHERE c.id_karyawan=?";
    $params = $filterStatus ? [$filterStatus, $_SESSION['id_karyawan']] : [$_SESSION['id_karyawan']];
} else {
    $where = $filterStatus ? "WHERE c.status=?" : "WHERE 1=1";
    $params = $filterStatus ? [$filterStatus] : [];
}

$stmt = $pdo->prepare("
    SELECT c.*, k.nama_lengkap
    FROM cuti c
    JOIN karyawan k ON c.id_karyawan=k.id_karyawan
    $where
    ORDER BY c.dibuat_pada DESC
");
$stmt->execute($params);
$cutiList = $stmt->fetchAll();

$karyawanList = $pdo->query("SELECT id_karyawan, nama_lengkap FROM karyawan WHERE status_aktif='aktif' ORDER BY nama_lengkap")->fetchAll();

require_once 'layout/header.php';

$jenisCuti = ['tahunan'=>'Tahunan','sakit'=>'Sakit','melahirkan'=>'Melahirkan','penting'=>'Penting','khusus'=>'Khusus'];
$statusBadge = ['menunggu'=>'warning','disetujui'=>'success','ditolak'=>'danger','dibatalkan'=>'secondary'];
$statusLabel = ['menunggu'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak','dibatalkan'=>'Dibatalkan'];
?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <strong><i class="bi bi-calendar-x-fill me-2 text-warning"></i>Data Cuti</strong>
            <button class="btn btn-warning btn-sm text-dark" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg me-1"></i>Ajukan Cuti
            </button>
        </div>
        <form method="GET" class="d-flex gap-2 mt-3">
            <select name="status" class="form-select form-select-sm" style="max-width:160px;">
                <option value="">Semua Status</option>
                <?php foreach ($statusLabel as $v => $l): ?>
                    <option value="<?= $v ?>" <?= $filterStatus===$v ? 'selected' : '' ?>><?= $l ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-sm btn-secondary"><i class="bi bi-search me-1"></i>Filter</button>
            <?php if ($filterStatus): ?><a href="/simdk/cuti.php" class="btn btn-sm btn-outline-secondary">Reset</a><?php endif; ?>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>#</th><th>Karyawan</th><th>Jenis Cuti</th><th>Mulai</th><th>Selesai</th><th>Hari</th><th>Alasan</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php if (empty($cutiList)): ?>
                <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data cuti</td></tr>
            <?php endif; ?>
            <?php foreach ($cutiList as $i => $c): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($c['nama_lengkap']) ?></td>
                    <td><span class="badge bg-light text-dark border"><?= $jenisCuti[$c['jenis_cuti']] ?? $c['jenis_cuti'] ?></span></td>
                    <td><?= tgl($c['tanggal_mulai']) ?></td>
                    <td><?= tgl($c['tanggal_selesai']) ?></td>
                    <td><strong><?= $c['jumlah_hari'] ?></strong> hari</td>
                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($c['alasan'] ?? '-') ?></td>
                    <td>
                        <span class="badge bg-<?= $statusBadge[$c['status']] ?> <?= $c['status']==='menunggu' ? 'text-dark' : '' ?>">
                            <?= $statusLabel[$c['status']] ?>
                        </span>
                    </td>
                    <td>
                        <?php if (($_SESSION['role'] ?? '') !== 'karyawan' && $c['status'] === 'menunggu'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id_cuti" value="<?= $c['id_cuti'] ?>">
                                <button name="aksi" value="approve" class="btn btn-sm btn-outline-success" title="Setujui"><i class="bi bi-check-lg"></i></button>
                                <button name="aksi" value="reject" class="btn btn-sm btn-outline-danger" title="Tolak"><i class="bi bi-x-lg"></i></button>
                            </form>
                        <?php endif; ?>
                        <?php if (($_SESSION['role'] ?? '') !== 'karyawan' || $c['status'] === 'menunggu'): ?>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus?')">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id_cuti" value="<?= $c['id_cuti'] ?>">
                                <button class="btn btn-sm btn-outline-secondary" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-calendar-plus me-2"></i>Ajukan Cuti</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="aksi" value="tambah">
                <div class="modal-body">
                    <?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Karyawan</label>
                            <select name="id_karyawan" class="form-select" required>
                                <option value="">— Pilih —</option>
                                <?php foreach ($karyawanList as $k): ?>
                                    <option value="<?= $k['id_karyawan'] ?>"><?= htmlspecialchars($k['nama_lengkap']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="id_karyawan" value="<?= $_SESSION['id_karyawan'] ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Cuti</label>
                        <select name="jenis_cuti" class="form-select" required>
                            <?php foreach ($jenisCuti as $v => $l): ?>
                                <option value="<?= $v ?>"><?= $l ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Alasan</label>
                        <textarea name="alasan" class="form-control" rows="3" placeholder="Tuliskan alasan cuti..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark">Ajukan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
