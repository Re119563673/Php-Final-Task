<?php

// Password Encryption Class
class PasswordEncryption {
    private $encryptionKey;

    // Use user's plain login password to generate a fixed encryption key
    public function __construct($userPlainPassword) {
        $this->encryptionKey = hash('sha256', $userPlainPassword); // 256-bit key
    }

    // Encrypt password using AES-256-CBC
    public function encrypt($data) {
        $iv = substr($this->encryptionKey, 0, 16); // 16 bytes IV from hashed key
        return openssl_encrypt($data, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
    }

    // Decrypt password using AES-256-CBC
    public function decrypt($encryptedData) {
        $iv = substr($this->encryptionKey, 0, 16); // Same IV as used in encryption
        return openssl_decrypt($encryptedData, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
    }
}

?>
