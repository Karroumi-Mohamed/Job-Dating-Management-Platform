<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class m20250213095153_CreateAnnouncementsTable {
    public function up() {
        Capsule::schema()->create('announcements', function ($table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->text('description');
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() {
        Capsule::schema()->dropIfExists('announcements');
    }
}