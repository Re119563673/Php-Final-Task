<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/PasswordEncryption.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Here you find register a new user
    public function register($username, $plainPassword) {
        $sql = "INSERT INTO " . $this->table . " (username, password_hash, encryption_key) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        $key = bin2hex(random_bytes(16)); // generate a 128-bit encryption key
        $encryptedKey = PasswordEncryption::encryptKey($key, $plainPassword); // encrypt key with password

        if ($stmt->execute([$username, $hashedPassword, $encryptedKey])) {
            return true;
        }
        return false;
    }

    // Login user
    public function login($username, $plainPassword) {
        $sql = "SELECT * FROM " . $this->table . " WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($plainPassword, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['encryption_key'] = PasswordEncryption::decryptKey($user['encryption_key'], $plainPassword);
                return true;
            }
        }
        return false;
    }

    // Logout user
    public function logout() {
        session_unset();
        session_destroy();
    }

    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

