<?php
require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Core\Database;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
Database::init();

$batch = Capsule::table('migrations')->max('batch') + 1;
$migrationsDir = __DIR__ . '/../migrations/';
$files = glob($migrationsDir . '*.php');
sort($files);

foreach ($files as $file) {

    $migrationName = pathinfo($file, PATHINFO_FILENAME);
    $className = $migrationName;




    if (Capsule::table('migrations')->where('migration', $migrationName)->exists()) {
        echo "Skipping: $migrationName\n";
        continue;
    }

    require_once $file;

    if (class_exists($className)) {

        $migration = new $className();
        $migration->up();

        Capsule::table('migrations')->insert([
            'migration' => $migrationName,
            'batch' => $batch,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        echo "Migrated: $migrationName\n";
    }
}
