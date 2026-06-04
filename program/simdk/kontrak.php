<?php
require_once 'config.php';
$activePage = 'kontrak';
$pageTitle = 'Kontrak Kerja';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'karyawan') {
        header("Location: kontrak.php?error=Akses ditolak: Karyawan tidak diizinkan mengubah kontrak"); exit;
    }
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'tambah') {
        $pdo->prepare("INSERT INTO kontrak_kerja (id_karyawan, jenis_kontrak, nomor_kontrak, tanggal_mulai, tanggal_selesai, catatan)
            VALUES (?,?,?,?,?,?)")
            ->execute([$_POST['id_karyawan'], $_POST['jenis_kontrak'], $_POST['nomor_kontrak'],
                       $_POST['tanggal_mulai'], $_POST['tanggal_selesai'] ?: null, $_POST['catatan'] ?: null]);
        header("Location: kontrak.php?success=Kontrak berhasil ditambahkan"); exit;
    } elseif ($aksi === 'edit') {
        $pdo->prepare("UPDATE kontrak_kerja SET id_karyawan=?, jenis_kontrak=?, nomor_kontrak=?, tanggal_mulai=?, tanggal_selesai=?, status_kontrak=?, catatan=? WHERE id_kontrak=?")
            ->execute([$_POST['id_karyawan'], $_POST['jenis_kontrak'], $_POST['nomor_kontrak'],
                       $_POST['tanggal_mulai'], $_POST['tanggal_selesai'] ?: null, $_POST['status_kontrak'],
                       $_POST['catatan'] ?: null, $_POST['id_kontrak']]);
        header("Location: kontrak.php?success=Kontrak berhasil diperbarui"); exit;
    } elseif ($aksi === 'hapus') {
        $pdo->prepare("DELETE FROM kontrak_kerja WHERE id_kontrak=?")->execute([$_POST['id_kontrak']]);
        header("Location: kontrak.php?success=Kontrak dihapus"); exit;
    }
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'karyawan') {
    $stmt = $pdo->prepare("
        SELECT kk.*, k.nama_lengkap
        FROM kontrak_kerja kk
        JOIN karyawan k ON kk.id_karyawan=k.id_karyawan
        WHERE kk.id_karyawan = ?
        ORDER BY kk.dibuat_pada DESC
    ");
    $stmt->execute([$_SESSION['id_karyawan']]);
    $list = $stmt->fetchAll();
} else {
    $list = $pdo->query("
        SELECT kk.*, k.nama_lengkap
        FROM kontrak_kerja kk
        JOIN karyawan k ON kk.id_karyawan=k.id_karyawan
        ORDER BY kk.dibuat_pada DESC
    ")->fetchAll();
}

$karyawanList = $pdo->query("SELECT id_karyawan, nama_lengkap FROM karyawan ORDER BY nama_lengkap")->fetchAll();

require_once 'layout/header.php';

$statusBadge = ['aktif'=>'success','berakhir'=>'danger','diperbarui'=>'info'];
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><i class="bi bi-file-earmark-text-fill me-2 text-primary"></i>Kontrak Kerja</strong>
        <?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i>Tambah Kontrak
        </button>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Karyawan</th>
                    <th>No. Kontrak</th>
                    <th>Jenis</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Status</th>
                    <?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $i => $k): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($k['nama_lengkap']) ?></td>
                    <td><code><?= $k['nomor_kontrak'] ?></code></td>
                    <td><span class="badge bg-light text-dark border"><?= $k['jenis_kontrak'] ?></span></td>
                    <td><?= tgl($k['tanggal_mulai']) ?></td>
                    <td><?= $k['tanggal_selesai'] ? tgl($k['tanggal_selesai']) : '<span class="text-muted">—</span>' ?></td>
                    <td><span class="badge bg-<?= $statusBadge[$k['status_kontrak']] ?? 'secondary' ?>"><?= ucfirst($k['status_kontrak']) ?></span></td>
                    <?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?>
                    <td>
                        <div class="d-flex">
                            <button class="btn btn-sm btn-outline-warning btn-edit me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEdit"
                                    data-id_kontrak="<?= $k['id_kontrak'] ?>"
                                    data-id_karyawan="<?= $k['id_karyawan'] ?>"
                                    data-nomor_kontrak="<?= htmlspecialchars($k['nomor_kontrak']) ?>"
                                    data-jenis_kontrak="<?= htmlspecialchars($k['jenis_kontrak']) ?>"
                                    data-tanggal_mulai="<?= $k['tanggal_mulai'] ?>"
                                    data-tanggal_selesai="<?= $k['tanggal_selesai'] ?>"
                                    data-status_kontrak="<?= $k['status_kontrak'] ?>"
                                    data-catatan="<?= htmlspecialchars($k['catatan'] ?? '') ?>"
                                    title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <form method="POST" onsubmit="return confirm('Hapus kontrak ini?')" style="display:inline;">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id_kontrak" value="<?= $k['id_kontrak'] ?>">
                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Kontrak Kerja</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="aksi" value="tambah">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Karyawan</label>
                        <select name="id_karyawan" class="form-select" required>
                            <option value="">— Pilih —</option>
                            <?php foreach ($karyawanList as $k): ?>
                                <option value="<?= $k['id_karyawan'] ?>"><?= htmlspecialchars($k['nama_lengkap']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">No. Kontrak</label>
                            <input type="text" name="nomor_kontrak" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Jenis Kontrak</label>
                            <select name="jenis_kontrak" class="form-select" required>
                                <option value="PKWT">PKWT</option>
                                <option value="PKWTT">PKWTT</option>
                                <option value="Magang">Magang</option>
                                <option value="Outsourcing">Outsourcing</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control">
                            <small class="text-muted">Kosongkan jika PKWTT/permanen</small>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"></textarea>
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
                <h6 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Kontrak Kerja</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="id_kontrak" id="edit_id_kontrak">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Karyawan</label>
                        <select name="id_karyawan" id="edit_id_karyawan" class="form-select" required>
                            <option value="">— Pilih —</option>
                            <?php foreach ($karyawanList as $k): ?>
                                <option value="<?= $k['id_karyawan'] ?>"><?= htmlspecialchars($k['nama_lengkap']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">No. Kontrak</label>
                            <input type="text" name="nomor_kontrak" id="edit_nomor_kontrak" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Jenis Kontrak</label>
                            <select name="jenis_kontrak" id="edit_jenis_kontrak" class="form-select" required>
                                <option value="PKWT">PKWT</option>
                                <option value="PKWTT">PKWTT</option>
                                <option value="Magang">Magang</option>
                                <option value="Outsourcing">Outsourcing</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" class="form-control">
                            <small class="text-muted">Kosongkan jika PKWTT/permanen</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Kontrak</label>
                        <select name="status_kontrak" id="edit_status_kontrak" class="form-select" required>
                            <option value="aktif">Aktif</option>
                            <option value="berakhir">Berakhir</option>
                            <option value="diperbarui">Diperbarui</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Catatan</label>
                        <textarea name="catatan" id="edit_catatan" class="form-control" rows="2"></textarea>
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
            document.getElementById('edit_id_kontrak').value = this.getAttribute('data-id_kontrak');
            document.getElementById('edit_id_karyawan').value = this.getAttribute('data-id_karyawan');
            document.getElementById('edit_nomor_kontrak').value = this.getAttribute('data-nomor_kontrak');
            document.getElementById('edit_jenis_kontrak').value = this.getAttribute('data-jenis_kontrak');
            document.getElementById('edit_tanggal_mulai').value = this.getAttribute('data-tanggal_mulai');
            document.getElementById('edit_tanggal_selesai').value = this.getAttribute('data-tanggal_selesai') || '';
            document.getElementById('edit_status_kontrak').value = this.getAttribute('data-status_kontrak');
            document.getElementById('edit_catatan').value = this.getAttribute('data-catatan');
        });
    });
});
</script>

<?php require_once 'layout/footer.php'; ?>
