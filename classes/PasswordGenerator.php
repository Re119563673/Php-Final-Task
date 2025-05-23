<?php
class PasswordGenerator {
    public static function generate($length, $lowercase, $uppercase, $numbers, $specials) {
        $pool = [
            'lower' => 'abcdefghijklmnopqrstuvwxyz',
            'upper' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'number' => '0123456789',
            'special' => '!@#$%^&*()_+',
        ];

        $password = '';
        $password .= self::randomChars($pool['lower'], $lowercase);
        $password .= self::randomChars($pool['upper'], $uppercase);
        $password .= self::randomChars($pool['number'], $numbers);
        $password .= self::randomChars($pool['special'], $specials);

        $remaining = $length - strlen($password);
        $all = implode('', $pool);
        $password .= self::randomChars($all, $remaining);

        return str_shuffle($password);
    }

    private static function randomChars($pool, $count) {
        return substr(str_shuffle(str_repeat($pool, $count)), 0, $count);
    }
}

