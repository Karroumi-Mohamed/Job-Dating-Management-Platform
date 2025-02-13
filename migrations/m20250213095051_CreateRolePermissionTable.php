<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class m20250213095051_CreateRolePermissionTable
{
    public function up()
    {
        Capsule::schema()->create('role_permission', function ($table) {
            $table->integer('role_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->primary(['role_id', 'permission_id']);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('role_permission');
    }
}
