<?php

// Here, Password Generator Class
class PasswordGenerator {
    
    public static function generate($length = 12, $uppercase = 2, $lowercase = 4, $numbers = 3, $special = 3) {
        $password = '';

        // Validate that total characters don't exceed requested length
        $total = $uppercase + $lowercase + $numbers + $special;
        if ($total > $length) {
            throw new Exception("Total of selected character types exceeds the password length.");
        }

        // Character pools are given below 
        $upperPool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerPool = 'abcdefghijklmnopqrstuvwxyz';
        $numberPool = '0123456789';
        $specialPool = '!@#$%^&*()-_+=<>?';

        // Here, Randomly pick characters from each pool
        $password .= substr(str_shuffle($upperPool), 0, $uppercase);
        $password .= substr(str_shuffle($lowerPool), 0, $lowercase);
        $password .= substr(str_shuffle($numberPool), 0, $numbers);
        $password .= substr(str_shuffle($specialPool), 0, $special);

        // Fill the rest with random lowercase characters if needed
        $remaining = $length - strlen($password);
        if ($remaining > 0) {
            $password .= substr(str_shuffle($lowerPool), 0, $remaining);
        }

        // Shuffle the final password to mix character types
        return str_shuffle($password);
    }
}

?>