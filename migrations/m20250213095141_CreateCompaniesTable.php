<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class m20250213095141_CreateCompaniesTable {
    public function up() {
        Capsule::schema()->create('companies', function ($table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->text('logo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() {
        Capsule::schema()->dropIfExists('companies');
    }
}