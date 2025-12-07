<?php
require_once __DIR__ . '/../Database.php';

class AuthController {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
        // DEBUG: Check connection immediately
        if (!$this->conn) {
            die("FATAL ERROR: Could not connect to database.");
        }
    }

    public function login() {
        // 1. Enable Error Reporting (So you don't see a blank page)
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Get Input
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? ''; // Get PLAIN password first

        // 3. Query User by Name OR Email (Don't check password yet)
        $sql = "SELECT * FROM users WHERE name = $1 OR email = $1";
        $result = pg_query_params($this->conn, $sql, [$username]);

        if (!$result) {
            die("Query Failed: " . pg_last_error($this->conn));
        }

        $user = pg_fetch_assoc($result);

        // 4. Verify Password
        if ($user) {
            $dbPassword = $user['password'];
            $isValid = false;

            // CHECK 1: Is it MD5? (For user 'aku')
            if (md5($password) === $dbPassword) {
                $isValid = true;
            }
            // CHECK 2: Is it BCrypt? (For user 'Admin')
            // BCrypt hashes always start with '$2y$'
            else if (password_verify($password, $dbPassword)) {
                $isValid = true;
            }

            if ($isValid) {
                // SUCCESS! Set Session
                $_SESSION['username'] = $user['name'];
                $_SESSION['role']     = $user['role'];
                $_SESSION['status']   = 'login';

                // Redirect to Home
                header("Location: index.php?action=home");
                exit;
            } else {
                // Password Wrong
                echo "<script>alert('Wrong Password!'); window.location.href='views/login.php';</script>";
                exit;
            }
        } else {
            // User Not Found
            echo "<script>alert('User not found!'); window.location.href='views/login.php';</script>";
            exit;
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: views/login.php");
        exit;
    }
}