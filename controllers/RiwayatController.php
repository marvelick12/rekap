<?php
// Riwayat Controller

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/JurnalModel.php';
require_once __DIR__ . '/../models/RencanaModel.php';
require_once __DIR__ . '/../models/EvaluasiModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/date_helper.php';

class RiwayatController {
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

        // Default to current week if not set
        $start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
        $end_date = $_GET['end_date'] ?? date('Y-m-d');

        $filters = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'sort' => 'tanggal',
            'order' => 'ASC' // Ascending order is usually better for sequential history reading
        ];

        // Fetch data
        $jurnals = $this->jurnalModel->getAll($user_id, $filters);
        $rencanas = $this->rencanaModel->getAll($user_id, $filters);
        $evaluasis = $this->evaluasiModel->getAll($user_id, $filters);

        // Group data by Date to make it super easy for boss/reports reading
        $grouped_data = [];
        
        // Loop range of dates between start and end
        $current = strtotime($start_date);
        $last = strtotime($end_date);
        while ($current <= $last) {
            $date_str = date('Y-m-d', $current);
            $grouped_data[$date_str] = [
                'jurnal' => [],
                'rencana' => [],
                'evaluasi' => null
            ];
            $current = strtotime('+1 day', $current);
        }

        foreach ($jurnals as $j) {
            if (isset($grouped_data[$j['tanggal']])) {
                $grouped_data[$j['tanggal']]['jurnal'][] = $j;
            }
        }
        foreach ($rencanas as $r) {
            if (isset($grouped_data[$r['tanggal']])) {
                $grouped_data[$r['tanggal']]['rencana'][] = $r;
            }
        }
        foreach ($evaluasis as $e) {
            if (isset($grouped_data[$e['tanggal']])) {
                $grouped_data[$e['tanggal']]['evaluasi'] = $e;
            }
        }

        // Filter out dates that have no activity to make the report cleaner
        foreach ($grouped_data as $date => $data) {
            if (empty($data['jurnal']) && empty($data['rencana']) && is_null($data['evaluasi'])) {
                unset($grouped_data[$date]);
            }
        }

        require_once __DIR__ . '/../views/riwayat.php';
    }
}
