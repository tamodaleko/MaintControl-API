<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetSubAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_sub_asset', function (Blueprint $table) {
            $table->unsignedInteger('asset_id');
            $table->unsignedInteger('sub_asset_id');

            $table->foreign('asset_id')->references('id')->on('assets')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('sub_asset_id')->references('id')->on('assets')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['asset_id', 'sub_asset_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_sub_asset', function (Blueprint $table) {
            $table->dropForeign(['asset_id']);
            $table->dropForeign(['sub_asset_id']);
        });

        Schema::dropIfExists('asset_sub_asset');
    }
}
