<?php
require_once 'config.php';
$activePage = 'karyawan';

$id = $_GET['id'] ?? null;
if (in_array($_SESSION['role'] ?? '', ['karyawan', 'manajer'])) {
    $id = $_SESSION['id_karyawan'];
    $activePage = 'karyawan_profil';
}
$isEdit = !is_null($id);
$pageTitle = $isEdit ? 'Edit Karyawan' : 'Tambah Karyawan';

$departemenList = $pdo->query("SELECT * FROM departemen ORDER BY nama_departemen")->fetchAll();
$jabatanList    = $pdo->query("SELECT * FROM jabatan ORDER BY nama_jabatan")->fetchAll();

// Load existing data if editing
$data = [
    'nik_ktp' => '', 'nama_lengkap' => '', 'jenis_kelamin' => 'L',
    'tempat_lahir' => '', 'tanggal_lahir' => '', 'alamat' => '',
    'no_telepon' => '', 'email' => '', 'tanggal_masuk' => date('Y-m-d'),
    'status_aktif' => 'aktif', 'id_departemen' => '', 'id_jabatan' => ''
];

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM karyawan WHERE id_karyawan=?");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if (!$existing) {
        header("Location: karyawan.php?error=Data tidak ditemukan");
        exit;
    }
    $data = array_merge($data, $existing);
}

