<?php
require_once 'config.php';
$activePage = 'penggajian';
$pageTitle = 'Data Penggajian';

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_SESSION['role'] ?? '') === 'karyawan') {
        header("Location: penggajian.php?error=Anda tidak memiliki hak akses");
        exit;
    }
    $aksi = $_POST['aksi'] ?? '';
    $bulanRedirect = $_POST['bulan'] ?? '';
    if ($aksi === 'bayar') {
        $pdo->prepare("UPDATE penggajian SET status_bayar='sudah_dibayar', tanggal_bayar=NOW() WHERE id_penggajian=?")
            ->execute([$_POST['id_penggajian']]);
        header("Location: penggajian.php?bulan=" . urlencode($bulanRedirect) . "&success=Status gaji berhasil diperbarui");
        exit;
    } elseif ($aksi === 'tambah') {
        try {
            $gajiBersih = floatval($_POST['gaji_pokok']) + floatval($_POST['total_tunjangan'])
                        - floatval($_POST['potongan_absen']) - floatval($_POST['potongan_lain']);
            $stmt = $pdo->prepare("INSERT INTO penggajian
                (id_karyawan, periode_bulan, periode_tahun, gaji_pokok, total_tunjangan, potongan_absen, potongan_lain, gaji_bersih, status_bayar, catatan)
                VALUES (?,?,?,?,?,?,?,?,'belum_dibayar',?)");
            $stmt->execute([
                $_POST['id_karyawan'], $_POST['periode_bulan'], $_POST['periode_tahun'],
                $_POST['gaji_pokok'], $_POST['total_tunjangan'],
                $_POST['potongan_absen'], $_POST['potongan_lain'], $gajiBersih, $_POST['catatan'] ?: null
            ]);
            
            // Redirect ke bulan yang baru saja diinput agar langsung muncul
            $redirectBulan = $_POST['periode_tahun'] . '-' . str_pad($_POST['periode_bulan'], 2, '0', STR_PAD_LEFT);
            header("Location: penggajian.php?bulan=" . $redirectBulan . "&success=Data penggajian berhasil ditambahkan");
            exit;
        } catch (Exception $e) {
            $errorTambah = $e->getMessage();
        }
    } elseif ($aksi === 'hapus') {
        $pdo->prepare("DELETE FROM penggajian WHERE id_penggajian=?")->execute([$_POST['id_penggajian']]);
        header("Location: penggajian.php?bulan=" . urlencode($bulanRedirect) . "&success=Data penggajian dihapus");
        exit;
    }
}

$filterBulan = $_GET['bulan'] ?? '';
if (!$filterBulan) {
    // Cari periode terbaru yang ada datanya di database
    $latest = $pdo->query("SELECT periode_bulan, periode_tahun FROM penggajian ORDER BY periode_tahun DESC, periode_bulan DESC LIMIT 1")->fetch();
    if ($latest) {
        $filterBulan = $latest['periode_tahun'] . '-' . str_pad($latest['periode_bulan'], 2, '0', STR_PAD_LEFT);
    } else {
        $filterBulan = date('Y-m');
    }
}
[$thn, $bln] = explode('-', $filterBulan);

