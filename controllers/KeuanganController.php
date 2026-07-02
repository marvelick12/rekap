<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/date_helper.php';

class KeuanganController {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function index() {
        require_login();
        $user_id = get_user_id();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store($user_id);
            header('Location: index.php?route=keuangan');
            exit;
        }

        $today = date('Y-m-d');
        $month = date('m');
        $year = date('Y');

        $stmt = $this->db->prepare('SELECT SUM(nominal) AS total FROM keuangan WHERE user_id = ? AND jenis = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?');
        $stmt->execute([$user_id, 'Pemasukan', $month, $year]);
        $pemasukan = (float)($stmt->fetch()['total'] ?? 0);

        $stmt = $this->db->prepare('SELECT SUM(nominal) AS total FROM keuangan WHERE user_id = ? AND jenis = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?');
        $stmt->execute([$user_id, 'Pengeluaran', $month, $year]);
        $pengeluaran = (float)($stmt->fetch()['total'] ?? 0);

        $stmt = $this->db->prepare('SELECT * FROM keuangan WHERE user_id = ? ORDER BY tanggal DESC, id DESC LIMIT 10');
        $stmt->execute([$user_id]);
        $riwayat = $stmt->fetchAll();

        $stmt = $this->db->prepare('SELECT * FROM keuangan WHERE user_id = ? AND jenis = ? ORDER BY tanggal DESC, id DESC LIMIT 5');
        $stmt->execute([$user_id, 'Pemasukan']);
        $riwayatMasuk = $stmt->fetchAll();

        $stmt = $this->db->prepare('SELECT * FROM keuangan WHERE user_id = ? AND jenis = ? ORDER BY tanggal DESC, id DESC LIMIT 5');
        $stmt->execute([$user_id, 'Pengeluaran']);
        $riwayatKeluar = $stmt->fetchAll();

        require_once __DIR__ . '/../views/keuangan.php';
    }

    private function store($user_id) {
        $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
        $jenis = $_POST['jenis'] ?? 'Pemasukan';
        $kategori = trim($_POST['kategori'] ?? '');
        $nominal = (float)($_POST['nominal'] ?? 0);
        $keterangan = trim($_POST['keterangan'] ?? '');

        if ($kategori !== '' && $nominal > 0) {
            $stmt = $this->db->prepare('INSERT INTO keuangan (user_id, tanggal, jenis, kategori, nominal, keterangan) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$user_id, $tanggal, $jenis, $kategori, $nominal, $keterangan]);
        }
    }
}
