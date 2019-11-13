<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaWorkgroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_workgroup', function (Blueprint $table) {
            $table->unsignedInteger('area_id');
            $table->unsignedInteger('workgroup_id');

            $table->foreign('area_id')->references('id')->on('areas')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('workgroup_id')->references('id')->on('workgroups')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['area_id', 'workgroup_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('area_workgroup', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropForeign(['workgroup_id']);
        });

        Schema::dropIfExists('area_workgroup');
    }
}
