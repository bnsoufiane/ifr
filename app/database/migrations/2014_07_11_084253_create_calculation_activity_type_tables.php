<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalculationActivityTypeTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activity_template_calculation', function (Blueprint $t) {
			$t->increments('id');
			$t->text('description');
		});

		Schema::create('activity_template_calculation_items', function (Blueprint $t) {
			$t->increments('id');
			$t->decimal('employer_cost', 5, 2);
			$t->string('cost_unit')->default('');
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
		Schema::drop('activity_template_calculation_items');
		Schema::drop('activity_template_calculation');
	}

}
