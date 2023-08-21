<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyServiceReportProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_service_report_products', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('daily_service_report_id');
            $table->bigInteger('product_id');
            $table->bigInteger('group_id')->nullable();
            $table->string('model_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('nature_of_complaint_by_customer')->nullable();
            $table->string('fault_observation_by_engineer')->nullable();
            $table->string('action_taken_by_engineer')->nullable();
            $table->string('remark_on_product')->nullable();
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
        Schema::dropIfExists('daily_service_report_products');
    }
}
