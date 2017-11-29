<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultianswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_answers_multiple', function (Blueprint $b) {
			$b->increments('id');
			$b->unsignedInteger('multiple_answers_id');
		});

		Schema::create('student_answers_multiple_values', function (Blueprint $b) {
			$b->unsignedInteger('multiple_answers_id');
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
		Schema::drop('student_answers_multiple_values');
		Schema::drop('student_answers_multiple');
	}

}
