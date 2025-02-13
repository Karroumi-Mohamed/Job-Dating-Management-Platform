<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class m20250213095106_CreateUsersTable {
    public function up() {
        Capsule::schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name', 255)->unique();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('role_id', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() {
        Capsule::schema()->dropIfExists('users');
    }
}