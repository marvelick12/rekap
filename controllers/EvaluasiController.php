<?php
// Evaluasi Controller

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/EvaluasiModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/date_helper.php';

class EvaluasiController {
    private $evaluasiModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->evaluasiModel = new EvaluasiModel($db);
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

        $evaluasis = $this->evaluasiModel->getAll($user_id, $filters);

        require_once __DIR__ . '/../views/evaluasi/index.php';
    }

    public function store() {
        require_login();
        $user_id = get_user_id();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
            $berjalan_baik = htmlspecialchars(trim($_POST['berjalan_baik'] ?? ''));
            $kendala = htmlspecialchars(trim($_POST['kendala'] ?? ''));
            $solusi = htmlspecialchars(trim($_POST['solusi'] ?? ''));
            $perlu_diperbaiki = htmlspecialchars(trim($_POST['perlu_diperbaiki'] ?? ''));
            $target_besok = htmlspecialchars(trim($_POST['target_besok'] ?? ''));
            $catatan_tambahan = htmlspecialchars(trim($_POST['catatan_tambahan'] ?? ''));

            $data = [
                'tanggal' => $tanggal,
                'berjalan_baik' => $berjalan_baik,
                'kendala' => $kendala,
                'solusi' => $solusi,
                'perlu_diperbaiki' => $perlu_diperbaiki,
                'target_besok' => $target_besok,
                'catatan_tambahan' => $catatan_tambahan
            ];

            if ($this->evaluasiModel->create($user_id, $data)) {
                $_SESSION['success'] = 'Evaluasi harian berhasil ditambahkan.';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan evaluasi harian.';
            }
        }
        header("Location: index.php?route=evaluasi");
        exit;
    }

    public function update() {
        require_login();
        $user_id = get_user_id();
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
            $berjalan_baik = htmlspecialchars(trim($_POST['berjalan_baik'] ?? ''));
            $kendala = htmlspecialchars(trim($_POST['kendala'] ?? ''));
            $solusi = htmlspecialchars(trim($_POST['solusi'] ?? ''));
            $perlu_diperbaiki = htmlspecialchars(trim($_POST['perlu_diperbaiki'] ?? ''));
            $target_besok = htmlspecialchars(trim($_POST['target_besok'] ?? ''));
            $catatan_tambahan = htmlspecialchars(trim($_POST['catatan_tambahan'] ?? ''));

            $data = [
                'tanggal' => $tanggal,
                'berjalan_baik' => $berjalan_baik,
                'kendala' => $kendala,
                'solusi' => $solusi,
                'perlu_diperbaiki' => $perlu_diperbaiki,
                'target_besok' => $target_besok,
                'catatan_tambahan' => $catatan_tambahan
            ];

            if ($this->evaluasiModel->update($id, $user_id, $data)) {
                $_SESSION['success'] = 'Evaluasi harian berhasil diperbarui.';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui evaluasi harian.';
            }
        }
        header("Location: index.php?route=evaluasi");
        exit;
    }

    public function delete() {
        require_login();
        $user_id = get_user_id();
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if ($this->evaluasiModel->delete($id, $user_id)) {
                $_SESSION['success'] = 'Evaluasi harian berhasil dihapus.';
            } else {
                $_SESSION['error'] = 'Gagal menghapus evaluasi harian.';
            }
        }
        header("Location: index.php?route=evaluasi");
        exit;
    }

    public function detail_json() {
        require_login();
        $user_id = get_user_id();
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($id) {
            $evaluasi = $this->evaluasiModel->getById($id, $user_id);
            if ($evaluasi) {
                $evaluasi['formatted_date'] = format_indo_date($evaluasi['tanggal']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'data' => $evaluasi]);
                exit;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        exit;
    }
}
