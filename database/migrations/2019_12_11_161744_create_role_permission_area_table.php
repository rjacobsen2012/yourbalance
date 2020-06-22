<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePermissionAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('role_permission_area')) {
            Schema::create('role_permission_area', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('area_id');
                $table->timestamps();

                $table->foreign('role_id')->references('id')->on('roles');
                $table->foreign('permission_id')->references('id')->on('permissions');
                $table->foreign('area_id')->references('id')->on('areas');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permission_areas');
    }
}
