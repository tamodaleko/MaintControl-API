<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_asset', function (Blueprint $table) {
            $table->unsignedInteger('area_id');
            $table->unsignedInteger('asset_id');

            $table->foreign('area_id')->references('id')->on('areas')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('asset_id')->references('id')->on('assets')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['area_id', 'asset_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('area_asset', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropForeign(['asset_id']);
        });

        Schema::dropIfExists('area_asset');
    }
}
