<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class m20250213095037_CreatePermissionsTable {
    public function up() {
        Capsule::schema()->create('permissions', function ($table) {
            $table->increments('id');
            $table->string('name',255)->unique();

            $table->timestamps();
        });
    }

    public function down() {
        Capsule::schema()->dropIfExists('permissions');
    }
}