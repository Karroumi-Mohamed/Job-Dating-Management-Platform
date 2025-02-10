<?php

namespace App\Core;

class Middleware
{
    public static function handle()
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit();
        }
    }

    public static function handleGuest()
    {
        if (Auth::check()) {
            header('Location: /');
            exit();
        }
    }

    public static function handleRole($role)
    {
        self::handle();
        if (!Auth::check() && !Auth::hasRole($role)) {
            header('Location: /403');
            exit();
        }
    }

    public static function handlePermission($permission)
    {
        self::handle();
        if (!Auth::check() && !Auth::hasPermission($permission)) {
            header('Location: /403');
            exit();
        }
    }
}
