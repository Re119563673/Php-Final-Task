<?php

// User Class
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Register a new user
    public function register($username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword
        ]);
        return $this->db->lastInsertId();
    }

    // Login a user
    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}

?>