$errors = [];

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aksi_akun'])) {
        if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
            header("Location: index.php?error=Akses ditolak");
            exit;
        }
        
        $aksiAkun = $_POST['aksi_akun'];
        $username = trim($_POST['username'] ?? '');
        $role = $_POST['role_pengguna'] ?? 'karyawan';
        $status_aktif = isset($_POST['status_aktif_pengguna']) ? 1 : 0;
        $password = $_POST['password'] ?? '';
        
        if (!$username) {
            $errors[] = "Username wajib diisi";
        }
        
        // Check if username is already taken by another account
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM pengguna WHERE username=? AND id_karyawan != ?");
        $stmtCheck->execute([$username, $id]);
        if ($stmtCheck->fetchColumn() > 0) {
            $errors[] = "Username sudah digunakan oleh akun lain";
        }
        
        if (empty($errors)) {
            try {
                if ($aksiAkun === 'tambah_akun') {
                    if (!$password) {
                        $errors[] = "Password wajib diisi untuk akun baru";
                    } else {
                        $password_hash = password_hash($password, PASSWORD_BCRYPT);
                        $stmtInsert = $pdo->prepare("INSERT INTO pengguna (id_karyawan, username, password_hash, role, status_aktif) VALUES (?, ?, ?, ?, ?)");
                        $stmtInsert->execute([$id, $username, $password_hash, $role, $status_aktif]);
                        header("Location: karyawan_form.php?id=" . $id . "&success=Akun login berhasil dibuat");
                        exit;
                    }
                } elseif ($aksiAkun === 'edit_akun') {
                    if ($password) {
                        $password_hash = password_hash($password, PASSWORD_BCRYPT);
                        $stmtUpdate = $pdo->prepare("UPDATE pengguna SET username=?, password_hash=?, role=?, status_aktif=? WHERE id_karyawan=?");
                        $stmtUpdate->execute([$username, $password_hash, $role, $status_aktif, $id]);
                    } else {
                        $stmtUpdate = $pdo->prepare("UPDATE pengguna SET username=?, role=?, status_aktif=? WHERE id_karyawan=?");
                        $stmtUpdate->execute([$username, $role, $status_aktif, $id]);
                    }
                    header("Location: karyawan_form.php?id=" . $id . "&success=Akun login berhasil diperbarui");
                    exit;
                }
            } catch (PDOException $e) {
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    } else {
        $fields = ['nik_ktp','nama_lengkap','jenis_kelamin','tempat_lahir','tanggal_lahir',
                   'alamat','no_telepon','email','tanggal_masuk','status_aktif','id_departemen','id_jabatan'];
        foreach ($fields as $f) {
            $data[$f] = trim($_POST[$f] ?? '');
        }

        if (in_array($_SESSION['role'] ?? '', ['karyawan', 'manajer'])) {
            $data['id_departemen'] = $existing['id_departemen'];
            $data['id_jabatan'] = $existing['id_jabatan'];
            $data['status_aktif'] = $existing['status_aktif'];
            $data['tanggal_masuk'] = $existing['tanggal_masuk'];
        }

        // Validasi
        if (!$data['nik_ktp'])       $errors[] = "NIK KTP wajib diisi";
        if (!$data['nama_lengkap'])  $errors[] = "Nama lengkap wajib diisi";
        if (!$data['email'])         $errors[] = "Email wajib diisi";
        if (!$data['id_departemen']) $errors[] = "Departemen wajib dipilih";
        if (!$data['id_jabatan'])    $errors[] = "Jabatan wajib dipilih";
        if (!$data['tanggal_masuk']) $errors[] = "Tanggal masuk wajib diisi";

        if (empty($errors)) {
            try {
                if ($isEdit) {
                    $stmt = $pdo->prepare("UPDATE karyawan SET
                        nik_ktp=?, nama_lengkap=?, jenis_kelamin=?, tempat_lahir=?, tanggal_lahir=?,
                        alamat=?, no_telepon=?, email=?, tanggal_masuk=?, status_aktif=?,
                        id_departemen=?, id_jabatan=?
                        WHERE id_karyawan=?");
                    $stmt->execute([
                        $data['nik_ktp'], $data['nama_lengkap'], $data['jenis_kelamin'],
                        $data['tempat_lahir'] ?: null, $data['tanggal_lahir'] ?: null,
                        $data['alamat'] ?: null, $data['no_telepon'] ?: null,
                        $data['email'], $data['tanggal_masuk'], $data['status_aktif'],
                        $data['id_departemen'], $data['id_jabatan'], $id
                    ]);
                    if (in_array($_SESSION['role'] ?? '', ['karyawan', 'manajer'])) {
                        header("Location: karyawan_form.php?success=Data profil berhasil diperbarui");
                    } else {
                        header("Location: karyawan.php?success=Data karyawan berhasil diperbarui");
                    }
                } else {
                    $stmt = $pdo->prepare("INSERT INTO karyawan
                        (nik_ktp, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir,
                         alamat, no_telepon, email, tanggal_masuk, status_aktif, id_departemen, id_jabatan)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                    $stmt->execute([
                        $data['nik_ktp'], $data['nama_lengkap'], $data['jenis_kelamin'],
                        $data['tempat_lahir'] ?: null, $data['tanggal_lahir'] ?: null,
                        $data['alamat'] ?: null, $data['no_telepon'] ?: null,
                        $data['email'], $data['tanggal_masuk'], $data['status_aktif'],
                        $data['id_departemen'], $data['id_jabatan']
                    ]);
                    header("Location: karyawan.php?success=Karyawan baru berhasil ditambahkan");
                }
                exit;
            } catch (PDOException $e) {
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    }
}

require_once 'layout/header.php';
?>

<div class="row justify-content-center">
<div class="col-md-9">

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong><i class="bi bi-x-circle-fill me-2"></i>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <strong><i class="bi bi-person-fill me-2 text-primary"></i><?= $pageTitle ?></strong>
    </div>
    <div class="card-body p-4">
        <form method="POST">
            <!-- Data Pribadi -->
            <h6 class="text-muted mb-3" style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Data Pribadi</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">NIK KTP <span class="text-danger">*</span></label>
                    <input type="text" name="nik_ktp" class="form-control" value="<?= htmlspecialchars($data['nik_ktp']) ?>" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="L" <?= $data['jenis_kelamin']==='L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= $data['jenis_kelamin']==='P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="<?= htmlspecialchars($data['tempat_lahir'] ?? '') ?>">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="<?= $data['tanggal_lahir'] ?? '' ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" name="no_telepon" class="form-control" value="<?= htmlspecialchars($data['no_telepon'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
                </div>
            </div>

            <hr>
            <h6 class="text-muted mb-3" style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Data Pekerjaan</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Departemen <span class="text-danger">*</span></label>
                    <select name="id_departemen" class="form-select" required <?= in_array($_SESSION['role'] ?? '', ['karyawan', 'manajer']) ? 'disabled' : '' ?>>
                        <option value="">— Pilih —</option>
                        <?php foreach ($departemenList as $d): ?>
                            <option value="<?= $d['id_departemen'] ?>" <?= $data['id_departemen'] == $d['id_departemen'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d['nama_departemen']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                    <select name="id_jabatan" class="form-select" required <?= in_array($_SESSION['role'] ?? '', ['karyawan', 'manajer']) ? 'disabled' : '' ?>>
                        <option value="">— Pilih —</option>
                        <?php foreach ($jabatanList as $j): ?>
                            <option value="<?= $j['id_jabatan'] ?>" <?= $data['id_jabatan'] == $j['id_jabatan'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($j['nama_jabatan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status_aktif" class="form-select" <?= in_array($_SESSION['role'] ?? '', ['karyawan', 'manajer']) ? 'disabled' : '' ?>>
                        <option value="aktif"       <?= $data['status_aktif']==='aktif'       ? 'selected' : '' ?>>Aktif</option>
                        <option value="tidak_aktif" <?= $data['status_aktif']==='tidak_aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                        <option value="pensiun"     <?= $data['status_aktif']==='pensiun'     ? 'selected' : '' ?>>Pensiun</option>
                        <option value="resign"      <?= $data['status_aktif']==='resign'      ? 'selected' : '' ?>>Resign</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_masuk" class="form-control" value="<?= $data['tanggal_masuk'] ?>" required <?= in_array($_SESSION['role'] ?? '', ['karyawan', 'manajer']) ? 'disabled' : '' ?>>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 flex-wrap">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save-fill me-1"></i><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Karyawan' ?>
                </button>
                <a href="<?= in_array($_SESSION['role'] ?? '', ['karyawan', 'manajer']) ? '/simdk/' : '/simdk/karyawan.php' ?>" class="btn btn-outline-secondary">Batal</a>
                <?php if ($isEdit): ?>
                    <a href="/simdk/karyawan_pendidikan.php?id_karyawan=<?= $id ?>" class="btn btn-info text-white ms-md-auto">
                        <i class="bi bi-mortarboard-fill me-1"></i>Riwayat Pendidikan
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && $isEdit): ?>
    <div class="card mt-4">
        <div class="card-header">
            <strong><i class="bi bi-shield-lock-fill me-2 text-danger"></i>Kredensial & Akun Login Karyawan</strong>
        </div>
        <div class="card-body p-4">
            <?php
            $stmtAccount = $pdo->prepare("SELECT * FROM pengguna WHERE id_karyawan=?");
            $stmtAccount->execute([$id]);
            $account = $stmtAccount->fetch();
            ?>
            <form method="POST">
                <?php if ($account): ?>
                    <input type="hidden" name="aksi_akun" value="edit_akun">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Username Login</label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($account['username']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hak Akses / Role</label>
                            <select name="role_pengguna" class="form-select" required>
                                <option value="karyawan" <?= $account['role']==='karyawan' ? 'selected' : '' ?>>Karyawan</option>
                                <option value="manajer" <?= $account['role']==='manajer' ? 'selected' : '' ?>>Manajer</option>
                                <option value="hrd" <?= $account['role']==='hrd' ? 'selected' : '' ?>>HRD</option>
                                <option value="admin" <?= $account['role']==='admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ganti Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status_aktif_pengguna" id="statusAktifPengguna" value="1" <?= $account['status_aktif'] ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="statusAktifPengguna">Akun Aktif (Bisa Login)</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-key-fill me-1"></i>Simpan Perubahan Akun
                    </button>
                <?php else: ?>
                    <input type="hidden" name="aksi_akun" value="tambah_akun">
                    <div class="alert alert-warning py-2 px-3 mb-3" style="font-size:0.85rem;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Karyawan ini belum memiliki akun untuk login ke sistem.
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Username Login</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Hak Akses / Role</label>
                            <select name="role_pengguna" class="form-select" required>
                                <option value="karyawan" selected>Karyawan</option>
                                <option value="manajer">Manajer</option>
                                <option value="hrd">HRD</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status_aktif_pengguna" id="statusAktifPengguna" value="1" checked>
                            <label class="form-check-label fw-semibold" for="statusAktifPengguna">Akun Aktif (Bisa Login)</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-shield-plus me-1"></i>Buat Akun Login
                    </button>
                <?php endif; ?>
            </form>
        </div>
    </div>
<?php endif; ?>

</div>
</div>

<?php require_once 'layout/footer.php'; ?>
