<?php
require_once 'config.php';
if (($_SESSION['role'] ?? '') === 'karyawan') {
    header("Location: index.php?error=Anda tidak memiliki hak akses");
    exit;
}
$activePage = 'tunjangan';
$pageTitle = 'Data Tunjangan Jabatan';

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'tambah') {
        try {
            $stmt = $pdo->prepare("INSERT INTO tunjangan (id_jabatan, nama_tunjangan, jenis, nominal, keterangan) VALUES (?,?,?,?,?)");
            $stmt->execute([
                $_POST['id_jabatan'],
                $_POST['nama_tunjangan'],
                $_POST['jenis'],
                $_POST['nominal'],
                $_POST['keterangan'] ?: null
            ]);
            header("Location: tunjangan.php?success=Tunjangan berhasil ditambahkan");
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } elseif ($aksi === 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE tunjangan SET id_jabatan=?, nama_tunjangan=?, jenis=?, nominal=?, keterangan=? WHERE id_tunjangan=?");
            $stmt->execute([
                $_POST['id_jabatan'],
                $_POST['nama_tunjangan'],
                $_POST['jenis'],
                $_POST['nominal'],
                $_POST['keterangan'] ?: null,
                $_POST['id_tunjangan']
            ]);
            header("Location: tunjangan.php?success=Tunjangan berhasil diperbarui");
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } elseif ($aksi === 'hapus') {
        try {
            $stmt = $pdo->prepare("DELETE FROM tunjangan WHERE id_tunjangan=?");
            $stmt->execute([$_POST['id_tunjangan']]);
            header("Location: tunjangan.php?success=Tunjangan berhasil dihapus");
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Ambil list tunjangan dengan join jabatan
$list = $pdo->query("
    SELECT t.*, j.nama_jabatan 
    FROM tunjangan t
    JOIN jabatan j ON t.id_jabatan = j.id_jabatan
    ORDER BY j.nama_jabatan ASC, t.nama_tunjangan ASC
")->fetchAll();

// Ambil list jabatan untuk select box
$jabatanList = $pdo->query("SELECT id_jabatan, nama_jabatan FROM jabatan ORDER BY nama_jabatan")->fetchAll();

require_once 'layout/header.php';
?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-x-circle-fill me-2"></i><?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><i class="bi bi-cash-coin me-2 text-primary"></i>Daftar Tunjangan Jabatan</strong>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i>Tambah Tunjangan
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jabatan</th>
                    <th>Nama Tunjangan</th>
                    <th>Jenis</th>
                    <th>Nominal</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($list)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Belum ada data tunjangan</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($list as $i => $t): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($t['nama_jabatan']) ?></td>
                    <td><?= htmlspecialchars($t['nama_tunjangan']) ?></td>
                    <td>
                        <span class="badge bg-<?= $t['jenis'] === 'tetap' ? 'primary' : 'info text-dark' ?>">
                            <?= $t['jenis'] === 'tetap' ? 'Tetap' : 'Tidak Tetap' ?>
                        </span>
                    </td>
                    <td style="font-weight:600;" class="text-success"><?= rupiah($t['nominal']) ?></td>
                    <td><?= htmlspecialchars($t['keterangan'] ?? '—') ?></td>
                    <td>
                        <div class="d-flex">
                            <button class="btn btn-sm btn-outline-warning btn-edit me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit"
                                    data-id_tunjangan="<?= $t['id_tunjangan'] ?>"
                                    data-id_jabatan="<?= $t['id_jabatan'] ?>"
                                    data-nama_tunjangan="<?= htmlspecialchars($t['nama_tunjangan']) ?>"
                                    data-jenis="<?= htmlspecialchars($t['jenis']) ?>"
                                    data-nominal="<?= number_format($t['nominal'], 0, '', '') ?>"
                                    data-keterangan="<?= htmlspecialchars($t['keterangan'] ?? '') ?>"
                                    title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <form method="POST" onsubmit="return confirm('Hapus data tunjangan ini?')" style="display:inline;">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id_tunjangan" value="<?= $t['id_tunjangan'] ?>">
                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
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
                <h6 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Tunjangan Jabatan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="aksi" value="tambah">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jabatan</label>
                        <select name="id_jabatan" class="form-select" required>
                            <option value="">— Pilih Jabatan —</option>
                            <?php foreach ($jabatanList as $j): ?>
                                <option value="<?= $j['id_jabatan'] ?>"><?= htmlspecialchars($j['nama_jabatan']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Tunjangan</label>
                        <input type="text" name="nama_tunjangan" class="form-control" placeholder="cth: Tunjangan Jabatan, Tunjangan Makan" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Jenis Tunjangan</label>
                            <select name="jenis" class="form-select" required>
                                <option value="tetap" selected>Tetap</option>
                                <option value="tidak_tetap">Tidak Tetap</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Nominal (Rupiah)</label>
                            <input type="number" name="nominal" class="form-control" placeholder="0" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Tunjangan Jabatan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="id_tunjangan" id="edit_id_tunjangan">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jabatan</label>
                        <select name="id_jabatan" id="edit_id_jabatan" class="form-select" required>
                            <option value="">— Pilih Jabatan —</option>
                            <?php foreach ($jabatanList as $j): ?>
                                <option value="<?= $j['id_jabatan'] ?>"><?= htmlspecialchars($j['nama_jabatan']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Tunjangan</label>
                        <input type="text" name="nama_tunjangan" id="edit_nama_tunjangan" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Jenis Tunjangan</label>
                            <select name="jenis" id="edit_jenis" class="form-select" required>
                                <option value="tetap">Tetap</option>
                                <option value="tidak_tetap">Tidak Tetap</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Nominal (Rupiah)</label>
                            <input type="number" name="nominal" id="edit_nominal" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_id_tunjangan').value = this.getAttribute('data-id_tunjangan');
            document.getElementById('edit_id_jabatan').value = this.getAttribute('data-id_jabatan');
            document.getElementById('edit_nama_tunjangan').value = this.getAttribute('data-nama_tunjangan');
            document.getElementById('edit_jenis').value = this.getAttribute('data-jenis');
            document.getElementById('edit_nominal').value = this.getAttribute('data-nominal');
            document.getElementById('edit_keterangan').value = this.getAttribute('data-keterangan');
        });
    });
});
</script>

<?php require_once 'layout/footer.php'; ?>
