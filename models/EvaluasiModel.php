<?php
// Evaluasi Model

class EvaluasiModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function create($user_id, $data) {
        $sql = "INSERT INTO evaluasi_harian 
                (user_id, tanggal, berjalan_baik, kendala, solusi, perlu_diperbaiki, target_besok, catatan_tambahan) 
                VALUES 
                (:user_id, :tanggal, :berjalan_baik, :kendala, :solusi, :perlu_diperbaiki, :target_besok, :catatan_tambahan)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':tanggal' => $data['tanggal'],
            ':berjalan_baik' => $data['berjalan_baik'] ?? null,
            ':kendala' => $data['kendala'] ?? null,
            ':solusi' => $data['solusi'] ?? null,
            ':perlu_diperbaiki' => $data['perlu_diperbaiki'] ?? null,
            ':target_besok' => $data['target_besok'] ?? null,
            ':catatan_tambahan' => $data['catatan_tambahan'] ?? null
        ]);
    }

    public function update($id, $user_id, $data) {
        $sql = "UPDATE evaluasi_harian SET 
                tanggal = :tanggal,
                berjalan_baik = :berjalan_baik,
                kendala = :kendala,
                solusi = :solusi,
                perlu_diperbaiki = :perlu_diperbaiki,
                target_besok = :target_besok,
                catatan_tambahan = :catatan_tambahan 
                WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tanggal' => $data['tanggal'],
            ':berjalan_baik' => $data['berjalan_baik'] ?? null,
            ':kendala' => $data['kendala'] ?? null,
            ':solusi' => $data['solusi'] ?? null,
            ':perlu_diperbaiki' => $data['perlu_diperbaiki'] ?? null,
            ':target_besok' => $data['target_besok'] ?? null,
            ':catatan_tambahan' => $data['catatan_tambahan'] ?? null,
            ':id' => $id,
            ':user_id' => $user_id
        ]);
    }

    public function delete($id, $user_id) {
        $sql = "DELETE FROM evaluasi_harian WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $user_id]);
    }

    public function getById($id, $user_id) {
        $sql = "SELECT * FROM evaluasi_harian WHERE id = :id AND user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':user_id' => $user_id]);
        return $stmt->fetch();
    }

    public function getAll($user_id, $filters = []) {
        $sql = "SELECT * FROM evaluasi_harian WHERE user_id = :user_id";
        $params = [':user_id' => $user_id];

        if (!empty($filters['search'])) {
            $sql .= " AND (kendala LIKE :search OR solusi LIKE :search OR berjalan_baik LIKE :search OR target_besok LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['bulan'])) {
            $sql .= " AND MONTH(tanggal) = :bulan";
            $params[':bulan'] = (int)$filters['bulan'];
        }

        if (!empty($filters['tahun'])) {
            $sql .= " AND YEAR(tanggal) = :tahun";
            $params[':tahun'] = (int)$filters['tahun'];
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $sql .= " AND tanggal BETWEEN :start_date AND :end_date";
            $params[':start_date'] = $filters['start_date'];
            $params[':end_date'] = $filters['end_date'];
        }

        $sort = $filters['sort'] ?? 'tanggal';
        $order = $filters['order'] ?? 'DESC';
        
        $allowed_sort = ['tanggal', 'kendala', 'solusi', 'target_besok'];
        if (!in_array($sort, $allowed_sort)) {
            $sort = 'tanggal';
        }
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        $sql .= " ORDER BY $sort $order";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getLatest($user_id) {
        $sql = "SELECT * FROM evaluasi_harian WHERE user_id = :user_id ORDER BY tanggal DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch();
    }

    public function getCountThisMonth($user_id) {
        $sql = "SELECT COUNT(*) FROM evaluasi_harian 
                WHERE user_id = :user_id 
                AND MONTH(tanggal) = MONTH(CURDATE()) 
                AND YEAR(tanggal) = YEAR(CURDATE())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return (int)$stmt->fetchColumn();
    }
}
