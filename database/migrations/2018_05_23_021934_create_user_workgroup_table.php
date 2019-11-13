<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWorkgroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_workgroup', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('workgroup_id');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('workgroup_id')->references('id')->on('workgroups')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'workgroup_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_workgroup', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['workgroup_id']);
        });

        Schema::dropIfExists('user_workgroup');
    }
}
