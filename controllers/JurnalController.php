<?php
// Jurnal Controller

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/JurnalModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/date_helper.php';

class JurnalController {
    private $jurnalModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->jurnalModel = new JurnalModel($db);
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

        $jurnals = $this->jurnalModel->getAll($user_id, $filters);

        require_once __DIR__ . '/../views/jurnal/index.php';
    }

    public function store() {
        require_login();
        $user_id = get_user_id();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
            $unit_divisi = htmlspecialchars(trim($_POST['unit_divisi'] ?? ''));
            $nama_pekerjaan = htmlspecialchars(trim($_POST['nama_pekerjaan'] ?? ''));
            $catatan = htmlspecialchars(trim($_POST['catatan'] ?? ''));
            $jam_mulai = $_POST['jam_mulai'] ?? '08:00';
            $jam_selesai = $_POST['jam_selesai'] ?? '17:00';
            $status = htmlspecialchars(trim($_POST['status'] ?? 'Selesai'));

            // Calculate Day, Month, Year
            $hari = get_indo_day($tanggal);
            $bulan = date('n', strtotime($tanggal)); // numeric month for sorting/filtering
            $tahun = date('Y', strtotime($tanggal));

            // File Upload
            $dokumentasi = null;
            if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['dokumentasi']['tmp_name'];
                $fileName = $_FILES['dokumentasi']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $uploadFileDir = __DIR__ . '/../uploads/';
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }
                    $newFileName = time() . '_' . md5(rand()) . '.' . $fileExtension;
                    $dest_path = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $dokumentasi = $newFileName;
                    }
                }
            }

            $data = [
                'tanggal' => $tanggal,
                'hari' => $hari,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'unit_divisi' => $unit_divisi,
                'nama_pekerjaan' => $nama_pekerjaan,
                'catatan' => $catatan,
                'dokumentasi' => $dokumentasi,
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai,
                'status' => $status
            ];

            if ($this->jurnalModel->create($user_id, $data)) {
                $_SESSION['success'] = 'Jurnal harian berhasil ditambahkan.';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan jurnal harian.';
            }
        }
        header("Location: index.php?route=jurnal");
        exit;
    }

    public function update() {
        require_login();
        $user_id = get_user_id();
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
            $unit_divisi = htmlspecialchars(trim($_POST['unit_divisi'] ?? ''));
            $nama_pekerjaan = htmlspecialchars(trim($_POST['nama_pekerjaan'] ?? ''));
            $catatan = htmlspecialchars(trim($_POST['catatan'] ?? ''));
            $jam_mulai = $_POST['jam_mulai'] ?? '08:00';
            $jam_selesai = $_POST['jam_selesai'] ?? '17:00';
            $status = htmlspecialchars(trim($_POST['status'] ?? 'Selesai'));

            $hari = get_indo_day($tanggal);
            $bulan = date('n', strtotime($tanggal));
            $tahun = date('Y', strtotime($tanggal));

            $data = [
                'tanggal' => $tanggal,
                'hari' => $hari,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'unit_divisi' => $unit_divisi,
                'nama_pekerjaan' => $nama_pekerjaan,
                'catatan' => $catatan,
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai,
                'status' => $status
            ];

            // File Upload
            if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['dokumentasi']['tmp_name'];
                $fileName = $_FILES['dokumentasi']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $uploadFileDir = __DIR__ . '/../uploads/';
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }
                    $newFileName = time() . '_' . md5(rand()) . '.' . $fileExtension;
                    $dest_path = $uploadFileDir . $newFileName;

                    // Delete old file if exists
                    $oldJurnal = $this->jurnalModel->getById($id, $user_id);
                    if ($oldJurnal && !empty($oldJurnal['dokumentasi'])) {
                        $oldFilePath = $uploadFileDir . $oldJurnal['dokumentasi'];
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $data['dokumentasi'] = $newFileName;
                    }
                }
            }

            if ($this->jurnalModel->update($id, $user_id, $data)) {
                $_SESSION['success'] = 'Jurnal harian berhasil diperbarui.';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui jurnal harian.';
            }
        }
        header("Location: index.php?route=jurnal");
        exit;
    }

    public function delete() {
        require_login();
        $user_id = get_user_id();
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            // Delete attachment file first
            $jurnal = $this->jurnalModel->getById($id, $user_id);
            if ($jurnal && !empty($jurnal['dokumentasi'])) {
                $filePath = __DIR__ . '/../uploads/' . $jurnal['dokumentasi'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            if ($this->jurnalModel->delete($id, $user_id)) {
                $_SESSION['success'] = 'Jurnal harian berhasil dihapus.';
            } else {
                $_SESSION['error'] = 'Gagal menghapus jurnal harian.';
            }
        }
        header("Location: index.php?route=jurnal");
        exit;
    }

    public function detail_json() {
        require_login();
        $user_id = get_user_id();
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($id) {
            $jurnal = $this->jurnalModel->getById($id, $user_id);
            if ($jurnal) {
                // Add formatted date for readability
                $jurnal['formatted_date'] = format_indo_date($jurnal['tanggal']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'data' => $jurnal]);
                exit;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        exit;
    }
}
