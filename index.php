<?php
// index.php - Front Controller / Router

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$route = $_GET['route'] ?? 'dashboard';

switch ($route) {
    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        (new DashboardController())->index();
        break;

    case 'jurnal':
        require_once __DIR__ . '/controllers/JurnalController.php';
        (new JurnalController())->index();
        break;

    case 'jurnal/store':
        require_once __DIR__ . '/controllers/JurnalController.php';
        (new JurnalController())->store();
        break;

    case 'jurnal/update':
        require_once __DIR__ . '/controllers/JurnalController.php';
        (new JurnalController())->update();
        break;

    case 'jurnal/delete':
        require_once __DIR__ . '/controllers/JurnalController.php';
        (new JurnalController())->delete();
        break;

    case 'jurnal/detail':
        require_once __DIR__ . '/controllers/JurnalController.php';
        (new JurnalController())->detail_json();
        break;

    case 'rencana':
        require_once __DIR__ . '/controllers/RencanaController.php';
        (new RencanaController())->index();
        break;

    case 'rencana/store':
        require_once __DIR__ . '/controllers/RencanaController.php';
        (new RencanaController())->store();
        break;

    case 'rencana/update':
        require_once __DIR__ . '/controllers/RencanaController.php';
        (new RencanaController())->update();
        break;

    case 'rencana/delete':
        require_once __DIR__ . '/controllers/RencanaController.php';
        (new RencanaController())->delete();
        break;

    case 'rencana/toggle':
        require_once __DIR__ . '/controllers/RencanaController.php';
        (new RencanaController())->toggle_status();
        break;

    case 'rencana/detail':
        require_once __DIR__ . '/controllers/RencanaController.php';
        (new RencanaController())->detail_json();
        break;

    case 'evaluasi':
        require_once __DIR__ . '/controllers/EvaluasiController.php';
        (new EvaluasiController())->index();
        break;

    case 'evaluasi/store':
        require_once __DIR__ . '/controllers/EvaluasiController.php';
        (new EvaluasiController())->store();
        break;

    case 'evaluasi/update':
        require_once __DIR__ . '/controllers/EvaluasiController.php';
        (new EvaluasiController())->update();
        break;

    case 'evaluasi/delete':
        require_once __DIR__ . '/controllers/EvaluasiController.php';
        (new EvaluasiController())->delete();
        break;

    case 'evaluasi/detail':
        require_once __DIR__ . '/controllers/EvaluasiController.php';
        (new EvaluasiController())->detail_json();
        break;

    case 'profile':
        require_once __DIR__ . '/controllers/ProfileController.php';
        (new ProfileController())->index();
        break;

    case 'profile/update':
        require_once __DIR__ . '/controllers/ProfileController.php';
        (new ProfileController())->update();
        break;

    case 'riwayat':
        require_once __DIR__ . '/controllers/RiwayatController.php';
        (new RiwayatController())->index();
        break;

    case 'keuangan':
        require_once __DIR__ . '/controllers/KeuanganController.php';
        (new KeuanganController())->index();
        break;

    case 'profile':
        require_once __DIR__ . '/controllers/ProfileController.php';
        (new ProfileController())->index();
        break;

    case 'register':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->register();
        break;

    case 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    default:
        // Page not found
        http_response_code(404);
        echo "<h1>404 Halaman Tidak Ditemukan</h1>";
        break;
}
