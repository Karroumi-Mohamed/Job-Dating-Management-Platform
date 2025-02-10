<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function attempt($email, $password)
    {
        $user = User::where('email', $email)->first();

        if ($user && Security::verifyPassword($password, $user->password)) {
            self::login($user);
            return true;
        }
        return false;
    }

    public static function hasRole($role)
    {
        return self::check() && self::user()->role->name == $role;
    }
    public static function hasPermission($permission)
    {
        if (!self::check()) return false;
        return self::user()->role->permission->contains('name', $permission);
    }

    public static function guard($permission)
    {
        if (!self::hasPermission($permission)) {
            throw new \Exception('Unauthorized access');
        }
    }

    public static function login($user)
    {
        Session::set('user_id', $user->id);
    }

    public static function logout()
    {
        Session::remove('user_id');
    }

    public static function check()
    {
        return Session::has('user_id');
    }

    public static function user()
    {
        if (self::check()) {
            return User::find(Session::get('user_id'));
        }
        return null;
    }

    public static function id()
    {
        return Session::get('user_id');
    }
}
