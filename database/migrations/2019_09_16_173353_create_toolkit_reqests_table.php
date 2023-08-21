<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToolkitReqestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toolkit_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('client_id')->nullable();
            $table->string('request_for')->nullable();
            $table->bigInteger('request_by_id')->nullable();
            $table->bigInteger('toolkit_id')->nullable();
            $table->text('remarks')->nullable();
            $table->bigInteger('issued_by_id')->nullable();
            $table->dateTime('issued_at')->nullable();
            $table->text('issued_remarks')->nullable();
            $table->string("status", 100)->nullable()->default("created");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('toolkit_requests');
    }
}
