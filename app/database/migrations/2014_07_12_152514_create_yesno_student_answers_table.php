<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYesnoStudentAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_answers_yesno', function(Blueprint $t) {
			$t->increments('id');
		});

		Schema::create('student_answers_yesno_values', function(Blueprint $t) {
			$t->increments('id');
			$t->unsignedInteger('yes_no_answer_id');
			$t->unsignedInteger('value');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('student_answers_yesno_values');
		Schema::drop('student_answers_yesno');
	}

}
