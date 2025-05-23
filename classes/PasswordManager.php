<?php
class PasswordManager {
    private $db;
    private $key;

    public function __construct($pdo, $key) {
        $this->db = $pdo;
        $this->key = $key;
    }

    public function savePassword($userId, $siteName, $plainPassword) {
        $encrypted = openssl_encrypt($plainPassword, "aes-256-cbc", $this->key, 0, "1234567890123456");
        $stmt = $this->db->prepare("INSERT INTO passwords (user_id, site_name, password_encrypted) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $siteName, $encrypted]);
    }

    public function getPasswords($userId) {
        $stmt = $this->db->prepare("SELECT * FROM passwords WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
