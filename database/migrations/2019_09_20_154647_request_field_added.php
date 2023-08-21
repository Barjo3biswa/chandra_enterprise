<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RequestFieldAdded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('toolkit_requests', function (Blueprint $table) {
            $table->string('request', 100)->nullable()->after("toolkit_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('toolkit_requests', function (Blueprint $table) {
            $table->dropColumn('request');
        });
    }
}
