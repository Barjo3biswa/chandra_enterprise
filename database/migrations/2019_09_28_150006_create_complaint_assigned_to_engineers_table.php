<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintAssignedToEngineersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_assigned_to_engineers', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('complaint_id');
            $table->string('priority')->nullabel();
            $table->bigInteger('engineer_id');
            $table->string('remark', 300)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('complaint_assigned_to_engineers');
    }
}
