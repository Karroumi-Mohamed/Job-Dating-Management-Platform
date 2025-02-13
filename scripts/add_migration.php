<?php

$filename = $argv[1] ?? null;

if (!$filename) {
    echo "Usage: php scripts/create_migration.php CreateUsersTable\n";
    exit;
}

$timestamp = date('YmdHis');
$migrationClass = 'm' . $timestamp . '_' . $filename;

$migrationFile = __DIR__ . "/../migrations/$migrationClass.php";


preg_match('/Create(.*?)Table/', $migrationClass, $matches);
$tableName = $filename;


$template = <<<PHP
<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class $migrationClass {
    public function up() {
        Capsule::schema()->create('$tableName', function (\$table) {
            \$table->increments('id');
            \$table->timestamps();
        });
    }

    public function down() {
        Capsule::schema()->dropIfExists('$tableName');
    }
}
PHP;

// Create migration file
file_put_contents($migrationFile, $template);
echo "Migration created: $migrationFile\n";
