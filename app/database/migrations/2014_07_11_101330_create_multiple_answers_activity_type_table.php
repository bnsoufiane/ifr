<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultipleAnswersActivityTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activity_template_multiple_answers', function (Blueprint $t) {
			$t->increments('id');
			$t->unsignedInteger('number_of_fields');
			$t->text('sample_answer');
			$t->text('description');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('activity_template_multiple_answers');
	}

}
