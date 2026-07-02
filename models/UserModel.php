<?php
// User Model

class UserModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function register($name, $email, $password, $division) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (name, email, password, division) VALUES (:name, :email, :password, :division)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':division' => $division
        ]);
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getUserById($id) {
        $sql = "SELECT id, name, email, division, created_at FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateProfile($id, $name, $division, $password = null) {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE users SET name = :name, division = :division, password = :password WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => $name,
                ':division' => $division,
                ':password' => $hashedPassword,
                ':id' => $id
            ]);
        } else {
            $sql = "UPDATE users SET name = :name, division = :division WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => $name,
                ':division' => $division,
                ':id' => $id
            ]);
        }
    }

    public function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) FROM users WHERE email = :email AND id != :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email, ':id' => $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
        }
        return $stmt->fetchColumn() > 0;
    }
}
