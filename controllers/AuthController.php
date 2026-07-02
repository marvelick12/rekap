<?php
// Auth Controller

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->userModel = new UserModel($db);
    }

    public function login() {
        require_guest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Email dan Password wajib diisi.';
                header("Location: login.php");
                exit;
            }

            $user = $this->userModel->login($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_division'] = $user['division'];
                
                $_SESSION['success'] = 'Login berhasil! Selamat datang kembali, ' . $user['name'] . '.';
                header("Location: index.php?route=dashboard");
                exit;
            } else {
                $_SESSION['error'] = 'Email atau Password salah.';
                header("Location: login.php");
                exit;
            }
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        require_guest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars(trim($_POST['name'] ?? ''));
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $division = htmlspecialchars(trim($_POST['division'] ?? ''));

            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['error'] = 'Nama, Email, dan Password wajib diisi.';
                header("Location: index.php?route=register");
                exit;
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Password minimal harus 6 karakter.';
                header("Location: index.php?route=register");
                exit;
            }

            if ($this->userModel->emailExists($email)) {
                $_SESSION['error'] = 'Email sudah digunakan.';
                header("Location: index.php?route=register");
                exit;
            }

            if ($this->userModel->register($name, $email, $password, $division)) {
                $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
                header("Location: login.php");
                exit;
            } else {
                $_SESSION['error'] = 'Terjadi kesalahan saat registrasi.';
                header("Location: index.php?route=register");
                exit;
            }
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        
        session_start();
        $_SESSION['success'] = 'Anda telah berhasil logout.';
        header("Location: login.php");
        exit;
    }
}
