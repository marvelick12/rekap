<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Buku Kerja Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

        <div class="content-body">
            <!-- Alert Welcome -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between p-4 bg-white border rounded-4 shadow-sm">
                        <div>
                            <h4 class="fw-bold m-0 text-main">Selamat datang, <?= htmlspecialchars(get_user_name()) ?>!</h4>
                            <p class="text-muted m-0 mt-1 small">Kelola jurnal harian, target pekerjaan, dan evaluasi dalam satu dashboard ringkas.</p>
                        </div>
                        <a href="index.php?route=riwayat" class="btn btn-primary d-none d-sm-inline-flex align-items-center gap-2">
                            <i class="fas fa-file-invoice"></i> Buat Laporan Kerja
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Filament-like Cards -->
            <div class="row g-4 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <p class="stat-title">Jurnal Hari Ini</p>
                            <h3 class="stat-value"><?= $totalJurnalHariIni ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-list-check"></i>
                        </div>
                        <div>
                            <p class="stat-title">Rencana Hari Ini</p>
                            <h3 class="stat-value"><?= $totalRencanaHariIni ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="stat-title">Kerja Selesai</p>
                            <h3 class="stat-value" id="db-stat-selesai"><?= $totalPekerjaanSelesai ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon danger">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <p class="stat-title">Belum Selesai</p>
                            <h3 class="stat-value" id="db-stat-belum-selesai"><?= $totalPekerjaanBelumSelesai ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Widgets Grid Section -->
            <div class="row g-4 mb-4">
                <!-- Left widgets -->
                <div class="col-12 col-lg-8">
                    <!-- Progress Card -->
                    <div class="card-filament mb-4">
                        <div class="card-header">
                            <span><i class="fas fa-spinner text-primary me-2"></i>Persentase Progress Rencana Kerja</span>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center g-3">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 text-center bg-light">
                                        <small class="text-muted d-block text-uppercase fw-semibold mb-1" style="font-size:0.75rem;">Hari Ini (<?= $progressHariIni['selesai'] ?> / <?= $progressHariIni['total'] ?>)</small>
                                        <h4 class="fw-bold m-0 text-success"><?= $progressHariIni['persen'] ?>% Selesai</h4>
                                        <div class="progress-modern mt-2">
                                            <div class="progress-bar-modern" style="width: <?= $progressHariIni['persen'] ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="p-2 border rounded-3 text-center">
                                                <small class="text-muted d-block text-uppercase fw-semibold mb-1" style="font-size:0.7rem;">Mingguan</small>
                                                <h5 class="fw-bold m-0 text-primary"><?= $progressMingguan['persen'] ?>%</h5>
                                                <div class="progress-modern mt-1" style="height:4px;">
                                                    <div class="progress-bar-modern bg-primary" style="width: <?= $progressMingguan['persen'] ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 border rounded-3 text-center">
                                                <small class="text-muted d-block text-uppercase fw-semibold mb-1" style="font-size:0.7rem;">Bulanan</small>
                                                <h5 class="fw-bold m-0 text-warning"><?= $progressBulanan['persen'] ?>%</h5>
                                                <div class="progress-modern mt-1" style="height:4px;">
                                                    <div class="progress-bar-modern bg-warning" style="width: <?= $progressBulanan['persen'] ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Target / Rencana Hari Ini -->
                    <div class="card-filament mb-4">
                        <div class="card-header">
                            <span><i class="fas fa-tasks text-warning me-2"></i>Rencana Pekerjaan Hari Ini</span>
                            <a href="index.php?route=rencana" class="btn btn-sm btn-outline-primary py-1" style="font-size: 0.8rem;">Kelola Rencana</a>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($rencanaHariIni)): ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-calendar-times fs-2 mb-2 d-block"></i>
                                    <small>Belum ada rencana pekerjaan untuk hari ini.</small>
                                </div>
                            <?php else: ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($rencanaHariIni as $r): ?>
                                        <li class="list-group-item d-flex align-items-center justify-content-between py-3 border-0 border-bottom">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input rencana-status-checkbox" type="checkbox" role="switch" data-id="<?= $r['id'] ?>" <?= $r['status'] == 1 ? 'checked' : '' ?>>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-semibold text-main <?= $r['status'] == 1 ? 'text-decoration-line-through text-muted' : '' ?>" style="font-size:0.9rem;">
                                                        <?= htmlspecialchars($r['target_pekerjaan']) ?>
                                                    </span>
                                                    <small class="text-muted" style="font-size:0.75rem;"><i class="fas fa-tag me-1"></i>Project: <?= htmlspecialchars($r['nama_project']) ?></small>
                                                </div>
                                            </div>
                                            <span id="rencana-badge-<?= $r['id'] ?>" class="badge-modern <?= $r['status'] == 1 ? 'badge-modern-success' : 'badge-modern-danger' ?>">
                                                <i class="fas <?= $r['status'] == 1 ? 'fa-check-circle' : 'fa-times-circle' ?> me-1"></i>
                                                <?= $r['status'] == 1 ? 'Selesai' : 'Belum Selesai' ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Aktivitas / Jurnal Hari Ini -->
                    <div class="card-filament">
                        <div class="card-header">
                            <span><i class="fas fa-clock-rotate-left text-success me-2"></i>Aktivitas Kerja Hari Ini</span>
                            <a href="index.php?route=jurnal" class="btn btn-sm btn-outline-primary py-1" style="font-size: 0.8rem;">Kelola Jurnal</a>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($aktivitasHariIni)): ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-clipboard-question fs-2 mb-2 d-block"></i>
                                    <small>Belum ada aktivitas jurnal dicatat hari ini.</small>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-modern m-0">
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Divisi</th>
                                                <th>Nama Pekerjaan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($aktivitasHariIni as $j): ?>
                                                <tr>
                                                    <td class="fw-semibold text-nowrap"><?= date('H:i', strtotime($j['jam_mulai'])) ?> - <?= date('H:i', strtotime($j['jam_selesai'])) ?></td>
                                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($j['unit_divisi']) ?></span></td>
                                                    <td class="text-wrap"><?= htmlspecialchars($j['nama_pekerjaan']) ?></td>
                                                    <td>
                                                        <span class="badge-modern <?= $j['status'] === 'Selesai' ? 'badge-modern-success' : ($j['status'] === 'Pending' ? 'badge-modern-warning' : 'badge-modern-primary') ?>">
                                                            <i class="fas <?= $j['status'] === 'Selesai' ? 'fa-check-circle' : ($j['status'] === 'Pending' ? 'fa-hourglass-half' : 'fa-spinner') ?> me-1"></i>
                                                            <?= htmlspecialchars($j['status']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Right widgets -->
                <div class="col-12 col-lg-4">
                    <!-- Mini Calendar -->
                    <div class="card-filament mb-4">
                        <div class="card-body p-0">
                            <div class="mini-calendar">
                                <div class="mini-calendar-header">
                                    <?= get_indo_month(date('n')) ?> <?= date('Y') ?>
                                </div>
                                <div class="mini-calendar-body">
                                    <div class="mini-calendar-day text-uppercase"><?= get_indo_day(date('Y-m-d')) ?></div>
                                    <div class="mini-calendar-date"><?= date('d') ?></div>
                                    <span class="badge bg-light text-muted border px-3 py-1.5 rounded-pill mt-2" style="font-size: 0.75rem;">
                                        Total Evaluasi Bulan Ini: <strong><?= $totalEvaluasiBulanIni ?></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluasi Terakhir -->
                    <div class="card-filament">
                        <div class="card-header">
                            <span><i class="fas fa-chart-line text-primary me-2"></i>Evaluasi Harian Terakhir</span>
                            <a href="index.php?route=evaluasi" class="btn btn-sm btn-outline-primary py-1" style="font-size: 0.8rem;">Lihat Semua</a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($evaluasiTerakhir)): ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-calendar-minus fs-2 mb-2 d-block"></i>
                                    <small>Belum ada catatan evaluasi.</small>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <small class="text-muted d-block fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Tanggal</small>
                                    <span class="fw-semibold text-main"><?= format_indo_date($evaluasiTerakhir['tanggal']) ?></span>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted d-block fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Kendala Utama</small>
                                    <p class="small text-main m-0 bg-light p-2 rounded-2 border-start border-danger border-3"><?= htmlspecialchars($evaluasiTerakhir['kendala'] ?: 'Tidak ada kendala') ?></p>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted d-block fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Solusi Diterapkan</small>
                                    <p class="small text-main m-0 bg-light p-2 rounded-2 border-start border-success border-3"><?= htmlspecialchars($evaluasiTerakhir['solusi'] ?: '-') ?></p>
                                </div>

                                <div>
                                    <small class="text-muted d-block fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Target Besok</small>
                                    <p class="small text-main m-0 bg-light p-2 rounded-2 border-start border-primary border-3"><?= htmlspecialchars($evaluasiTerakhir['target_besok'] ?: '-') ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
    </div>

</body>
</html>
