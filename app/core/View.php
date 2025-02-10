<?php

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class View
{
    private static $twig;

    public static function init()
    {
        $loader = new FilesystemLoader('../app/views');
        self::$twig = new Environment($loader, []);

        self::$twig->addFunction(new TwigFunction('auth', function () {
            return Auth::user();
        }));

        self::$twig->addFunction(new TwigFunction('hasRole', function ($role) {
            return Auth::hasRole($role);
        }));

        self::$twig->addFunction(new TwigFunction('csrf_token', function () {
            return Security::generateCsrfToken();
        }));

        self::$twig->addFunction(new TwigFunction('session', function () {
            return new class {
                public function has($key) {
                    return Session::has($key);
                }
                public function get($key) {
                    $value = Session::get($key);
                    Session::remove($key);
                    return $value;
                }
            };
        }));
    }

    public static function render($view, $data = [])
    {
        if (self::$twig === null) {
            self::init();
        }
        echo self::$twig->render("$view.twig", $data);
    }
}