if (($_SESSION['role'] ?? '') === 'karyawan') {
    $stmt = $pdo->prepare("
        SELECT p.*, k.nama_lengkap, d.nama_departemen
        FROM penggajian p
        JOIN karyawan k ON p.id_karyawan=k.id_karyawan
        JOIN departemen d ON k.id_departemen=d.id_departemen
        WHERE p.id_karyawan=?
        ORDER BY p.periode_tahun DESC, p.periode_bulan DESC
    ");
    $stmt->execute([$_SESSION['id_karyawan']]);
} else {
    $stmt = $pdo->prepare("
        SELECT p.*, k.nama_lengkap, d.nama_departemen
        FROM penggajian p
        JOIN karyawan k ON p.id_karyawan=k.id_karyawan
        JOIN departemen d ON k.id_departemen=d.id_departemen
        WHERE p.periode_bulan=? AND p.periode_tahun=?
        ORDER BY k.nama_lengkap
    ");
    $stmt->execute([(int)$bln, (int)$thn]);
}
$penggajianList = $stmt->fetchAll();

$totalGajiBersih = array_sum(array_column($penggajianList, 'gaji_bersih'));

$karyawanList = $pdo->query("SELECT id_karyawan, nama_lengkap FROM karyawan WHERE status_aktif='aktif' ORDER BY nama_lengkap")->fetchAll();

require_once 'layout/header.php';
?>

<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-cash-stack"></i></div>
            <div>
                <h4 style="font-size:1.1rem;"><?= rupiah($totalGajiBersih) ?></h4>
                <p><?= (($_SESSION['role'] ?? '') === 'karyawan') ? 'Total Pendapatan' : 'Total Penggajian Bulan Ini' ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <?php $sudah = count(array_filter($penggajianList, fn($p)=>$p['status_bayar']==='sudah_dibayar')); ?>
                <h4><?= $sudah ?></h4>
                <p>Sudah Dibayar</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed;color:#ea580c;"><i class="bi bi-clock-fill"></i></div>
            <div>
                <h4><?= count($penggajianList) - $sudah ?></h4>
                <p>Belum Dibayar</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <strong><i class="bi bi-cash-stack me-2 text-success"></i>Data Penggajian</strong>
            <?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Data
                </button>
            <?php endif; ?>
        </div>
        <?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?>
            <form method="GET" class="d-flex gap-2 mt-3">
                <input type="month" name="bulan" class="form-control form-control-sm" value="<?= $filterBulan ?>" style="max-width:160px;">
                <button class="btn btn-sm btn-secondary"><i class="bi bi-search me-1"></i>Filter</button>
            </form>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <?php if (($_SESSION['role'] ?? '') === 'karyawan'): ?>
                        <th>Periode</th>
                    <?php else: ?>
                        <th>Karyawan</th>
                        <th>Departemen</th>
                    <?php endif; ?>
                    <th>Gaji Pokok</th>
                    <th>Tunjangan</th>
                    <th>Potongan</th>
                    <th>Gaji Bersih</th>
                    <th>Status</th>
                    <?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($penggajianList)): ?>
                <tr><td colspan="<?= ($_SESSION['role'] ?? '') === 'karyawan' ? '7' : '9' ?>" class="text-center text-muted py-4">Tidak ada data penggajian</td></tr>
            <?php endif; ?>
            <?php foreach ($penggajianList as $i => $p): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <?php if (($_SESSION['role'] ?? '') === 'karyawan'): ?>
                        <?php
                        $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                        $periode = $namaBulan[$p['periode_bulan']] . ' ' . $p['periode_tahun'];
                        ?>
                        <td style="font-weight:600;"><?= $periode ?></td>
                    <?php else: ?>
                        <td style="font-weight:600;"><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($p['nama_departemen']) ?></td>
                    <?php endif; ?>
                    <td><?= rupiah($p['gaji_pokok']) ?></td>
                    <td class="text-success">+<?= rupiah($p['total_tunjangan']) ?></td>
                    <td class="text-danger">-<?= rupiah($p['potongan_absen'] + $p['potongan_lain']) ?></td>
                    <td style="font-weight:700;"><?= rupiah($p['gaji_bersih']) ?></td>
                    <td>
                        <?php if ($p['status_bayar'] === 'sudah_dibayar'): ?>
                            <span class="badge bg-success">Lunas</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Belum Dibayar</span>
                        <?php endif; ?>
                    </td>
                    <?php if (($_SESSION['role'] ?? '') !== 'karyawan'): ?>
                        <td>
                            <?php if ($p['status_bayar'] === 'belum_dibayar'): ?>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Tandai sudah dibayar?')">
                                    <input type="hidden" name="aksi" value="bayar">
                                    <input type="hidden" name="id_penggajian" value="<?= $p['id_penggajian'] ?>">
                                    <input type="hidden" name="bulan" value="<?= $filterBulan ?>">
                                    <button class="btn btn-sm btn-outline-success" title="Tandai Lunas"><i class="bi bi-check-lg"></i></button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus data ini?')">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id_penggajian" value="<?= $p['id_penggajian'] ?>">
                                <input type="hidden" name="bulan" value="<?= $filterBulan ?>">
                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
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
                <h6 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Data Penggajian</h6>
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
                            <label class="form-label fw-semibold">Periode Bulan</label>
                            <select name="periode_bulan" class="form-select" required>
                                <?php for ($m=1;$m<=12;$m++): ?>
                                    <option value="<?= $m ?>" <?= $m==(int)$bln ? 'selected':'' ?>>
                                        <?= date('F', mktime(0,0,0,$m,1)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Tahun</label>
                            <input type="number" name="periode_tahun" class="form-control" value="<?= $thn ?>" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Gaji Pokok</label>
                            <input type="number" name="gaji_pokok" class="form-control" placeholder="0" required>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Total Tunjangan</label>
                            <input type="number" name="total_tunjangan" class="form-control" placeholder="0" value="0">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-semibold">Potongan Absen</label>
                            <input type="number" name="potongan_absen" class="form-control" placeholder="0" value="0">
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold">Potongan Lain</label>
                            <input type="number" name="potongan_lain" class="form-control" placeholder="0" value="0">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Catatan</label>
                        <input type="text" name="catatan" class="form-control" placeholder="Opsional">
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
