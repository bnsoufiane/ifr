<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalculationAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_answers_calculation', function (Blueprint $b) {
			$b->increments('id');
			$b->unsignedInteger('calculation_answers_id');
		});

		Schema::create('student_answers_calculation_values', function (Blueprint $b) {
			$b->unsignedInteger('calculation_answers_id');
			$b->string('text');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('student_answers_calculation_values');
		Schema::drop('student_answers_calculation');
	}

}
