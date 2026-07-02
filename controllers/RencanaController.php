<?php
// Rencana Controller

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/RencanaModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/date_helper.php';

class RencanaController {
    private $rencanaModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->rencanaModel = new RencanaModel($db);
    }

    public function index() {
        require_login();
        $user_id = get_user_id();

        // Get filter inputs
        $search = htmlspecialchars(trim($_GET['search'] ?? ''));
        $bulan = htmlspecialchars(trim($_GET['bulan'] ?? ''));
        $tahun = htmlspecialchars(trim($_GET['tahun'] ?? ''));
        $sort = htmlspecialchars(trim($_GET['sort'] ?? 'tanggal'));
        $order = htmlspecialchars(trim($_GET['order'] ?? 'DESC'));

        $filters = [
            'search' => $search,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'sort' => $sort,
            'order' => $order
        ];

        $rencanas = $this->rencanaModel->getAll($user_id, $filters);
        
        // Calculate progress percentage
        $progress = $this->rencanaModel->getProgressToday($user_id);

        require_once __DIR__ . '/../views/rencana/index.php';
    }

    public function store() {
        require_login();
        $user_id = get_user_id();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
            $nama_project = htmlspecialchars(trim($_POST['nama_project'] ?? ''));
            $target_pekerjaan = htmlspecialchars(trim($_POST['target_pekerjaan'] ?? ''));
            $catatan = htmlspecialchars(trim($_POST['catatan'] ?? ''));
            $status = isset($_POST['status']) ? 1 : 0;

            $hari = get_indo_day($tanggal);
            $bulan = date('n', strtotime($tanggal));
            $tahun = date('Y', strtotime($tanggal));

            $data = [
                'tanggal' => $tanggal,
                'hari' => $hari,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'nama_project' => $nama_project,
                'target_pekerjaan' => $target_pekerjaan,
                'catatan' => $catatan,
                'status' => $status
            ];

            if ($this->rencanaModel->create($user_id, $data)) {
                $_SESSION['success'] = 'Rencana pekerjaan berhasil ditambahkan.';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan rencana pekerjaan.';
            }
        }
        header("Location: index.php?route=rencana");
        exit;
    }

    public function update() {
        require_login();
        $user_id = get_user_id();
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
            $nama_project = htmlspecialchars(trim($_POST['nama_project'] ?? ''));
            $target_pekerjaan = htmlspecialchars(trim($_POST['target_pekerjaan'] ?? ''));
            $catatan = htmlspecialchars(trim($_POST['catatan'] ?? ''));
            $status = isset($_POST['status']) ? 1 : 0;

            $hari = get_indo_day($tanggal);
            $bulan = date('n', strtotime($tanggal));
            $tahun = date('Y', strtotime($tanggal));

            $data = [
                'tanggal' => $tanggal,
                'hari' => $hari,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'nama_project' => $nama_project,
                'target_pekerjaan' => $target_pekerjaan,
                'catatan' => $catatan,
                'status' => $status
            ];

            if ($this->rencanaModel->update($id, $user_id, $data)) {
                $_SESSION['success'] = 'Rencana pekerjaan berhasil diperbarui.';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui rencana pekerjaan.';
            }
        }
        header("Location: index.php?route=rencana");
        exit;
    }

    public function delete() {
        require_login();
        $user_id = get_user_id();
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if ($this->rencanaModel->delete($id, $user_id)) {
                $_SESSION['success'] = 'Rencana pekerjaan berhasil dihapus.';
            } else {
                $_SESSION['error'] = 'Gagal menghapus rencana pekerjaan.';
            }
        }
        header("Location: index.php?route=rencana");
        exit;
    }

    public function toggle_status() {
        require_login();
        $user_id = get_user_id();
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);

        if ($id !== false && $status !== false) {
            if ($this->rencanaModel->toggleStatus($id, $user_id, $status)) {
                // Return fresh progress statistics for updates
                $progress = $this->rencanaModel->getProgressToday($user_id);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Status berhasil diperbarui',
                    'progress' => $progress
                ]);
                exit;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah status']);
        exit;
    }

    public function detail_json() {
        require_login();
        $user_id = get_user_id();
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($id) {
            $rencana = $this->rencanaModel->getById($id, $user_id);
            if ($rencana) {
                $rencana['formatted_date'] = format_indo_date($rencana['tanggal']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'data' => $rencana]);
                exit;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        exit;
    }
}
