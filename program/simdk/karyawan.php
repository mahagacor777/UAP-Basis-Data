<?php
require_once 'config.php';
if (($_SESSION['role'] ?? '') === 'karyawan') {
    header("Location: index.php?error=Anda tidak memiliki hak akses");
    exit;
}
$activePage = 'karyawan';
$pageTitle = 'Data Karyawan';

// Handle Delete
if (isset($_POST['delete_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'manajer') {
        header("Location: karyawan.php?error=Akses ditolak: Manajer tidak diizinkan menghapus karyawan");
        exit;
    }
    try {
        $stmt = $pdo->prepare("DELETE FROM karyawan WHERE id_karyawan=?");
        $stmt->execute([$_POST['delete_id']]);
        header("Location: karyawan.php?success=Karyawan berhasil dihapus");
        exit;
    } catch (Exception $e) {
        header("Location: karyawan.php?error=Gagal menghapus karyawan");
        exit;
    }
}

// Search & Filter
$search = $_GET['q'] ?? '';
$filterDept = $_GET['dept'] ?? '';
$filterStatus = $_GET['status'] ?? '';

$where = "WHERE 1=1";
$params = [];
if ($search) {
    $where .= " AND (k.nama_lengkap LIKE ? OR k.email LIKE ? OR k.nik_ktp LIKE ?)";
    $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%";
}
if ($filterDept) {
    $where .= " AND k.id_departemen=?";
    $params[] = $filterDept;
}
if ($filterStatus) {
    $where .= " AND k.status_aktif=?";
    $params[] = $filterStatus;
}

$stmt = $pdo->prepare("
    SELECT k.*, d.nama_departemen, j.nama_jabatan
    FROM karyawan k
    JOIN departemen d ON k.id_departemen=d.id_departemen
    JOIN jabatan j ON k.id_jabatan=j.id_jabatan
    $where
    ORDER BY k.nama_lengkap ASC
");
$stmt->execute($params);
$karyawanList = $stmt->fetchAll();

$departemenList = $pdo->query("SELECT * FROM departemen ORDER BY nama_departemen")->fetchAll();

require_once 'layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <strong><i class="bi bi-people-fill me-2 text-primary"></i>Daftar Karyawan</strong>
            <?php if (($_SESSION['role'] ?? '') !== 'manajer'): ?>
            <a href="/simdk/karyawan_form.php" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Tambah Karyawan
            </a>
            <?php endif; ?>
        </div>
        <!-- Filter -->
        <form method="GET" class="d-flex gap-2 mt-3 flex-wrap">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama / email / NIK..." value="<?= htmlspecialchars($search) ?>" style="max-width:220px;">
            <select name="dept" class="form-select form-select-sm" style="max-width:180px;">
                <option value="">Semua Departemen</option>
                <?php foreach ($departemenList as $d): ?>
                    <option value="<?= $d['id_departemen'] ?>" <?= $filterDept == $d['id_departemen'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d['nama_departemen']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="status" class="form-select form-select-sm" style="max-width:150px;">
                <option value="">Semua Status</option>
                <option value="aktif" <?= $filterStatus === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="tidak_aktif" <?= $filterStatus === 'tidak_aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                <option value="pensiun" <?= $filterStatus === 'pensiun' ? 'selected' : '' ?>>Pensiun</option>
                <option value="resign" <?= $filterStatus === 'resign' ? 'selected' : '' ?>>Resign</option>
            </select>
            <button class="btn btn-sm btn-secondary"><i class="bi bi-search me-1"></i>Filter</button>
            <?php if ($search || $filterDept || $filterStatus): ?>
                <a href="/simdk/karyawan.php" class="btn btn-sm btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Departemen</th>
                    <th>Jabatan</th>
                    <th>No. HP</th>
                    <th>Masuk</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($karyawanList)): ?>
                <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data karyawan</td></tr>
            <?php endif; ?>
            <?php foreach ($karyawanList as $i => $k): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><code><?= $k['nik_ktp'] ?></code></td>
                    <td>
                        <div style="font-weight:600;"><?= htmlspecialchars($k['nama_lengkap']) ?></div>
                        <small class="text-muted"><?= htmlspecialchars($k['email']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($k['nama_departemen']) ?></td>
                    <td><?= htmlspecialchars($k['nama_jabatan']) ?></td>
                    <td><?= htmlspecialchars($k['no_telepon'] ?? '-') ?></td>
                    <td><?= tgl($k['tanggal_masuk']) ?></td>
                    <td>
                        <?php
                        $sc = ['aktif'=>'success','tidak_aktif'=>'secondary','pensiun'=>'info','resign'=>'danger'];
                        $lb = ['aktif'=>'Aktif','tidak_aktif'=>'Tidak Aktif','pensiun'=>'Pensiun','resign'=>'Resign'];
                        $s = $k['status_aktif'];
                        ?>
                        <span class="badge bg-<?= $sc[$s] ?? 'secondary' ?>"><?= $lb[$s] ?? $s ?></span>
                    </td>
                    <td>
                        <a href="/simdk/karyawan_pendidikan.php?id_karyawan=<?= $k['id_karyawan'] ?>" class="btn btn-sm btn-outline-info" title="Pendidikan">
                            <i class="bi bi-mortarboard-fill"></i>
                        </a>
                        <?php if (($_SESSION['role'] ?? '') !== 'manajer'): ?>
                        <a href="/simdk/karyawan_form.php?id=<?= $k['id_karyawan'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-danger" title="Hapus"
                            onclick="konfirmasiHapus(<?= $k['id_karyawan'] ?>, '<?= addslashes($k['nama_lengkap']) ?>')">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white text-muted" style="font-size:.8rem;">
        Total: <strong><?= count($karyawanList) ?></strong> karyawan
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus</h6>
            </div>
            <div class="modal-body">
                <p class="mb-0">Hapus karyawan <strong id="namaHapus"></strong>?</p>
                <small class="text-muted">Semua data terkait (kehadiran, cuti, dll) akan ikut terhapus.</small>
            </div>
            <div class="modal-footer border-0 pt-0">
                <form method="POST">
                    <input type="hidden" name="delete_id" id="deleteId">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function konfirmasiHapus(id, nama) {
    document.getElementById('deleteId').value = id;
    document.getElementById('namaHapus').textContent = nama;
    new bootstrap.Modal(document.getElementById('modalHapus')).show();
}
</script>

<?php require_once 'layout/footer.php'; ?>
