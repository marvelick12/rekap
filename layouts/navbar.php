<?php
// layouts/navbar.php
require_once __DIR__ . '/../helpers/date_helper.php';
?>
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container-fluid p-0 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <button class="navbar-toggler-btn" id="sidebarToggle" type="button" aria-label="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="m-0 fw-semibold text-capitalize d-none d-md-block" style="color: var(--text-main);">
                <?php
                $current_route = $_GET['route'] ?? 'dashboard';
                switch ($current_route) {
                    case 'dashboard':
                        echo 'Dashboard';
                        break;
                    case 'jurnal':
                        echo 'Jurnal Harian Pekerjaan';
                        break;
                    case 'rencana':
                        echo 'Rencana Pekerjaan';
                        break;
                    case 'evaluasi':
                        echo 'Evaluasi Harian & Refleksi';
                        break;
                    case 'profile':
                        echo 'Profil Pengguna';
                        break;
                    case 'riwayat':
                        echo 'Riwayat & Cetak Laporan';
                        break;
                    default:
                        echo 'Aplikasi Laporan';
                }
                ?>
            </h5>
        </div>
        
        <div class="d-flex align-items-center gap-4">
            <div class="text-end d-none d-lg-block">
                <small class="text-muted d-block fw-medium" style="font-size: 0.75rem;">Hari Ini</small>
                <span class="fw-semibold text-main" style="font-size: 0.875rem;">
                    <?= format_indo_date(date('Y-m-d')) ?>
                </span>
            </div>
            
            <div class="vr d-none d-lg-block" style="height: 2rem; background-color: var(--border-color);"></div>

            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center bg-primary-light text-primary rounded-circle fw-bold" style="width: 2.5rem; height: 2.5rem; font-size: 1rem;">
                    <?= strtoupper(substr(get_user_name(), 0, 1)) ?>
                </div>
                <div class="text-start d-none d-sm-block">
                    <span class="d-block fw-semibold text-main" style="font-size: 0.875rem; line-height: 1.2;">
                        <?= htmlspecialchars(get_user_name()) ?>
                    </span>
                    <small class="text-muted" style="font-size: 0.75rem;">
                        <?= htmlspecialchars(get_user_email()) ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</nav>
