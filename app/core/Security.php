<?php

namespace App\Core;

class Security
{

    public static function generateCsrfToken()
    {
        if (!Session::has('csrf_token')) {
            $token = bin2hex(random_bytes(32));
            Session::set('csrf_token', $token);
        }
        return Session::get('csrf_token');
    }

    public static function validateCsrfToken($token)
    {
        return hash_equals(Session::get('csrf_token'), $token);
    }

    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    public static function clean($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'clean'], $data);
        }
        return trim(strip_tags($data));
    }


    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public static function sanitizeString($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
