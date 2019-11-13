<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('avatar')->nullable()->change();
            $table->integer('meter_readings')->nullable()->change();
            $table->string('location_description')->nullable()->change();
            $table->string('manufacturer')->nullable()->change();
            $table->string('model')->nullable()->change();
            $table->string('serial')->nullable()->change();
            $table->string('installed')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
