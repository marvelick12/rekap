<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Buku Kerja Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

        <div class="content-body">
            <div class="row g-4">
                <!-- User Profile Info Card -->
                <div class="col-12 col-md-4">
                    <div class="card-filament text-center p-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle fw-bold mb-3 shadow-sm" style="width: 5.5rem; height: 5.5rem; font-size: 2.25rem;">
                            <?= strtoupper(substr(get_user_name(), 0, 1)) ?>
                        </div>
                        <h5 class="fw-bold text-main m-0"><?= htmlspecialchars(get_user_name()) ?></h5>
                        <p class="text-muted small mt-1 mb-3"><?= htmlspecialchars(get_user_email()) ?></p>
                        
                        <span class="badge bg-primary-light text-primary px-3 py-2 rounded-pill fw-semibold" style="font-size:0.8rem;">
                            <i class="fas fa-briefcase me-1"></i> <?= htmlspecialchars(get_user_division() ?: 'Divisi Belum Diatur') ?>
                        </span>

                        <hr class="my-4" style="border-color: var(--border-color);">

                        <div class="text-start">
                            <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size:0.7rem;">Tanggal Bergabung</small>
                            <span class="fw-semibold text-main small" style="font-size:0.85rem;"><i class="far fa-calendar-check me-1"></i> <?= format_indo_date($user['created_at']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Update Settings Card -->
                <div class="col-12 col-md-8">
                    <div class="card-filament">
                        <div class="card-header">
                            <span><i class="fas fa-user-gear text-primary me-2"></i>Pengaturan Akun & Profil</span>
                        </div>
                        <div class="card-body">
                            <form action="index.php?route=profile/update" method="POST">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="name" class="form-label fw-semibold small">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="division" class="form-label fw-semibold small">Unit / Divisi Kerja</label>
                                        <input type="text" class="form-control" id="division" name="division" value="<?= htmlspecialchars($user['division'] ?? '') ?>">
                                    </div>
                                    <div class="col-12">
                                        <label for="email" class="form-label fw-semibold small text-muted">Alamat Email (Tidak dapat diubah)</label>
                                        <input type="email" class="form-control bg-light" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly disabled>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="p-3 border rounded-3" style="background-color: var(--bg-main);">
                                            <h6 class="fw-bold mb-2 small text-main"><i class="fas fa-key me-2 text-warning"></i>Ubah Password (Kosongkan jika tidak ingin mengubah)</h6>
                                            <div class="row g-3">
                                                <div class="col-12 col-md-6">
                                                    <label for="password" class="form-label fw-semibold small">Password Baru</label>
                                                    <input type="password" class="form-control bg-white" id="password" name="password" placeholder="••••••••">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label for="confirm_password" class="form-label fw-semibold small">Konfirmasi Password Baru</label>
                                                    <input type="password" class="form-control bg-white" id="confirm_password" name="confirm_password" placeholder="••••••••">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                                        <a href="index.php?route=dashboard" class="btn btn-outline-secondary">Batal</a>
                                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Simpan Pengaturan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
    </div>

</body>
</html>
