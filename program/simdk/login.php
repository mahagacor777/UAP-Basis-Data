<?php
require_once 'config.php';

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("
            SELECT p.*, k.nama_lengkap 
            FROM pengguna p
            JOIN karyawan k ON p.id_karyawan = k.id_karyawan
            WHERE p.username = ? AND p.status_aktif = 1
        ");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id_pengguna'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama_karyawan'] = $user['nama_lengkap'];
            $_SESSION['id_karyawan'] = $user['id_karyawan'];

            // Update terakhir login
            $pdo->prepare("UPDATE pengguna SET terakhir_login = NOW() WHERE id_pengguna = ?")
                ->execute([$user['id_pengguna']]);

            header("Location: index.php");
            exit;
        } else {
            $error = 'Username atau password salah, atau akun Anda dinonaktifkan.';
        }
    } else {
        $error = 'Harap isi semua kolom.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIMDK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at 10% 20%, rgb(18, 28, 64) 0%, rgb(9, 15, 36) 90%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #fff;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Background decorative circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(30, 64, 175, 0.4) 0%, rgba(99, 102, 241, 0.2) 100%);
            filter: blur(80px);
            z-index: -1;
        }
        .circle-1 {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 15%;
        }
        .circle-2 {
            width: 400px;
            height: 400px;
            bottom: 10%;
            right: 15%;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            width: 100%;
            max-width: 440px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transform: translateY(0);
            transition: all 0.3s ease;
        }
        
        .login-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
            font-size: 2rem;
            color: #fff;
            margin-bottom: 16px;
        }

        .brand-header h4 {
            font-weight: 700;
            margin: 0;
            background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .brand-header p {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .form-label {
            font-size: 0.825rem;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 24px;
        }

        .input-group-custom i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.1rem;
            transition: color 0.3s;
        }

        .form-control-custom {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #fff;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control-custom:focus {
            outline: none;
            background: rgba(15, 23, 42, 0.8);
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .form-control-custom:focus + i {
            color: #3b82f6;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            background: linear-gradient(135deg, #1d4ed8 0%, #4338ca 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-custom {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-custom i {
            font-size: 1.1rem;
            color: #ef4444;
        }
    </style>
</head>
<body>

<div class="circle circle-1"></div>
<div class="circle circle-2"></div>

<div class="login-card">
    <div class="brand-header">
        <div class="brand-icon">
            <i class="bi bi-building-fill"></i>
        </div>
        <h4>SIMDK SYSTEM</h4>
        <p>Sistem Informasi Manajemen Data Karyawan</p>
    </div>

    <?php if ($error): ?>
        <div class="alert-custom">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div><?= htmlspecialchars($error) ?></div>
        </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group-custom">
                <input type="text" name="username" class="form-control-custom" placeholder="Masukkan username" required autofocus>
                <i class="bi bi-person-fill"></i>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="input-group-custom">
                <input type="password" name="password" class="form-control-custom" placeholder="Masukkan password" required>
                <i class="bi bi-lock-fill"></i>
            </div>
        </div>

        <button type="submit" class="btn-login">
            Masuk <i class="bi bi-arrow-right-short" style="font-size:1.2rem;"></i>
        </button>
    </form>
</div>

</body>
</html>
