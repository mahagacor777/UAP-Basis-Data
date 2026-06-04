<?php
require_once 'config.php';
if (in_array($_SESSION['role'] ?? '', ['karyawan', 'manajer'])) {
    header("Location: index.php?error=Anda tidak memiliki hak akses");
    exit;
}
$activePage = 'jabatan';
$pageTitle = 'Data Jabatan';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'tambah') {
        $pdo->prepare("INSERT INTO jabatan (kode_jabatan, nama_jabatan, level_jabatan, gaji_pokok_min, gaji_pokok_max) VALUES (?,?,?,?,?)")
            ->execute([strtoupper($_POST['kode_jabatan']), $_POST['nama_jabatan'], $_POST['level_jabatan'],
                       $_POST['gaji_min'], $_POST['gaji_max']]);
        header("Location: jabatan.php?success=Jabatan berhasil ditambahkan"); exit;
    } elseif ($aksi === 'hapus') {
        try {
            $pdo->prepare("DELETE FROM jabatan WHERE id_jabatan=?")->execute([$_POST['id_jabatan']]);
            header("Location: jabatan.php?success=Jabatan dihapus"); exit;
        } catch (Exception $e) {
            header("Location: jabatan.php?error=Gagal hapus — masih ada karyawan dengan jabatan ini"); exit;
        }
    }
}

$list = $pdo->query("
    SELECT j.*, COUNT(k.id_karyawan) as jumlah_karyawan
    FROM jabatan j
    LEFT JOIN karyawan k ON j.id_jabatan=k.id_jabatan AND k.status_aktif='aktif'
    GROUP BY j.id_jabatan
    ORDER BY j.level_jabatan DESC, j.nama_jabatan
")->fetchAll();

require_once 'layout/header.php';
?>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><strong><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Jabatan</strong></div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="aksi" value="tambah">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Jabatan</label>
                        <input type="text" name="kode_jabatan" class="form-control" maxlength="10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Jabatan</label>
                        <input type="text" name="nama_jabatan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Level</label>
                        <select name="level_jabatan" class="form-select">
                            <option value="1">Level 1 (Staff)</option>
                            <option value="2">Level 2 (Supervisor)</option>
                            <option value="3">Level 3 (Manager)</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Gaji Min</label>
                            <input type="number" name="gaji_min" class="form-control" placeholder="0" required>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Gaji Max</label>
                            <input type="number" name="gaji_max" class="form-control" placeholder="0" required>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><strong><i class="bi bi-briefcase-fill me-2 text-primary"></i>Daftar Jabatan</strong></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Kode</th><th>Nama Jabatan</th><th>Level</th><th>Range Gaji</th><th>Karyawan</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($list as $j): ?>
                        <tr>
                            <td><code><?= $j['kode_jabatan'] ?></code></td>
                            <td style="font-weight:600;"><?= htmlspecialchars($j['nama_jabatan']) ?></td>
                            <td><span class="badge bg-secondary">Level <?= $j['level_jabatan'] ?></span></td>
                            <td style="font-size:.8rem;"><?= rupiah($j['gaji_pokok_min']) ?> – <?= rupiah($j['gaji_pokok_max']) ?></td>
                            <td><span class="badge bg-primary"><?= $j['jumlah_karyawan'] ?> orang</span></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Hapus jabatan ini?')">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id_jabatan" value="<?= $j['id_jabatan'] ?>">
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
