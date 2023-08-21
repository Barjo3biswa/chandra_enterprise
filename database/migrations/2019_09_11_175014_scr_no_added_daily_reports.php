<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScrNoAddedDailyReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_service_reports', function (Blueprint $table) {
            $table->string('scr_no', 100)->nullable()->after("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_service_reports', function (Blueprint $table) {
            $table->dropColumn('scr_no');
        });
    }
}
