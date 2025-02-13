<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class m20250213094957_CreateRolesTable {
    public function up() {
        Capsule::schema()->create('roles', function ($table) {
            $table->increments('id');
            $table->string('name',255)->unique();
            $table->timestamps();
        });
    }

    public function down() {
        Capsule::schema()->dropIfExists('roles');
    }
}