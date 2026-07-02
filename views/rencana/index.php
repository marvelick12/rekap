<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rencana Pekerjaan - Buku Kerja Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php require_once __DIR__ . '/../../layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>

        <div class="content-body">
            <!-- Progress Tracker Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card-filament bg-white">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div>
                                    <h5 class="fw-bold m-0 text-main"><i class="fas fa-bars-progress text-success me-2"></i>Progress Pekerjaan Hari Ini</h5>
                                    <p class="text-muted m-0 small" id="progress-count-text"><?= $progress['selesai'] ?> dari <?= $progress['total'] ?> pekerjaan selesai</p>
                                </div>
                                <div class="d-flex align-items-center gap-3 flex-grow-1" style="max-width: 450px;">
                                    <div class="progress-modern w-100">
                                        <div class="progress-bar-modern" id="progress-bar-fill" style="width: <?= $progress['persen'] ?>%"></div>
                                    </div>
                                    <span class="fw-bold text-success fs-5" id="progress-percent-text"><?= $progress['persen'] ?>%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Action Header -->
            <div class="card-filament mb-4 no-print">
                <div class="card-body">
                    <form action="index.php" method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="route" value="rencana">
                        
                        <div class="col-12 col-md-4">
                            <label for="search" class="form-label fw-semibold small">Cari Rencana</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0" id="search" name="search" placeholder="Cari project, target..." value="<?= htmlspecialchars($search ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <label for="bulan" class="form-label fw-semibold small">Filter Bulan</label>
                            <select class="form-select" id="bulan" name="bulan">
                                <option value="">Semua Bulan</option>
                                <?php foreach (get_months_list() as $num => $name): ?>
                                    <option value="<?= $num ?>" <?= (string)$num === (string)($bulan ?? '') ? 'selected' : '' ?>><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6 col-md-2">
                            <label for="tahun" class="form-label fw-semibold small">Filter Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <option value="">Semua</option>
                                <?php 
                                $current_year = (int)date('Y');
                                for ($y = $current_year; $y >= $current_year - 5; $y--): 
                                ?>
                                    <option value="<?= $y ?>" <?= (string)$y === (string)($tahun ?? '') ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="index.php?route=rencana" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="fas fa-undo"></i>
                            </a>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addRencanaModal">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card-filament">
                <div class="card-header">
                    <span><i class="fas fa-clipboard-list text-primary me-2"></i>Rencana & Target Kerja</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern m-0" id="rencanaTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Check</th>
                                    <th style="width: 15%">Tanggal</th>
                                    <th style="width: 20%">Project</th>
                                    <th style="width: 30%">Target Pekerjaan</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 10%">Catatan</th>
                                    <th style="width: 10%" class="text-center no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rencanas as $r): ?>
                                    <tr>
                                        <td data-label="Check">
                                            <div class="form-check form-switch m-0 d-flex justify-content-center">
                                                <input class="form-check-input rencana-status-checkbox" type="checkbox" role="switch" data-id="<?= $r['id'] ?>" <?= $r['status'] == 1 ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                        <td data-label="Tanggal">
                                            <span class="fw-semibold text-main"><?= format_indo_date($r['tanggal']) ?></span>
                                            <small class="text-muted d-block" style="font-size:0.75rem;"><i class="far fa-calendar me-1"></i><?= $r['hari'] ?></small>
                                        </td>
                                        <td data-label="Project" class="table-hide-mobile">
                                            <span class="badge bg-light text-primary border border-primary-light px-2.5 py-1.5 fw-semibold"><i class="fas fa-folder me-1"></i><?= htmlspecialchars($r['nama_project']) ?></span>
                                        </td>
                                        <td data-label="Target Pekerjaan" class="text-wrap fw-medium">
                                            <?= htmlspecialchars($r['target_pekerjaan']) ?>
                                        </td>
                                        <td data-label="Status" class="table-hide-mobile">
                                            <span id="rencana-badge-<?= $r['id'] ?>" class="badge-modern <?= $r['status'] == 1 ? 'badge-modern-success' : 'badge-modern-danger' ?>">
                                                <i class="fas <?= $r['status'] == 1 ? 'fa-check-circle' : 'fa-times-circle' ?> me-1"></i>
                                                <?= $r['status'] == 1 ? 'Selesai' : 'Belum Selesai' ?>
                                            </span>
                                        </td>
                                        <td data-label="Catatan" class="text-truncate" style="max-width: 150px;">
                                            <?= htmlspecialchars($r['catatan'] ?: '-') ?>
                                        </td>
                                        <td data-label="Aksi" class="no-print">
                                            <div class="d-flex justify-content-center gap-1">
                                                <button class="btn btn-sm btn-outline-warning py-1 px-2 btn-edit" data-id="<?= $r['id'] ?>" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="index.php?route=rencana/delete" method="POST" class="delete-form m-0 d-inline">
                                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADD RENCANA MODAL -->
        <div class="modal fade" id="addRencanaModal" tabindex="-1" aria-labelledby="addRencanaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-bottom px-4">
                        <h5 class="modal-title fw-bold" id="addRencanaModalLabel"><i class="fas fa-plus-circle text-primary me-2"></i>Tambah Rencana Pekerjaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?route=rencana/store" method="POST">
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="add_tanggal" class="form-label fw-semibold small">Tanggal Rencana</label>
                                    <input type="date" class="form-control" id="add_tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-12">
                                    <label for="add_nama_project" class="form-label fw-semibold small">Nama Project / Modul</label>
                                    <input type="text" class="form-control" id="add_nama_project" name="nama_project" placeholder="Contoh: Web Portal, Database Upgrade" required>
                                </div>
                                <div class="col-12">
                                    <label for="add_target_pekerjaan" class="form-label fw-semibold small">Target Pekerjaan</label>
                                    <input type="text" class="form-control" id="add_target_pekerjaan" name="target_pekerjaan" placeholder="Deskripsikan output yang diharapkan" required>
                                </div>
                                <div class="col-12">
                                    <label for="add_catatan" class="form-label fw-semibold small">Catatan Tambahan (Opsional)</label>
                                    <textarea class="form-control" id="add_catatan" name="catatan" rows="3" placeholder="Tuliskan kendala awal atau instruksi khusus..."></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="add_status" name="status">
                                        <label class="form-check-label fw-semibold small" for="add_status">Tandai Langsung Sebagai Selesai</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top px-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT RENCANA MODAL -->
        <div class="modal fade" id="editRencanaModal" tabindex="-1" aria-labelledby="editRencanaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-bottom px-4">
                        <h5 class="modal-title fw-bold" id="editRencanaModalLabel"><i class="fas fa-edit text-warning me-2"></i>Edit Rencana Pekerjaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?route=rencana/update" method="POST">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="edit_tanggal" class="form-label fw-semibold small">Tanggal Rencana</label>
                                    <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                                </div>
                                <div class="col-12">
                                    <label for="edit_nama_project" class="form-label fw-semibold small">Nama Project / Modul</label>
                                    <input type="text" class="form-control" id="edit_nama_project" name="nama_project" required>
                                </div>
                                <div class="col-12">
                                    <label for="edit_target_pekerjaan" class="form-label fw-semibold small">Target Pekerjaan</label>
                                    <input type="text" class="form-control" id="edit_target_pekerjaan" name="target_pekerjaan" required>
                                </div>
                                <div class="col-12">
                                    <label for="edit_catatan" class="form-label fw-semibold small">Catatan Tambahan (Opsional)</label>
                                    <textarea class="form-control" id="edit_catatan" name="catatan" rows="3"></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="edit_status" name="status">
                                        <label class="form-check-label fw-semibold small" for="edit_status">Tandai Sebagai Selesai</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top px-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#rencanaTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                    "emptyTable": "<i class='fas fa-tasks fs-1 d-block mb-2' style='opacity: 0.5;'></i>Belum ada data rencana pekerjaan."
                },
                "paging": true,
                "searching": false,
                "info": true,
                "ordering": true,
                "columns": [
                    { "orderable": false },
                    { "orderable": true },
                    { "orderable": true },
                    { "orderable": true },
                    { "orderable": true },
                    { "orderable": true },
                    { "orderable": false }
                ]
            });

            // Handle Edit Click
            $('.btn-edit').on('click', function() {
                var id = $(this).data('id');
                $.getJSON('index.php?route=rencana/detail&id=' + id, function(response) {
                    if (response.status === 'success') {
                        var data = response.data;
                        $('#edit_id').val(data.id);
                        $('#edit_tanggal').val(data.tanggal);
                        $('#edit_nama_project').val(data.nama_project);
                        $('#edit_target_pekerjaan').val(data.target_pekerjaan);
                        $('#edit_catatan').val(data.catatan);
                        
                        if (data.status == 1) {
                            $('#edit_status').prop('checked', true);
                        } else {
                            $('#edit_status').prop('checked', false);
                        }

                        $('#editRencanaModal').modal('show');
                    }
                });
            });
        });
    </script>
</body>
</html>
