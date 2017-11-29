<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameStudentAnswerColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('student_answers', function (Blueprint $t) {
			$t->renameColumn('answer_id', 'answer_type_id');
			$t->renameColumn('answer_type', 'answer_type_type');
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
			$t->renameColumn('answer_type_id', 'answer_id');
			$t->renameColumn('answer_type_type', 'answer_type');
		});
	}

}
