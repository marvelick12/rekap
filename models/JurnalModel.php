<?php
// Jurnal Model

class JurnalModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function create($user_id, $data) {
        $sql = "INSERT INTO jurnal_harian 
                (user_id, tanggal, hari, bulan, tahun, unit_divisi, nama_pekerjaan, catatan, dokumentasi, jam_mulai, jam_selesai, status) 
                VALUES 
                (:user_id, :tanggal, :hari, :bulan, :tahun, :unit_divisi, :nama_pekerjaan, :catatan, :dokumentasi, :jam_mulai, :jam_selesai, :status)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':tanggal' => $data['tanggal'],
            ':hari' => $data['hari'],
            ':bulan' => $data['bulan'],
            ':tahun' => $data['tahun'],
            ':unit_divisi' => $data['unit_divisi'],
            ':nama_pekerjaan' => $data['nama_pekerjaan'],
            ':catatan' => $data['catatan'] ?? null,
            ':dokumentasi' => $data['dokumentasi'] ?? null,
            ':jam_mulai' => $data['jam_mulai'],
            ':jam_selesai' => $data['jam_selesai'],
            ':status' => $data['status']
        ]);
    }

    public function update($id, $user_id, $data) {
        $sql = "UPDATE jurnal_harian SET 
                tanggal = :tanggal,
                hari = :hari,
                bulan = :bulan,
                tahun = :tahun,
                unit_divisi = :unit_divisi,
                nama_pekerjaan = :nama_pekerjaan,
                catatan = :catatan,
                jam_mulai = :jam_mulai,
                jam_selesai = :jam_selesai,
                status = :status" . 
                (!empty($data['dokumentasi']) ? ", dokumentasi = :dokumentasi" : "") . 
                " WHERE id = :id AND user_id = :user_id";
        
        $params = [
            ':tanggal' => $data['tanggal'],
            ':hari' => $data['hari'],
            ':bulan' => $data['bulan'],
            ':tahun' => $data['tahun'],
            ':unit_divisi' => $data['unit_divisi'],
            ':nama_pekerjaan' => $data['nama_pekerjaan'],
            ':catatan' => $data['catatan'] ?? null,
            ':jam_mulai' => $data['jam_mulai'],
            ':jam_selesai' => $data['jam_selesai'],
            ':status' => $data['status'],
            ':id' => $id,
            ':user_id' => $user_id
        ];

        if (!empty($data['dokumentasi'])) {
            $params[':dokumentasi'] = $data['dokumentasi'];
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id, $user_id) {
        $sql = "DELETE FROM jurnal_harian WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $user_id]);
    }

    public function getById($id, $user_id) {
        $sql = "SELECT * FROM jurnal_harian WHERE id = :id AND user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':user_id' => $user_id]);
        return $stmt->fetch();
    }

    public function getAll($user_id, $filters = []) {
        $sql = "SELECT * FROM jurnal_harian WHERE user_id = :user_id";
        $params = [':user_id' => $user_id];

        if (!empty($filters['search'])) {
            $sql .= " AND (nama_pekerjaan LIKE :search OR unit_divisi LIKE :search OR catatan LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['bulan'])) {
            $sql .= " AND (bulan = :bulan OR MONTH(tanggal) = :bulan_num)";
            $params[':bulan'] = $filters['bulan'];
            $params[':bulan_num'] = (int)$filters['bulan'];
        }

        if (!empty($filters['tahun'])) {
            $sql .= " AND tahun = :tahun";
            $params[':tahun'] = $filters['tahun'];
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $sql .= " AND tanggal BETWEEN :start_date AND :end_date";
            $params[':start_date'] = $filters['start_date'];
            $params[':end_date'] = $filters['end_date'];
        }

        $sort = $filters['sort'] ?? 'tanggal';
        $order = $filters['order'] ?? 'DESC';
        
        // Sanitize sorting field to avoid SQL injection
        $allowed_sort = ['tanggal', 'unit_divisi', 'nama_pekerjaan', 'status'];
        if (!in_array($sort, $allowed_sort)) {
            $sort = 'tanggal';
        }
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        $sql .= " ORDER BY $sort $order";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getCountToday($user_id) {
        $sql = "SELECT COUNT(*) FROM jurnal_harian WHERE user_id = :user_id AND tanggal = CURDATE()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return (int)$stmt->fetchColumn();
    }

    public function getCompletedToday($user_id) {
        $sql = "SELECT COUNT(*) FROM jurnal_harian WHERE user_id = :user_id AND tanggal = CURDATE() AND status = 'Selesai'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return (int)$stmt->fetchColumn();
    }

    public function getUncompletedToday($user_id) {
        $sql = "SELECT COUNT(*) FROM jurnal_harian WHERE user_id = :user_id AND tanggal = CURDATE() AND status != 'Selesai'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return (int)$stmt->fetchColumn();
    }
}
