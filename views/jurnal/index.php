<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Harian - Buku Kerja Digital</title>
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
                        <input type="hidden" name="route" value="jurnal">
                        
                        <div class="col-12 col-md-4">
                            <label for="search" class="form-label fw-semibold small">Cari Pekerjaan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0" id="search" name="search" placeholder="Cari unit, pekerjaan..." value="<?= htmlspecialchars($search ?? '') ?>">
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
                            <a href="index.php?route=jurnal" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="fas fa-undo"></i>
                            </a>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addJurnalModal">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Jurnal Data Table -->
            <div class="card-filament">
                <div class="card-header">
                    <span><i class="fas fa-book text-primary me-2"></i>Daftar Jurnal Pekerjaan Harian</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern m-0" id="jurnalTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 15%">Tanggal</th>
                                    <th style="width: 15%">Unit / Divisi</th>
                                    <th style="width: 30%">Pekerjaan</th>
                                    <th style="width: 15%">Catatan</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 10%" class="text-center no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1; 
                                foreach ($jurnals as $j): 
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <span class="fw-semibold text-main"><?= format_indo_date($j['tanggal']) ?></span>
                                            <small class="text-muted d-block" style="font-size:0.75rem;"><i class="far fa-clock me-1"></i><?= date('H:i', strtotime($j['jam_mulai'])) ?> - <?= date('H:i', strtotime($j['jam_selesai'])) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border"><?= htmlspecialchars($j['unit_divisi']) ?></span>
                                        </td>
                                        <td class="text-wrap fw-medium">
                                            <?= htmlspecialchars($j['nama_pekerjaan']) ?>
                                        </td>
                                        <td class="text-truncate" style="max-width: 150px;">
                                            <?= htmlspecialchars($j['catatan'] ?: '-') ?>
                                        </td>
                                        <td>
                                            <span class="badge-modern <?= $j['status'] === 'Selesai' ? 'badge-modern-success' : ($j['status'] === 'Pending' ? 'badge-modern-warning' : 'badge-modern-primary') ?>">
                                                <i class="fas <?= $j['status'] === 'Selesai' ? 'fa-check-circle' : ($j['status'] === 'Pending' ? 'fa-hourglass-half' : 'fa-spinner') ?> me-1"></i>
                                                <?= htmlspecialchars($j['status']) ?>
                                            </span>
                                        </td>
                                        <td class="no-print">
                                            <div class="d-flex justify-content-center gap-1">
                                                <button class="btn btn-sm btn-outline-info py-1 px-2 btn-detail" data-id="<?= $j['id'] ?>" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning py-1 px-2 btn-edit" data-id="<?= $j['id'] ?>" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="index.php?route=jurnal/delete" method="POST" class="delete-form m-0 d-inline">
                                                    <input type="hidden" name="id" value="<?= $j['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($jurnals)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open fs-1 mb-2 d-block"></i>
                                            Belum ada data jurnal harian.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADD JURNAL MODAL -->
        <div class="modal fade" id="addJurnalModal" tabindex="-1" aria-labelledby="addJurnalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-bottom px-4">
                        <h5 class="modal-title fw-bold" id="addJurnalModalLabel"><i class="fas fa-plus-circle text-primary me-2"></i>Tambah Jurnal Harian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?route=jurnal/store" method="POST" enctype="multipart/form-data">
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="add_tanggal" class="form-label fw-semibold small">Tanggal</label>
                                    <input type="date" class="form-control" id="add_tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="add_unit_divisi" class="form-label fw-semibold small">Unit / Divisi</label>
                                    <input type="text" class="form-control" id="add_unit_divisi" name="unit_divisi" placeholder="Contoh: IT Support" value="<?= htmlspecialchars(get_user_division()) ?>" required>
                                </div>
                                <div class="col-12">
                                    <label for="add_nama_pekerjaan" class="form-label fw-semibold small">Nama Pekerjaan / Aktivitas</label>
                                    <input type="text" class="form-control" id="add_nama_pekerjaan" name="nama_pekerjaan" placeholder="Deskripsikan pekerjaan utama" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="add_jam_mulai" class="form-label fw-semibold small">Jam Mulai</label>
                                    <input type="time" class="form-control" id="add_jam_mulai" name="jam_mulai" value="08:00" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="add_jam_selesai" class="form-label fw-semibold small">Jam Selesai</label>
                                    <input type="time" class="form-control" id="add_jam_selesai" name="jam_selesai" value="17:00" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="add_status" class="form-label fw-semibold small">Status</label>
                                    <select class="form-select" id="add_status" name="status" required>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dalam Proses">Dalam Proses</option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="add_catatan" class="form-label fw-semibold small">Catatan Detail (Opsional)</label>
                                    <textarea class="form-control" id="add_catatan" name="catatan" rows="3" placeholder="Tuliskan catatan tambahan mengenai pekerjaan tersebut..."></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="add_dokumentasi" class="form-label fw-semibold small">Foto / Dokumentasi (Opsional)</label>
                                    <input type="file" class="form-control" id="add_dokumentasi" name="dokumentasi" accept="image/*">
                                    <small class="text-muted" style="font-size: 0.75rem;">Format diperbolehkan: JPG, JPEG, PNG, GIF</small>
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

        <!-- EDIT JURNAL MODAL -->
        <div class="modal fade" id="editJurnalModal" tabindex="-1" aria-labelledby="editJurnalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-bottom px-4">
                        <h5 class="modal-title fw-bold" id="editJurnalModalLabel"><i class="fas fa-edit text-warning me-2"></i>Edit Jurnal Harian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?route=jurnal/update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="edit_tanggal" class="form-label fw-semibold small">Tanggal</label>
                                    <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_unit_divisi" class="form-label fw-semibold small">Unit / Divisi</label>
                                    <input type="text" class="form-control" id="edit_unit_divisi" name="unit_divisi" required>
                                </div>
                                <div class="col-12">
                                    <label for="edit_nama_pekerjaan" class="form-label fw-semibold small">Nama Pekerjaan / Aktivitas</label>
                                    <input type="text" class="form-control" id="edit_nama_pekerjaan" name="nama_pekerjaan" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_jam_mulai" class="form-label fw-semibold small">Jam Mulai</label>
                                    <input type="time" class="form-control" id="edit_jam_mulai" name="jam_mulai" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_jam_selesai" class="form-label fw-semibold small">Jam Selesai</label>
                                    <input type="time" class="form-control" id="edit_jam_selesai" name="jam_selesai" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_status" class="form-label fw-semibold small">Status</label>
                                    <select class="form-select" id="edit_status" name="status" required>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dalam Proses">Dalam Proses</option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="edit_catatan" class="form-label fw-semibold small">Catatan Detail (Opsional)</label>
                                    <textarea class="form-control" id="edit_catatan" name="catatan" rows="3"></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="edit_dokumentasi" class="form-label fw-semibold small">Ubah Foto / Dokumentasi (Opsional)</label>
                                    <input type="file" class="form-control" id="edit_dokumentasi" name="dokumentasi" accept="image/*">
                                    <div class="mt-2" id="edit_dokumentasi_preview"></div>
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

        <!-- DETAIL JURNAL MODAL -->
        <div class="modal fade" id="detailJurnalModal" tabindex="-1" aria-labelledby="detailJurnalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-bottom px-4">
                        <h5 class="modal-title fw-bold" id="detailJurnalModalLabel"><i class="fas fa-info-circle text-info me-2"></i>Detail Jurnal Harian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <table class="table table-bordered table-striped rounded">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">Tanggal</th>
                                    <td id="detail_tanggal"></td>
                                </tr>
                                <tr>
                                    <th>Hari / Waktu</th>
                                    <td><span id="detail_hari"></span>, <span id="detail_waktu"></span></td>
                                </tr>
                                <tr>
                                    <th>Unit / Divisi</th>
                                    <td><span class="badge bg-light text-dark border" id="detail_unit"></span></td>
                                </tr>
                                <tr>
                                    <th>Nama Pekerjaan</th>
                                    <td class="fw-semibold text-primary" id="detail_pekerjaan"></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><div id="detail_status"></div></td>
                                </tr>
                                <tr>
                                    <th>Catatan Detail</th>
                                    <td id="detail_catatan" style="white-space: pre-line;"></td>
                                </tr>
                                <tr>
                                    <th>Dokumentasi</th>
                                    <td>
                                        <div id="detail_image_container" class="text-center"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer border-top px-4">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
    </div>

    <!-- Script triggers for modal detail & edit AJAX fetching -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable (without server-side, using standard frontend filtering matching our custom controls)
            var table = $('#jurnalTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                },
                "paging": true,
                "searching": false, // using custom filter card above
                "info": true,
                "ordering": true,
                "columnDefs": [
                    { "orderable": false, "targets": 6 }
                ]
            });

            // Handle Detail Click
            $('.btn-detail').on('click', function() {
                var id = $(this).data('id');
                $.getJSON('index.php?route=jurnal/detail&id=' + id, function(response) {
                    if (response.status === 'success') {
                        var data = response.data;
                        $('#detail_tanggal').text(data.formatted_date);
                        $('#detail_hari').text(data.hari);
                        $('#detail_waktu').text(data.jam_mulai.substring(0, 5) + ' - ' + data.jam_selesai.substring(0, 5));
                        $('#detail_unit').text(data.unit_divisi);
                        $('#detail_pekerjaan').text(data.nama_pekerjaan);
                        
                        var statusHtml = '';
                        if (data.status === 'Selesai') {
                            statusHtml = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Selesai</span>';
                        } else if (data.status === 'Pending') {
                            statusHtml = '<span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i> Pending</span>';
                        } else {
                            statusHtml = '<span class="badge bg-primary"><i class="fas fa-spinner me-1"></i> Dalam Proses</span>';
                        }
                        $('#detail_status').html(statusHtml);
                        $('#detail_catatan').text(data.catatan ? data.catatan : 'Tidak ada catatan tambahan.');

                        if (data.dokumentasi) {
                            $('#detail_image_container').html('<img src="uploads/' + data.dokumentasi + '" class="img-fluid rounded-3 border mt-2" style="max-height: 350px;" alt="Dokumentasi">');
                        } else {
                            $('#detail_image_container').html('<span class="text-muted small">Tidak ada lampiran foto</span>');
                        }

                        $('#detailJurnalModal').modal('show');
                    }
                });
            });

            // Handle Edit Click
            $('.btn-edit').on('click', function() {
                var id = $(this).data('id');
                $.getJSON('index.php?route=jurnal/detail&id=' + id, function(response) {
                    if (response.status === 'success') {
                        var data = response.data;
                        $('#edit_id').val(data.id);
                        $('#edit_tanggal').val(data.tanggal);
                        $('#edit_unit_divisi').val(data.unit_divisi);
                        $('#edit_nama_pekerjaan').val(data.nama_pekerjaan);
                        $('#edit_jam_mulai').val(data.jam_mulai.substring(0, 5));
                        $('#edit_jam_selesai').val(data.jam_selesai.substring(0, 5));
                        $('#edit_status').val(data.status);
                        $('#edit_catatan').val(data.catatan);

                        if (data.dokumentasi) {
                            $('#edit_dokumentasi_preview').html('<div class="small mb-1 text-muted">File saat ini:</div><img src="uploads/' + data.dokumentasi + '" class="img-thumbnail rounded" style="max-height: 100px;">');
                        } else {
                            $('#edit_dokumentasi_preview').empty();
                        }

                        $('#editJurnalModal').modal('show');
                    }
                });
            });
        });
    </script>
</body>
</html>
