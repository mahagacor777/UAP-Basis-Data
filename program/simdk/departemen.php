<?php
require_once 'config.php';
if (in_array($_SESSION['role'] ?? '', ['karyawan', 'manajer'])) {
    header("Location: index.php?error=Anda tidak memiliki hak akses");
    exit;
}
$activePage = 'departemen';
$pageTitle = 'Data Departemen';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'tambah') {
        $pdo->prepare("INSERT INTO departemen (kode_dept, nama_departemen, lokasi) VALUES (?,?,?)")
            ->execute([strtoupper($_POST['kode_dept']), $_POST['nama_departemen'], $_POST['lokasi'] ?: null]);
        header("Location: departemen.php?success=Departemen berhasil ditambahkan"); exit;
    } elseif ($aksi === 'hapus') {
        try {
            $pdo->prepare("DELETE FROM departemen WHERE id_departemen=?")->execute([$_POST['id_departemen']]);
            header("Location: departemen.php?success=Departemen dihapus"); exit;
        } catch (Exception $e) {
            header("Location: departemen.php?error=Gagal hapus — masih ada karyawan di departemen ini"); exit;
        }
    }
}

$stmt = $pdo->query("
    SELECT d.*, COUNT(k.id_karyawan) as jumlah_karyawan
    FROM departemen d
    LEFT JOIN karyawan k ON d.id_departemen=k.id_departemen AND k.status_aktif='aktif'
    GROUP BY d.id_departemen
    ORDER BY d.nama_departemen
");
$list = $stmt->fetchAll();

require_once 'layout/header.php';
?>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><strong><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Departemen</strong></div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="aksi" value="tambah">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Departemen</label>
                        <input type="text" name="kode_dept" class="form-control" placeholder="cth: IT, HRD" maxlength="10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Departemen</label>
                        <input type="text" name="nama_departemen" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Opsional">
                    </div>
                    <button class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><strong><i class="bi bi-diagram-3-fill me-2 text-primary"></i>Daftar Departemen</strong></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Kode</th><th>Nama Departemen</th><th>Lokasi</th><th>Karyawan Aktif</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($list as $d): ?>
                        <tr>
                            <td><code><?= $d['kode_dept'] ?></code></td>
                            <td style="font-weight:600;"><?= htmlspecialchars($d['nama_departemen']) ?></td>
                            <td><?= htmlspecialchars($d['lokasi'] ?? '-') ?></td>
                            <td><span class="badge bg-primary"><?= $d['jumlah_karyawan'] ?> orang</span></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Hapus departemen ini?')">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id_departemen" value="<?= $d['id_departemen'] ?>">
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
