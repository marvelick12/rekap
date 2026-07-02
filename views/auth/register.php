<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Buku Kerja Digital</title>
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

    <div class="login-card" style="max-width: 480px;">
        <div class="text-center mb-4">
            <span class="bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-3 mb-3" style="width: 3.5rem; height: 3.5rem; font-size: 1.5rem;">
                <i class="fas fa-user-plus"></i>
            </span>
            <h4 class="fw-bold m-0" style="letter-spacing: -0.025em;">Buat Akun Baru</h4>
            <p class="text-muted small mt-1">Daftarkan diri Anda untuk menggunakan Buku Kerja Digital</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 border-0 rounded-3 py-2 small" style="background-color: var(--danger-light); color: var(--danger);">
                <i class="fas fa-exclamation-circle"></i>
                <div><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            </div>
        <?php endif; ?>

        <form action="index.php?route=register" method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold small">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold small">Alamat Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="nama@perusahaan.com" required>
            </div>

            <div class="mb-3">
                <label for="division" class="form-label fw-semibold small">Unit / Divisi Kerja</label>
                <input type="text" class="form-control" id="division" name="division" placeholder="Contoh: IT Support, Keuangan" required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold small">Password (Min. 6 karakter)</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold mb-3">
                <i class="fas fa-user-plus me-1"></i> Daftar
            </button>
        </form>

        <div class="text-center">
            <p class="small text-muted m-0">Sudah punya akun? <a href="login.php" class="text-primary fw-semibold text-decoration-none">Masuk di sini</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
