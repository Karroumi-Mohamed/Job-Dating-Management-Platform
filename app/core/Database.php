<?php

namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    private static $capsule;

    public static function init()
    {
        self::$capsule = new Capsule;

        $config = require_once '../config/database.php';
        self::$capsule->addConnection($config);

        self::$capsule->setAsGlobal();
        self::$capsule->bootEloquent();
    }

    public function getCapsule()
    {
        return self::$capsule;
    }
}
