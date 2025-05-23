<?php
class User {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function register($username, $plainPassword) {
        $checkStmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $checkStmt->execute([$username]);
        if ($checkStmt->fetch()) {
            return false;
        }

        $passwordHash = password_hash($plainPassword, PASSWORD_DEFAULT);
        $aesKey = openssl_encrypt("USER_SECRET_KEY", "aes-256-cbc", $plainPassword, 0, "1234567890123456");
        $stmt = $this->db->prepare("INSERT INTO users (username, password_hash, aes_key) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $passwordHash, $aesKey]);
    }

    public function login($username, $plainPassword) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($plainPassword, $user['password_hash'])) {
            return $user;
        }

        return false;
    }

    public function getKey($user, $plainPassword) {
        return openssl_decrypt($user['aes_key'], "aes-256-cbc", $plainPassword, 0, "1234567890123456");
    }
}
