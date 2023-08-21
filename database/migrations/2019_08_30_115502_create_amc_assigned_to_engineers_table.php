<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmcAssignedToEngineersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amc_assigned_to_engineers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger("client_amc_master_id");
            $table->unsignedBigInteger("engineer_id");
            $table->text("remark")->nullable();
            $table->softDeletes();
            $table->timestamps();
            // $table->foreign('client_amc_master_id')
            //     ->references('id')->on('client_amc_masters')
            //     ->onDelete('cascade');
            // $table->foreign('engineer_id')
            //     ->references('id')->on('users')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amc_assigned_to_engineers');
    }
}
