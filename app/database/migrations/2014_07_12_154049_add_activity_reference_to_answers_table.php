<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivityReferenceToAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('student_answers', function (Blueprint $t) {
			$t->unsignedInteger('activity_id');

			$t->foreign('activity_id')
			  ->references('id')->on('activities');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('student_answers', function (Blueprint $t) {
			$t->dropForeign('student_answers_activity_id_foreign');
			$t->dropColumn('activity_id');
		});
	}

}
