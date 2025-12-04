<?php
session_start();

class LoginController {

    public function loginPage() {
        include __DIR__ . '/../views/login.php';
    }

    public function loginProcess() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // sementara (dummy user)
        $validUser = "admin";
        $validPass = "123";

        if ($username === $validUser && $password === $validPass) {
            $_SESSION['admin_logged_in'] = true;

            header("Location: ../index.php?action=home");
            exit;
        }

        // gagal login → kembali ke login
        header("Location: ../index.php?action=login&error=1");
        exit;
    }

    public function logout() {
        session_destroy();
        header("Location: ../index.php?action=login");
        exit;
    }
}
