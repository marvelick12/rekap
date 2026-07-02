<?php
// Dashboard Controller

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/JurnalModel.php';
require_once __DIR__ . '/../models/RencanaModel.php';
require_once __DIR__ . '/../models/EvaluasiModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class DashboardController {
    private $jurnalModel;
    private $rencanaModel;
    private $evaluasiModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->jurnalModel = new JurnalModel($db);
        $this->rencanaModel = new RencanaModel($db);
        $this->evaluasiModel = new EvaluasiModel($db);
    }

    public function index() {
        require_login();
        $user_id = get_user_id();

        // Standard Dashboard stats
        $totalJurnalHariIni = $this->jurnalModel->getCountToday($user_id);
        $totalRencanaHariIni = $this->rencanaModel->getCountToday($user_id);
        
        $totalPekerjaanSelesai = $this->rencanaModel->getCompletedToday($user_id);
        $totalPekerjaanBelumSelesai = $this->rencanaModel->getUncompletedToday($user_id);
        
        $totalEvaluasiBulanIni = $this->evaluasiModel->getCountThisMonth($user_id);

        // Widgets / Additional Dashboard items
        $aktivitasHariIni = $this->jurnalModel->getAll($user_id, [
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d')
        ]);
        
        $rencanaHariIni = $this->rencanaModel->getAll($user_id, [
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d')
        ]);

        $evaluasiTerakhir = $this->evaluasiModel->getLatest($user_id);

        // Progress Weekly & Monthly
        $progressMingguan = $this->rencanaModel->getProgressWeekly($user_id);
        $progressBulanan = $this->rencanaModel->getProgressMonthly($user_id);
        $progressHariIni = $this->rencanaModel->getProgressToday($user_id);

        require_once __DIR__ . '/../views/dashboard.php';
    }
}
