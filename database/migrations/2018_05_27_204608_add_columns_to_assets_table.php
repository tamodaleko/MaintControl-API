<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('uid')->after('name');
            $table->unsignedInteger('parent_asset_id')->nullable()->after('uid');
            $table->unsignedInteger('area_id')->after('parent_asset_id');
            $table->unsignedInteger('category_id')->nullable()->after('area_id');
            $table->unsignedInteger('condition_id')->nullable()->after('category_id');
            $table->string('avatar')->after('condition_id');
            $table->integer('meter_readings')->after('avatar');
            $table->string('location_description')->after('meter_readings');
            $table->string('manufacturer')->after('location_description');
            $table->string('model')->after('manufacturer');
            $table->string('serial')->after('model');
            $table->string('installed')->after('serial');

            $table->foreign('parent_asset_id')->references('id')->on('assets');

            $table->foreign('area_id')
                ->references('id')
                ->on('areas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['parent_asset_id']);
            $table->dropForeign(['area_id']);
            
            $table->dropColumn('uid');
            $table->dropColumn('parent_asset_id');
            $table->dropColumn('area_id');
            $table->dropColumn('category_id');
            $table->dropColumn('condition_id');
            $table->dropColumn('avatar');
            $table->dropColumn('meter_readings');
            $table->dropColumn('location_description');
            $table->dropColumn('manufacturer');
            $table->dropColumn('model');
            $table->dropColumn('serial');
            $table->dropColumn('installed');
        });
    }
}
