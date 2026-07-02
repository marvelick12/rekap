<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluasi Harian - Buku Kerja Digital</title>
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
            <!-- Filter & Action Header -->
            <div class="card-filament mb-4 no-print">
                <div class="card-body">
                    <form action="index.php" method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="route" value="evaluasi">
                        
                        <div class="col-12 col-md-4">
                            <label for="search" class="form-label fw-semibold small">Cari Evaluasi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0" id="search" name="search" placeholder="Cari kendala, solusi..." value="<?= htmlspecialchars($search ?? '') ?>">
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
                            <a href="index.php?route=evaluasi" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="fas fa-undo"></i>
                            </a>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEvaluasiModal">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card-filament">
                <div class="card-header">
                    <span><i class="fas fa-chart-pie text-primary me-2"></i>Evaluasi Kerja & Refleksi Harian</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern m-0" id="evaluasiTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 15%">Tanggal</th>
                                    <th style="width: 25%">Kendala Teridentifikasi</th>
                                    <th style="width: 25%">Solusi Dilakukan</th>
                                    <th style="width: 20%">Target Besok</th>
                                    <th style="width: 10%" class="text-center no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($evaluasis as $e): 
                                ?>
                                    <tr>
                                        <td data-label="No" class="table-hide-mobile"><?= $no++ ?></td>
                                        <td data-label="Tanggal">
                                            <span class="fw-semibold text-main"><?= format_indo_date($e['tanggal']) ?></span>
                                        </td>
                                        <td data-label="Kendala" class="text-wrap">
                                            <?php if (!empty($e['kendala'])): ?>
                                                <div class="p-2 bg-danger-light rounded text-danger small border-start border-danger border-3">
                                                    <?= htmlspecialchars($e['kendala']) ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-success small"><i class="fas fa-circle-check me-1"></i> Tidak ada kendala</span>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="Solusi" class="text-wrap small table-hide-mobile">
                                            <?= htmlspecialchars($e['solusi'] ?: '-') ?>
                                        </td>
                                        <td data-label="Target Besok" class="text-wrap small font-monospace">
                                            <?= htmlspecialchars($e['target_besok'] ?: '-') ?>
                                        </td>
                                        <td data-label="Aksi" class="no-print">
                                            <div class="d-flex justify-content-center gap-1">
                                                <button class="btn btn-sm btn-outline-info py-1 px-2 btn-detail" data-id="<?= $e['id'] ?>" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning py-1 px-2 btn-edit" data-id="<?= $e['id'] ?>" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="index.php?route=evaluasi/delete" method="POST" class="delete-form m-0 d-inline">
                                                    <input type="hidden" name="id" value="<?= $e['id'] ?>">
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

        <!-- ADD EVALUASI MODAL -->
        <div class="modal fade" id="addEvaluasiModal" tabindex="-1" aria-labelledby="addEvaluasiModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-bottom px-4">
                        <h5 class="modal-title fw-bold" id="addEvaluasiModalLabel"><i class="fas fa-plus-circle text-primary me-2"></i>Tambah Evaluasi Harian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?route=evaluasi/store" method="POST">
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="add_tanggal" class="form-label fw-semibold small">Tanggal Evaluasi</label>
                                    <input type="date" class="form-control" id="add_tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="add_berjalan_baik" class="form-label fw-semibold small text-success">Hal yang berjalan dengan baik</label>
                                    <textarea class="form-control" id="add_berjalan_baik" name="berjalan_baik" rows="3" placeholder="Pencapaian atau efisiensi hari ini..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="add_kendala" class="form-label fw-semibold small text-danger">Kendala yang ditemukan</label>
                                    <textarea class="form-control" id="add_kendala" name="kendala" rows="3" placeholder="Masalah teknis, bottleneck, blocker..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="add_solusi" class="form-label fw-semibold small text-warning">Solusi yang dilakukan</label>
                                    <textarea class="form-control" id="add_solusi" name="solusi" rows="3" placeholder="Langkah mitigasi yang diambil..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="add_perlu_diperbaiki" class="form-label fw-semibold small text-primary">Hal yang perlu diperbaiki besok</label>
                                    <textarea class="form-control" id="add_perlu_diperbaiki" name="perlu_diperbaiki" rows="3" placeholder="Koreksi diri atau peningkatan metode..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="add_target_besok" class="form-label fw-semibold small">Target Besok</label>
                                    <textarea class="form-control" id="add_target_besok" name="target_besok" rows="3" placeholder="Tujuan atau milestones esok hari..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="add_catatan_tambahan" class="form-label fw-semibold small">Catatan Tambahan (Opsional)</label>
                                    <textarea class="form-control" id="add_catatan_tambahan" name="catatan_tambahan" rows="3" placeholder="Catatan di luar agenda utama..."></textarea>
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

        <!-- EDIT EVALUASI MODAL -->
        <div class="modal fade" id="editEvaluasiModal" tabindex="-1" aria-labelledby="editEvaluasiModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-bottom px-4">
                        <h5 class="modal-title fw-bold" id="editEvaluasiModalLabel"><i class="fas fa-edit text-warning me-2"></i>Edit Evaluasi Harian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?route=evaluasi/update" method="POST">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="edit_tanggal" class="form-label fw-semibold small">Tanggal Evaluasi</label>
                                    <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_berjalan_baik" class="form-label fw-semibold small text-success">Hal yang berjalan dengan baik</label>
                                    <textarea class="form-control" id="edit_berjalan_baik" name="berjalan_baik" rows="3"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_kendala" class="form-label fw-semibold small text-danger">Kendala yang ditemukan</label>
                                    <textarea class="form-control" id="edit_kendala" name="kendala" rows="3"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_solusi" class="form-label fw-semibold small text-warning">Solusi yang dilakukan</label>
                                    <textarea class="form-control" id="edit_solusi" name="solusi" rows="3"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_perlu_diperbaiki" class="form-label fw-semibold small text-primary">Hal yang perlu diperbaiki besok</label>
                                    <textarea class="form-control" id="edit_perlu_diperbaiki" name="perlu_diperbaiki" rows="3"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_target_besok" class="form-label fw-semibold small">Target Besok</label>
                                    <textarea class="form-control" id="edit_target_besok" name="target_besok" rows="3"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_catatan_tambahan" class="form-label fw-semibold small">Catatan Tambahan (Opsional)</label>
                                    <textarea class="form-control" id="edit_catatan_tambahan" name="catatan_tambahan" rows="3"></textarea>
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

        <!-- DETAIL EVALUASI MODAL -->
        <div class="modal fade" id="detailEvaluasiModal" tabindex="-1" aria-labelledby="detailEvaluasiModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-bottom px-4">
                        <h5 class="modal-title fw-bold" id="detailEvaluasiModalLabel"><i class="fas fa-info-circle text-info me-2"></i>Detail Evaluasi Harian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <div class="col-12 border-bottom pb-2">
                                <h6 class="text-muted small fw-bold text-uppercase m-0">Tanggal Evaluasi</h6>
                                <span class="fw-semibold text-main fs-5" id="detail_tanggal"></span>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light border-start border-success border-4 h-100">
                                    <h6 class="text-success fw-bold small text-uppercase mb-2"><i class="fas fa-circle-check me-2"></i>Berjalan Dengan Baik</h6>
                                    <p id="detail_berjalan_baik" class="small m-0 text-main" style="white-space: pre-line;"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light border-start border-danger border-4 h-100">
                                    <h6 class="text-danger fw-bold small text-uppercase mb-2"><i class="fas fa-triangle-exclamation me-2"></i>Kendala Yang Ditemukan</h6>
                                    <p id="detail_kendala" class="small m-0 text-main" style="white-space: pre-line;"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light border-start border-warning border-4 h-100">
                                    <h6 class="text-warning fw-bold small text-uppercase mb-2"><i class="fas fa-lightbulb me-2"></i>Solusi Yang Dilakukan</h6>
                                    <p id="detail_solusi" class="small m-0 text-main" style="white-space: pre-line;"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light border-start border-primary border-4 h-100">
                                    <h6 class="text-primary fw-bold small text-uppercase mb-2"><i class="fas fa-arrows-spin me-2"></i>Perlu Diperbaiki Besok</h6>
                                    <p id="detail_perlu_diperbaiki" class="small m-0 text-main" style="white-space: pre-line;"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light border-start border-dark border-4 h-100">
                                    <h6 class="text-dark fw-bold small text-uppercase mb-2"><i class="fas fa-bullseye me-2"></i>Target Pekerjaan Besok</h6>
                                    <p id="detail_target_besok" class="small m-0 text-main" style="white-space: pre-line;"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light h-100">
                                    <h6 class="text-muted fw-bold small text-uppercase mb-2"><i class="fas fa-paperclip me-2"></i>Catatan Tambahan</h6>
                                    <p id="detail_catatan_tambahan" class="small m-0 text-main" style="white-space: pre-line;"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top px-4">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#evaluasiTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                    "emptyTable": "<i class='fas fa-chart-line fs-1 d-block mb-2' style='opacity: 0.5;'></i>Belum ada data evaluasi harian."
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
                    { "orderable": false }
                ]
            });

            // Handle Detail Click
            $('.btn-detail').on('click', function() {
                var id = $(this).data('id');
                $.getJSON('index.php?route=evaluasi/detail&id=' + id, function(response) {
                    if (response.status === 'success') {
                        var data = response.data;
                        $('#detail_tanggal').text(data.formatted_date);
                        $('#detail_berjalan_baik').text(data.berjalan_baik ? data.berjalan_baik : '-');
                        $('#detail_kendala').text(data.kendala ? data.kendala : 'Tidak ada kendala.');
                        $('#detail_solusi').text(data.solusi ? data.solusi : '-');
                        $('#detail_perlu_diperbaiki').text(data.perlu_diperbaiki ? data.perlu_diperbaiki : '-');
                        $('#detail_target_besok').text(data.target_besok ? data.target_besok : '-');
                        $('#detail_catatan_tambahan').text(data.catatan_tambahan ? data.catatan_tambahan : '-');

                        $('#detailEvaluasiModal').modal('show');
                    }
                });
            });

            // Handle Edit Click
            $('.btn-edit').on('click', function() {
                var id = $(this).data('id');
                $.getJSON('index.php?route=evaluasi/detail&id=' + id, function(response) {
                    if (response.status === 'success') {
                        var data = response.data;
                        $('#edit_id').val(data.id);
                        $('#edit_tanggal').val(data.tanggal);
                        $('#edit_berjalan_baik').val(data.berjalan_baik);
                        $('#edit_kendala').val(data.kendala);
                        $('#edit_solusi').val(data.solusi);
                        $('#edit_perlu_diperbaiki').val(data.perlu_diperbaiki);
                        $('#edit_target_besok').val(data.target_besok);
                        $('#edit_catatan_tambahan').val(data.catatan_tambahan);

                        $('#editEvaluasiModal').modal('show');
                    }
                });
            });
        });
    </script>
</body>
</html>
