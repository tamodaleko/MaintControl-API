<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->unsignedInteger('country_id')->nullable()->after('name');
            $table->string('city')->nullable()->after('country_id');
            $table->string('address')->nullable()->after('city');
            $table->string('phone')->nullable()->after('address');
            $table->string('fax')->nullable()->after('phone');
            $table->string('email')->nullable()->after('fax');
            $table->unsignedInteger('manager_id')->nullable()->after('time_format_24');
            $table->unsignedInteger('timezone_id')->nullable()->after('manager_id');
            $table->unsignedInteger('currency_id')->nullable()->after('timezone_id');

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('set null');

            $table->foreign('manager_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('timezone_id')
                ->references('id')
                ->on('timezones')
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
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['timezone_id']);
            $table->dropForeign(['currency_id']);

            $table->dropColumn('country_id');
            $table->dropColumn('city');
            $table->dropColumn('address');
            $table->dropColumn('phone');
            $table->dropColumn('fax');
            $table->dropColumn('email');
            $table->dropColumn('manager_id');
            $table->dropColumn('timezone_id');
            $table->dropColumn('currency_id');
        });
    }
}
