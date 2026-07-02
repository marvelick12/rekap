<?php
// layouts/sidebar.php
$current_route = $_GET['route'] ?? 'dashboard';
?>
<aside class="sidebar-wrapper" id="sidebarWrapper">
    <div class="sidebar-brand">
        <span class="bg-primary text-white d-flex align-items-center justify-content-center rounded-3" style="width: 2.25rem; height: 2.25rem;">
            <i class="fas fa-briefcase"></i>
        </span>
        <div>
            <h6 class="m-0 fw-bold" style="letter-spacing: -0.025em;">Buku Kerja Digital</h6>
            <small class="text-muted fw-semibold" style="font-size: 0.7rem;"><?= htmlspecialchars(get_user_division() ?: 'Divisi / Unit') ?></small>
        </div>
    </div>
    
    <ul class="sidebar-menu">
        <li class="sidebar-item">
            <a href="index.php?route=dashboard" class="sidebar-link <?= $current_route === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="index.php?route=jurnal" class="sidebar-link <?= strpos($current_route, 'jurnal') === 0 ? 'active' : '' ?>">
                <i class="fas fa-edit"></i>
                <span>Jurnal Harian</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="index.php?route=rencana" class="sidebar-link <?= strpos($current_route, 'rencana') === 0 ? 'active' : '' ?>">
                <i class="fas fa-list-check"></i>
                <span>Rencana Pekerjaan</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="index.php?route=evaluasi" class="sidebar-link <?= strpos($current_route, 'evaluasi') === 0 ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i>
                <span>Evaluasi Harian</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="index.php?route=riwayat" class="sidebar-link <?= strpos($current_route, 'riwayat') === 0 ? 'active' : '' ?>">
                <i class="fas fa-file-invoice"></i>
                <span>Riwayat Laporan</span>
            </a>
        </li>

        <li class="sidebar-item my-3" style="border-top: 1px solid var(--border-color);"></li>
        
        <li class="sidebar-item">
            <a href="index.php?route=profile" class="sidebar-link <?= $current_route === 'profile' ? 'active' : '' ?>">
                <i class="fas fa-user-circle"></i>
                <span>Profil Saya</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="index.php?route=logout" class="sidebar-link text-danger">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</aside>
