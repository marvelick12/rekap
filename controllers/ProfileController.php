<?php
// Profile Controller

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/date_helper.php';

class ProfileController {
    private $userModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->userModel = new UserModel($db);
    }

    public function index() {
        require_login();
        $user_id = get_user_id();
        
        $user = $this->userModel->getUserById($user_id);
        
        require_once __DIR__ . '/../views/profile.php';
    }

    public function update() {
        require_login();
        $user_id = get_user_id();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars(trim($_POST['name'] ?? ''));
            $division = htmlspecialchars(trim($_POST['division'] ?? ''));
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($name)) {
                $_SESSION['error'] = 'Nama wajib diisi.';
                header("Location: index.php?route=profile");
                exit;
            }

            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $_SESSION['error'] = 'Password minimal harus 6 karakter.';
                    header("Location: index.php?route=profile");
                    exit;
                }
                if ($password !== $confirm_password) {
                    $_SESSION['error'] = 'Konfirmasi password tidak cocok.';
                    header("Location: index.php?route=profile");
                    exit;
                }
            }

            $success = $this->userModel->updateProfile($user_id, $name, $division, !empty($password) ? $password : null);
            if ($success) {
                // Sync session
                $_SESSION['user_name'] = $name;
                $_SESSION['user_division'] = $division;
                $_SESSION['success'] = 'Profil berhasil diperbarui.';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui profil.';
            }
        }
        header("Location: index.php?route=profile");
        exit;
    }
}
