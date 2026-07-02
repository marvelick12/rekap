<?php
// Rencana Model

class RencanaModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function create($user_id, $data) {
        $sql = "INSERT INTO rencana_pekerjaan 
                (user_id, tanggal, hari, bulan, tahun, nama_project, target_pekerjaan, catatan, status) 
                VALUES 
                (:user_id, :tanggal, :hari, :bulan, :tahun, :nama_project, :target_pekerjaan, :catatan, :status)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':tanggal' => $data['tanggal'],
            ':hari' => $data['hari'],
            ':bulan' => $data['bulan'],
            ':tahun' => $data['tahun'],
            ':nama_project' => $data['nama_project'],
            ':target_pekerjaan' => $data['target_pekerjaan'],
            ':catatan' => $data['catatan'] ?? null,
            ':status' => $data['status'] ?? 0
        ]);
    }

    public function update($id, $user_id, $data) {
        $sql = "UPDATE rencana_pekerjaan SET 
                tanggal = :tanggal,
                hari = :hari,
                bulan = :bulan,
                tahun = :tahun,
                nama_project = :nama_project,
                target_pekerjaan = :target_pekerjaan,
                catatan = :catatan,
                status = :status 
                WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tanggal' => $data['tanggal'],
            ':hari' => $data['hari'],
            ':bulan' => $data['bulan'],
            ':tahun' => $data['tahun'],
            ':nama_project' => $data['nama_project'],
            ':target_pekerjaan' => $data['target_pekerjaan'],
            ':catatan' => $data['catatan'] ?? null,
            ':status' => $data['status'] ?? 0,
            ':id' => $id,
            ':user_id' => $user_id
        ]);
    }

    public function delete($id, $user_id) {
        $sql = "DELETE FROM rencana_pekerjaan WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $user_id]);
    }

    public function getById($id, $user_id) {
        $sql = "SELECT * FROM rencana_pekerjaan WHERE id = :id AND user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':user_id' => $user_id]);
        return $stmt->fetch();
    }

    public function getAll($user_id, $filters = []) {
        $sql = "SELECT * FROM rencana_pekerjaan WHERE user_id = :user_id";
        $params = [':user_id' => $user_id];

        if (!empty($filters['search'])) {
            $sql .= " AND (nama_project LIKE :search OR target_pekerjaan LIKE :search OR catatan LIKE :search)";
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
        
        $allowed_sort = ['tanggal', 'nama_project', 'target_pekerjaan', 'status'];
        if (!in_array($sort, $allowed_sort)) {
            $sort = 'tanggal';
        }
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        $sql .= " ORDER BY $sort $order";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function toggleStatus($id, $user_id, $status) {
        $sql = "UPDATE rencana_pekerjaan SET status = :status WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status' => (int)$status,
            ':id' => $id,
            ':user_id' => $user_id
        ]);
    }

    public function getProgressToday($user_id) {
        $sql = "SELECT 
                COUNT(*) as total, 
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as selesai 
                FROM rencana_pekerjaan 
                WHERE user_id = :user_id AND tanggal = CURDATE()";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $row = $stmt->fetch();
        
        $total = (int)$row['total'];
        $selesai = (int)($row['selesai'] ?? 0);
        $persen = $total > 0 ? round(($selesai / $total) * 100) : 0;
        
        return [
            'total' => $total,
            'selesai' => $selesai,
            'persen' => $persen
        ];
    }

    public function getProgressWeekly($user_id) {
        // Calculate progress for the last 7 days
        $sql = "SELECT 
                COUNT(*) as total, 
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as selesai 
                FROM rencana_pekerjaan 
                WHERE user_id = :user_id AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $row = $stmt->fetch();
        
        $total = (int)$row['total'];
        $selesai = (int)($row['selesai'] ?? 0);
        $persen = $total > 0 ? round(($selesai / $total) * 100) : 0;
        
        return [
            'total' => $total,
            'selesai' => $selesai,
            'persen' => $persen
        ];
    }

    public function getProgressMonthly($user_id) {
        // Calculate progress for the current month
        $sql = "SELECT 
                COUNT(*) as total, 
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as selesai 
                FROM rencana_pekerjaan 
                WHERE user_id = :user_id AND MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $row = $stmt->fetch();
        
        $total = (int)$row['total'];
        $selesai = (int)($row['selesai'] ?? 0);
        $persen = $total > 0 ? round(($selesai / $total) * 100) : 0;
        
        return [
            'total' => $total,
            'selesai' => $selesai,
            'persen' => $persen
        ];
    }

    public function getCountToday($user_id) {
        $sql = "SELECT COUNT(*) FROM rencana_pekerjaan WHERE user_id = :user_id AND tanggal = CURDATE()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return (int)$stmt->fetchColumn();
    }

    public function getCompletedToday($user_id) {
        $sql = "SELECT COUNT(*) FROM rencana_pekerjaan WHERE user_id = :user_id AND tanggal = CURDATE() AND status = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return (int)$stmt->fetchColumn();
    }

    public function getUncompletedToday($user_id) {
        $sql = "SELECT COUNT(*) FROM rencana_pekerjaan WHERE user_id = :user_id AND tanggal = CURDATE() AND status = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return (int)$stmt->fetchColumn();
    }
}
