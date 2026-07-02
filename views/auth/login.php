<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Buku Kerja Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: var(--bg-main);
        }
    </style>
</head>
<body class="login-bg">

    <div class="login-card">
        <div class="text-center mb-4">
            <span class="bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-3 mb-3" style="width: 3.5rem; height: 3.5rem; font-size: 1.5rem;">
                <i class="fas fa-briefcase"></i>
            </span>
            <h4 class="fw-bold m-0" style="letter-spacing: -0.025em;">Masuk ke Akun Anda</h4>
            <p class="text-muted small mt-1">Silakan masuk menggunakan email dan password terdaftar</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 border-0 rounded-3 py-2 small" style="background-color: var(--danger-light); color: var(--danger);">
                <i class="fas fa-exclamation-circle"></i>
                <div><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success d-flex align-items-center gap-2 border-0 rounded-3 py-2 small" style="background-color: var(--success-light); color: var(--success);">
                <i class="fas fa-check-circle"></i>
                <div><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold small">Alamat Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="nama@perusahaan.com" required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold small">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold mb-3">
                <i class="fas fa-sign-in-alt me-1"></i> Masuk
            </button>
        </form>

        <div class="text-center">
            <p class="small text-muted m-0">Belum punya akun? <a href="index.php?route=register" class="text-primary fw-semibold text-decoration-none">Daftar Sekarang</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
