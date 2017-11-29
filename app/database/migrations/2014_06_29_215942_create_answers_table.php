<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_answers', function (Blueprint $table) {
			$table->increments('id');

			$table->unsignedInteger('student_id');

			// Reference for an answer.
			$table->morphs('answer');

			$table->timestamps();
		});

		// Create tables for various answer types.
		Schema::create('student_answers_freeform', function (Blueprint $table) {
			// This table is generic and can be used for any type of free-form answer (a question, a blog post, etc.)
			$table->increments('id');

			$table->text('answer');
		});

		Schema::create('student_answers_select', function (Blueprint $table) {
			$table->increments('id');

			$table->unsignedInteger('select_option_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('student_answers');
		Schema::drop('student_answers_freeform');
		Schema::drop('student_answers_select');
	}

}
