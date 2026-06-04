<?php
require_once 'config.php';
$activePage = 'karyawan';

$id_karyawan = $_GET['id_karyawan'] ?? null;
if (isset($_SESSION['role']) && $_SESSION['role'] === 'karyawan') {
    $id_karyawan = $_SESSION['id_karyawan'];
}
if (!$id_karyawan) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'karyawan') {
        header("Location: index.php?error=ID Karyawan tidak valid");
    } else {
        header("Location: karyawan.php?error=ID Karyawan tidak valid");
    }
    exit;
}

// Ambil data karyawan
$stmtKaryawan = $pdo->prepare("SELECT nama_lengkap FROM karyawan WHERE id_karyawan=?");
$stmtKaryawan->execute([$id_karyawan]);
$karyawan = $stmtKaryawan->fetch();
if (!$karyawan) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'karyawan') {
        header("Location: index.php?error=Karyawan tidak ditemukan");
    } else {
        header("Location: karyawan.php?error=Karyawan tidak ditemukan");
    }
    exit;
}
$pageTitle = "Pendidikan: " . htmlspecialchars($karyawan['nama_lengkap']);

$canEdit = true;
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'karyawan' && $id_karyawan !== $_SESSION['id_karyawan']) {
        $canEdit = false;
    }
    if ($_SESSION['role'] === 'manajer' && $id_karyawan !== $_SESSION['id_karyawan']) {
        $canEdit = false;
    }
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$canEdit) {
        header("Location: karyawan_pendidikan.php?id_karyawan=" . $id_karyawan . "&error=Akses ditolak");
        exit;
    }
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'tambah') {
        try {
            $stmt = $pdo->prepare("INSERT INTO pendidikan_karyawan (id_karyawan, jenjang, nama_institusi, jurusan, tahun_masuk, tahun_lulus, ipk)
                VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([
                $id_karyawan,
                $_POST['jenjang'],
                $_POST['nama_institusi'],
                $_POST['jurusan'] ?: null,
                $_POST['tahun_masuk'],
                $_POST['tahun_lulus'] ?: null,
                $_POST['ipk'] !== '' ? $_POST['ipk'] : null
            ]);
            header("Location: karyawan_pendidikan.php?id_karyawan=" . $id_karyawan . "&success=Riwayat pendidikan berhasil ditambahkan");
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } elseif ($aksi === 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE pendidikan_karyawan SET jenjang=?, nama_institusi=?, jurusan=?, tahun_masuk=?, tahun_lulus=?, ipk=? WHERE id_pendidikan=?");
            $stmt->execute([
                $_POST['jenjang'],
                $_POST['nama_institusi'],
                $_POST['jurusan'] ?: null,
                $_POST['tahun_masuk'],
                $_POST['tahun_lulus'] ?: null,
                $_POST['ipk'] !== '' ? $_POST['ipk'] : null,
                $_POST['id_pendidikan']
            ]);
            header("Location: karyawan_pendidikan.php?id_karyawan=" . $id_karyawan . "&success=Riwayat pendidikan berhasil diperbarui");
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } elseif ($aksi === 'hapus') {
        try {
            $stmt = $pdo->prepare("DELETE FROM pendidikan_karyawan WHERE id_pendidikan=?");
            $stmt->execute([$_POST['id_pendidikan']]);
            header("Location: karyawan_pendidikan.php?id_karyawan=" . $id_karyawan . "&success=Riwayat pendidikan berhasil dihapus");
            exit;
        } catch (Exception $e) {
            header("Location: karyawan_pendidikan.php?id_karyawan=" . $id_karyawan . "&error=Gagal menghapus data");
            exit;
        }
    }
}

// Ambil riwayat pendidikan
$stmtPendidikan = $pdo->prepare("SELECT * FROM pendidikan_karyawan WHERE id_karyawan=? ORDER BY tahun_masuk DESC");
$stmtPendidikan->execute([$id_karyawan]);
$list = $stmtPendidikan->fetchAll();

require_once 'layout/header.php';
?>

<div class="mb-3">
    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'karyawan' || ($_SESSION['role'] === 'manajer' && $id_karyawan === $_SESSION['id_karyawan']))): ?>
        <a href="/simdk/karyawan_form.php?id=<?= $_SESSION['id_karyawan'] ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Profil Saya
        </a>
    <?php else: ?>
        <a href="/simdk/karyawan.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Karyawan
        </a>
    <?php endif; ?>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-x-circle-fill me-2"></i><?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <strong><i class="bi bi-mortarboard-fill me-2 text-primary"></i>Riwayat Pendidikan</strong>
            <span class="text-muted ms-2">| <?= htmlspecialchars($karyawan['nama_lengkap']) ?></span>
        </div>
        <?php if ($canEdit): ?>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i>Tambah Riwayat
        </button>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jenjang</th>
                    <th>Nama Institusi</th>
                    <th>Jurusan</th>
                    <th>Tahun Masuk</th>
                    <th>Tahun Lulus</th>
                    <th>IPK</th>
                    <?php if ($canEdit): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($list)): ?>
                <tr>
                    <td colspan="<?= $canEdit ? '8' : '7' ?>" class="text-center text-muted py-4">Belum ada riwayat pendidikan</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($list as $i => $p): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['jenjang']) ?></span></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($p['nama_institusi']) ?></td>
                    <td><?= htmlspecialchars($p['jurusan'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($p['tahun_masuk']) ?></td>
                    <td><?= $p['tahun_lulus'] ? htmlspecialchars($p['tahun_lulus']) : '<span class="text-muted">Belum Lulus / Sedang Studi</span>' ?></td>
                    <td><?= $p['ipk'] ? htmlspecialchars($p['ipk']) : '—' ?></td>
                    <?php if ($canEdit): ?>
                    <td>
                        <div class="d-flex">
                            <button class="btn btn-sm btn-outline-warning btn-edit me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit"
                                    data-id_pendidikan="<?= $p['id_pendidikan'] ?>"
                                    data-jenjang="<?= htmlspecialchars($p['jenjang']) ?>"
                                    data-nama_institusi="<?= htmlspecialchars($p['nama_institusi']) ?>"
                                    data-jurusan="<?= htmlspecialchars($p['jurusan'] ?? '') ?>"
                                    data-tahun_masuk="<?= $p['tahun_masuk'] ?>"
                                    data-tahun_lulus="<?= $p['tahun_lulus'] ?? '' ?>"
                                    data-ipk="<?= $p['ipk'] ?? '' ?>"
                                    title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <form method="POST" onsubmit="return confirm('Hapus riwayat pendidikan ini?')" style="display:inline;">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id_pendidikan" value="<?= $p['id_pendidikan'] ?>">
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Riwayat Pendidikan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="aksi" value="tambah">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenjang Pendidikan</label>
                        <select name="jenjang" class="form-select" required>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA/SMK">SMA/SMK</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="S1" selected>S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Institusi</label>
                        <input type="text" name="nama_institusi" class="form-control" required placeholder="cth: Universitas Lampung">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control" placeholder="cth: Teknik Informatika (Kosongkan jika SD/SMP)">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" class="form-control" min="1900" max="2100" required value="<?= date('Y') - 4 ?>">
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Tahun Lulus</label>
                            <input type="number" name="tahun_lulus" class="form-control" min="1900" max="2100">
                            <small class="text-muted">Kosongkan jika belum lulus</small>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">IPK / Nilai Akhir</label>
                        <input type="number" name="ipk" class="form-control" step="0.01" min="0" max="100" placeholder="cth: 3.50 atau 85.5">
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
                <h6 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Riwayat Pendidikan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="id_pendidikan" id="edit_id_pendidikan">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenjang Pendidikan</label>
                        <select name="jenjang" id="edit_jenjang" class="form-select" required>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA/SMK">SMA/SMK</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Institusi</label>
                        <input type="text" name="nama_institusi" id="edit_nama_institusi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jurusan</label>
                        <input type="text" name="jurusan" id="edit_jurusan" class="form-control">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" id="edit_tahun_masuk" class="form-control" min="1900" max="2100" required>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Tahun Lulus</label>
                            <input type="number" name="tahun_lulus" id="edit_tahun_lulus" class="form-control" min="1900" max="2100">
                            <small class="text-muted">Kosongkan jika belum lulus</small>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">IPK / Nilai Akhir</label>
                        <input type="number" name="ipk" id="edit_ipk" class="form-control" step="0.01" min="0" max="100">
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
            document.getElementById('edit_id_pendidikan').value = this.getAttribute('data-id_pendidikan');
            document.getElementById('edit_jenjang').value = this.getAttribute('data-jenjang');
            document.getElementById('edit_nama_institusi').value = this.getAttribute('data-nama_institusi');
            document.getElementById('edit_jurusan').value = this.getAttribute('data-jurusan');
            document.getElementById('edit_tahun_masuk').value = this.getAttribute('data-tahun_masuk');
            document.getElementById('edit_tahun_lulus').value = this.getAttribute('data-tahun_lulus') || '';
            document.getElementById('edit_ipk').value = this.getAttribute('data-ipk') || '';
        });
    });
});
</script>

<?php require_once 'layout/footer.php'; ?>
