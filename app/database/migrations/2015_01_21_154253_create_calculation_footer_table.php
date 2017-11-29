<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalculationFooterTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_template_calculation_footers', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->unsignedInteger('calculation_id');

            $t->foreign('calculation_id')
                ->references('id')->on('activity_template_calculation')
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
        Schema::drop('activity_template_calculation_footers');
    }

}
