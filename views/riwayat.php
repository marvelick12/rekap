<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan Kerja - Buku Kerja Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .report-section-header {
            background-color: var(--bg-main);
            border-bottom: 2px solid var(--border-color);
            padding: 0.75rem 1rem;
            font-weight: 700;
            color: var(--text-main);
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            margin-top: 1.5rem;
        }
        .day-report-card {
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            background: #FFFFFF;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            overflow: hidden;
        }
        .day-report-title {
            background-color: var(--primary-light);
            color: var(--primary);
            padding: 1rem 1.5rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }
        @media print {
            .day-report-card {
                page-break-inside: avoid;
                border: 1px solid #000 !important;
                margin-bottom: 1.5rem;
            }
            .day-report-title {
                background-color: #f0f0f0 !important;
                color: #000 !important;
                border-bottom: 1px solid #000 !important;
            }
            body {
                background: #fff !important;
                color: #000 !important;
            }
        }
    </style>
</head>
<body>

    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

        <div class="content-body">
            <!-- Filter Section -->
            <div class="card-filament mb-4 no-print">
                <div class="card-body">
                    <form action="index.php" method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="route" value="riwayat">
                        
                        <div class="col-12 col-sm-4">
                            <label for="start_date" class="form-label fw-semibold small">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date) ?>" required>
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="end_date" class="form-label fw-semibold small">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date) ?>" required>
                        </div>

                        <div class="col-12 col-sm-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-search me-1"></i> Tampilkan
                            </button>
                            <button type="button" onclick="window.print()" class="btn btn-outline-dark">
                                <i class="fas fa-print me-1"></i> Cetak PDF / Print
                            </button>
                            <button type="button" id="btnExportExcel" class="btn btn-success" title="Ekspor ke Excel">
                                <i class="fas fa-file-excel"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Print Header Banner (Hidden on Web Screen, Shown on Print) -->
            <div class="print-only text-center mb-4 pb-3 border-bottom">
                <h3 class="fw-bold m-0">LAPORAN KERJA HARIAN</h3>
                <h5 class="fw-semibold text-muted mt-1"><?= strtoupper(get_user_name()) ?> (<?= strtoupper(get_user_division() ?: '-') ?>)</h5>
                <small class="text-muted">Periode: <?= format_indo_date($start_date) ?> s/d <?= format_indo_date($end_date) ?></small>
            </div>

            <!-- consolidated log list -->
            <?php if (empty($grouped_data)): ?>
                <div class="card-filament text-center py-5 text-muted">
                    <i class="fas fa-folder-open fs-1 mb-2 d-block text-muted"></i>
                    <h5>Tidak ada aktivitas tercatat</h5>
                    <p class="small m-0">Silakan pilih rentang tanggal yang lain atau buat jurnal, rencana, dan evaluasi harian.</p>
                </div>
            <?php else: ?>
                <div id="reportContainer">
                    <?php foreach ($grouped_data as $date => $data): ?>
                        <div class="day-report-card">
                            <div class="day-report-title">
                                <span><i class="far fa-calendar-alt me-2"></i><?= get_indo_day($date) ?>, <?= format_indo_date($date) ?></span>
                                <small class="no-print badge bg-white text-primary border border-primary-light">Buku Kerja</small>
                            </div>
                            
                            <div class="p-4">
                                <div class="row g-4">
                                    <!-- JURNAL HARIAN -->
                                    <div class="col-12 col-xl-6">
                                        <h6 class="fw-bold border-bottom pb-2 text-primary" style="font-size:0.9rem;"><i class="fas fa-edit me-2"></i>Jurnal Pekerjaan (Selesai Dikerjakan)</h6>
                                        <?php if (empty($data['jurnal'])): ?>
                                            <p class="text-muted small italic my-3">Tidak ada aktivitas jurnal tercatat pada hari ini.</p>
                                        <?php else: ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm small align-middle my-2">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width:20%">Waktu</th>
                                                            <th style="width:50%">Pekerjaan</th>
                                                            <th style="width:30%">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($data['jurnal'] as $j): ?>
                                                            <tr>
                                                                <td class="font-monospace fw-semibold"><?= date('H:i', strtotime($j['jam_mulai'])) ?> - <?= date('H:i', strtotime($j['jam_selesai'])) ?></td>
                                                                <td>
                                                                    <strong><?= htmlspecialchars($j['nama_pekerjaan']) ?></strong>
                                                                    <?php if (!empty($j['catatan'])): ?>
                                                                        <div class="text-muted mt-1" style="font-size:0.75rem; white-space: pre-line;"><?= htmlspecialchars($j['catatan']) ?></div>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-<?= $j['status'] === 'Selesai' ? 'success' : ($j['status'] === 'Pending' ? 'warning text-dark' : 'primary') ?> rounded-pill" style="font-size:0.7rem;">
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

                                    <!-- RENCANA PEKERJAAN -->
                                    <div class="col-12 col-xl-6">
                                        <h6 class="fw-bold border-bottom pb-2 text-warning" style="font-size:0.9rem;"><i class="fas fa-list-check me-2"></i>Rencana & Target Kerja</h6>
                                        <?php if (empty($data['rencana'])): ?>
                                            <p class="text-muted small italic my-3">Tidak ada target rencana tercatat pada hari ini.</p>
                                        <?php else: ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm small align-middle my-2">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width:30%">Project</th>
                                                            <th style="width:50%">Target Pekerjaan</th>
                                                            <th style="width:20%">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($data['rencana'] as $r): ?>
                                                            <tr>
                                                                <td><span class="badge bg-light text-primary border border-primary-light"><?= htmlspecialchars($r['nama_project']) ?></span></td>
                                                                <td><?= htmlspecialchars($r['target_pekerjaan']) ?></td>
                                                                <td>
                                                                    <span class="badge bg-<?= $r['status'] == 1 ? 'success' : 'danger' ?> rounded-pill" style="font-size:0.7rem;">
                                                                        <?= $r['status'] == 1 ? 'Selesai' : 'Belum Selesai' ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- EVALUASI HARIAN -->
                                    <div class="col-12 border-top pt-3">
                                        <h6 class="fw-bold text-success mb-2" style="font-size:0.9rem;"><i class="fas fa-chart-line me-2"></i>Evaluasi Harian & Target Besok</h6>
                                        <?php if (is_null($data['evaluasi'])): ?>
                                            <p class="text-muted small italic m-0">Tidak ada evaluasi/refleksi harian dicatat pada hari ini.</p>
                                        <?php else: ?>
                                            <div class="row g-2 text-start">
                                                <div class="col-md-6 col-lg-3">
                                                    <div class="p-2 border rounded bg-light h-100">
                                                        <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.65rem;">Berjalan Baik</small>
                                                        <span class="small text-main" style="white-space: pre-line;"><?= htmlspecialchars($data['evaluasi']['berjalan_baik'] ?: '-') ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-3">
                                                    <div class="p-2 border rounded bg-light border-start border-danger border-2 h-100">
                                                        <small class="text-danger fw-bold d-block text-uppercase" style="font-size: 0.65rem;">Kendala / Bottleneck</small>
                                                        <span class="small text-main" style="white-space: pre-line;"><?= htmlspecialchars($data['evaluasi']['kendala'] ?: '-') ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-3">
                                                    <div class="p-2 border rounded bg-light border-start border-success border-2 h-100">
                                                        <small class="text-success fw-bold d-block text-uppercase" style="font-size: 0.65rem;">Solusi Diterapkan</small>
                                                        <span class="small text-main" style="white-space: pre-line;"><?= htmlspecialchars($data['evaluasi']['solusi'] ?: '-') ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-3">
                                                    <div class="p-2 border rounded bg-light border-start border-primary border-2 h-100">
                                                        <small class="text-primary fw-bold d-block text-uppercase" style="font-size: 0.65rem;">Target Besok</small>
                                                        <span class="small text-main fw-semibold" style="white-space: pre-line;"><?= htmlspecialchars($data['evaluasi']['target_besok'] ?: '-') ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
    </div>

    <!-- Script for Excel Export -->
    <script>
        document.getElementById('btnExportExcel')?.addEventListener('click', function() {
            // Get content container
            var reportContent = document.getElementById('reportContainer');
            if (!reportContent) return;

            // Generate clean temporary HTML Table for Excel parser
            var html = `
                <table border="1">
                    <thead>
                        <tr style="background-color: #3B82F6; color: #ffffff; font-weight: bold;">
                            <th colspan="7" style="font-size: 16px; text-align: center; height: 40px;">LAPORAN ELEKTRONIK KINERJA HARIAN</th>
                        </tr>
                        <tr>
                            <th colspan="7" style="text-align: center; font-weight: bold;">Nama Karyawan: <?= htmlspecialchars(get_user_name()) ?> (${'<?= htmlspecialchars(get_user_division() ?: "-") ?>'})</th>
                        </tr>
                        <tr>
                            <th colspan="7" style="text-align: center;">Periode Laporan: <?= htmlspecialchars($start_date) ?> s/d <?= htmlspecialchars($end_date) ?></th>
                        </tr>
                        <tr style="background-color: #f2f2f2; font-weight: bold;">
                            <th>Tanggal</th>
                            <th>Jurnal Pekerjaan (Waktu & Aktivitas)</th>
                            <th>Status Jurnal</th>
                            <th>Rencana Kerja (Project & Target)</th>
                            <th>Status Rencana</th>
                            <th>Kendala Utama</th>
                            <th>Solusi & Target Besok</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            <?php foreach ($grouped_data as $date => $data): ?>
                var dateStr = '<?= format_indo_date($date) ?>';
                
                // Format journals
                var journals = [];
                <?php foreach ($data['jurnal'] as $j): ?>
                    journals.push('<?= date("H:i", strtotime($j["jam_mulai"])) ?>-<?= date("H:i", strtotime($j["jam_selesai"])) ?>: <?= addslashes(str_replace(array("\r", "\n"), " ", $j["nama_pekerjaan"])) ?>');
                <?php endforeach; ?>
                var journalText = journals.join('\n');
                var journalStatusText = '<?= implode(", ", array_map(function($j){ return $j["status"]; }, $data["jurnal"])) ?>';

                // Format plans
                var plans = [];
                <?php foreach ($data['rencana'] as $r): ?>
                    plans.push('[<?= addslashes($r["nama_project"]) ?>] <?= addslashes(str_replace(array("\r", "\n"), " ", $r["target_pekerjaan"])) ?>');
                <?php endforeach; ?>
                var plansText = plans.join('\n');
                var plansStatusText = '<?= implode(", ", array_map(function($r){ return $r["status"] == 1 ? "Selesai" : "Belum Selesai"; }, $data["rencana"])) ?>';

                // Format evaluations
                var kendala = '<?= is_null($data["evaluasi"]) ? "-" : addslashes(str_replace(array("\r", "\n"), " ", $data["evaluasi"]["kendala"])) ?>';
                var solusiTarget = '<?= is_null($data["evaluasi"]) ? "-" : "Solusi: " . addslashes(str_replace(array("\r", "\n"), " ", $data["evaluasi"]["solusi"])) . " | Target: " . addslashes(str_replace(array("\r", "\n"), " ", $data["evaluasi"]["target_besok"])) ?>';

                html += `
                    <tr>
                        <td style="vertical-align: top;">\${dateStr}</td>
                        <td style="vertical-align: top; white-space: pre-wrap;">\${journalText}</td>
                        <td style="vertical-align: top;">\${journalStatusText}</td>
                        <td style="vertical-align: top; white-space: pre-wrap;">\${plansText}</td>
                        <td style="vertical-align: top;">\${plansStatusText}</td>
                        <td style="vertical-align: top; white-space: pre-wrap;">\${kendala}</td>
                        <td style="vertical-align: top; white-space: pre-wrap;">\${solusiTarget}</td>
                    </tr>
                `;
            <?php endforeach; ?>

            html += `
                    </tbody>
                </table>
            `;

            // Create blob and download link
            var blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'Laporan_Kerja_Harian_<?= str_replace(' ', '_', get_user_name()) ?>.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });
    </script>
</body>
</html>
