<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFillblankAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_answers_fillblank', function (Blueprint $b) {
			$b->increments('id');
			$b->unsignedInteger('fillblank_answers_id');
		});

		Schema::create('student_answers_fillblank_values', function (Blueprint $b) {
			$b->unsignedInteger('fillblank_answers_id');
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
		Schema::drop('student_answers_fillblank_values');
		Schema::drop('student_answers_fillblank');
	}

}
