<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetSpareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_spare', function (Blueprint $table) {
            $table->unsignedInteger('asset_id');
            $table->unsignedInteger('spare_id');

            $table->foreign('asset_id')->references('id')->on('assets')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('spare_id')->references('id')->on('spares')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['asset_id', 'spare_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_spare', function (Blueprint $table) {
            $table->dropForeign(['asset_id']);
            $table->dropForeign(['spare_id']);
        });

        Schema::dropIfExists('asset_spare');
    }
}
