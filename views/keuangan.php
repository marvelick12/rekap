<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan - Buku Kerja Digital</title>
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
                <div class="col-12 col-lg-4">
                    <div class="card-filament">
                        <div class="card-header">
                            <span><i class="fas fa-wallet text-primary me-2"></i>Ringkasan Bulan Ini</span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Tanggal Hari Ini</small>
                                <div class="fw-bold fs-5"><?= format_indo_date(date('Y-m-d')) ?></div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Bulan</small>
                                <div class="fw-bold fs-5"><?= get_indo_month(date('m')) ?></div>
                            </div>
                            <div class="border rounded p-3 mb-2">
                                <small class="text-muted">Pemasukan</small>
                                <div class="fw-bold text-success">Rp <?= number_format($pemasukan, 0, ',', '.') ?></div>
                            </div>
                            <div class="border rounded p-3 mb-2">
                                <small class="text-muted">Total Pengeluaran</small>
                                <div class="fw-bold text-danger">Rp <?= number_format($pengeluaran, 0, ',', '.') ?></div>
                            </div>
                            <div class="border rounded p-3">
                                <small class="text-muted">Total Saldo</small>
                                <div class="fw-bold text-primary">Rp <?= number_format($pemasukan - $pengeluaran, 0, ',', '.') ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <div class="card-filament mb-4">
                        <div class="card-header">
                            <span><i class="fas fa-arrow-down text-success me-2"></i>Riwayat Masuk</span>
                        </div>
                        <div class="card-body">
                            <?php if (empty($riwayatMasuk)): ?>
                                <div class="text-muted">Belum ada pemasukan.</div>
                            <?php else: ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($riwayatMasuk as $row): ?>
                                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($row['kategori']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($row['tanggal']) ?> • <?= htmlspecialchars($row['keterangan'] ?: '-') ?></small>
                                            </div>
                                            <span class="fw-bold text-success">+Rp <?= number_format($row['nominal'], 0, ',', '.') ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-filament">
                        <div class="card-header">
                            <span><i class="fas fa-arrow-up text-danger me-2"></i>Riwayat Keluar</span>
                        </div>
                        <div class="card-body">
                            <?php if (empty($riwayatKeluar)): ?>
                                <div class="text-muted">Belum ada pengeluaran.</div>
                            <?php else: ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($riwayatKeluar as $row): ?>
                                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($row['kategori']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($row['tanggal']) ?> • <?= htmlspecialchars($row['keterangan'] ?: '-') ?></small>
                                            </div>
                                            <span class="fw-bold text-danger">-Rp <?= number_format($row['nominal'], 0, ',', '.') ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-filament mt-4">
                <div class="card-header">
                    <span><i class="fas fa-plus-circle text-success me-2"></i>Tambah Catatan Keuangan</span>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jenis</label>
                                <select name="jenis" class="form-select">
                                    <option value="Pemasukan">Pemasukan</option>
                                    <option value="Pengeluaran">Pengeluaran</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" name="kategori" class="form-control" placeholder="Contoh: Gaji" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nominal</label>
                                <input type="number" name="nominal" class="form-control" min="0" step="1000" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan Catatan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
    </div>
</body>
</html>
