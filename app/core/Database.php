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

        if (!Capsule::schema()->hasTable('migrations')) {
            Capsule::schema()->create('migrations', function ($table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
                $table->timestamps();
            });
        }
    }

    public function getCapsule()
    {
        return self::$capsule;
    }
}
