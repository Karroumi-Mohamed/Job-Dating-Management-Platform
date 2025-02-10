<?php
return [
    'driver'    => $_ENV['DB_DRIVER'],
    'host'      => $_ENV['DB_HOST'] ?: 'localhost',
    'database'  => $_ENV['DB_NAME'],
    'username'  => $_ENV['DB_USER'],
    'password'  => $_ENV['DB_PASS'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];
