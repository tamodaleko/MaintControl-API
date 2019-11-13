<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->string('position')->nullable()->after('password');
            $table->string('avatar')->nullable()->after('position');
            $table->unsignedInteger('skill_id')->nullable()->after('avatar');
            $table->unsignedInteger('currency_id')->nullable()->after('skill_id');
            $table->integer('rate')->after('currency_id');

            $table->foreign('skill_id')
                ->references('id')
                ->on('skills')
                ->onDelete('set null');

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['skill_id']);
            $table->dropForeign(['currency_id']);
            
            $table->dropColumn('username');
            $table->dropColumn('position');
            $table->dropColumn('avatar');
            $table->dropColumn('skill_id');
            $table->dropColumn('currency_id');
            $table->dropColumn('rate');
        });
    }
}
