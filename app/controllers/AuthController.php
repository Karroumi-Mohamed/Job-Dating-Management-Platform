<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Core\Auth;
use App\Core\Security;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    public function showLogin()
    {
        View::render('auth/login');
    }

    public function login()
    {
        $cleaned = Security::clean($_POST);
        $email = $cleaned['email'] ?? '';
        $password = $cleaned['password'] ?? '';
        $token = $cleaned['token'] ?? '';

        if (!Security::validateCsrfToken($token)) {
            $this->error('Invalid Request');
            header('Location: /login');
            exit;
        }

        if (Auth::attempt($email, $password)) {
            $this->success('Successfully logged in');
            header('Location: /');
            exit;
        }

        $this->error('Invalid username or password');
        header('Location: /login');
        exit;
    }

    public function showRegister()
    {
        View::render('auth/register');
    }

    public function register()
    {
        $data = Security::clean($_POST);

        if (!Security::validateCsrfToken($data['token'])) {
            $this->error('Invalid Request');
            header('Location: /register');
            exit;
        }

        if (User::where('email', $data['email'])->exists()) {
            $this->error('Email already exists');
            header('Location: /register');
            exit;
        }

        $data['password'] = Security::hashPassword($data['password']);
        $data['role_id'] = 2;
        User::create($data);

        $this->success('Registration successful');
        header('Location: /login');
        exit;
    }

    public function logout()
    {
        Auth::logout();
        $this->success('Successfully logged out');
        header('Location: /login');
        exit;
    }
}
