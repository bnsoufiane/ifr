<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefToActivityTemplateInYesnoAnswers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('student_answers_yesno', function (Blueprint $t) {
			$t->unsignedInteger('yes_no_id');

			//$t->foreign('yes_no_id', 'yes_no_ref')
			//  ->references('id')->on('activity_template_yesno');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('student_answers_yesno', function (Blueprint $t) {
			//$t->dropForeign('yes_no_ref');
			$t->dropColumn('yes_no_id');
		});
	}

}
