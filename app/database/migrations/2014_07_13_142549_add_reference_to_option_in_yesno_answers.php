<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferenceToOptionInYesnoAnswers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('student_answers_yesno_values', function (Blueprint $t) {
			$t->unsignedInteger('yes_no_option_id');
			//$t->foreign('yes_no_option_id', 'option_ref_id')
			 // ->references('id')->on('activity_template_yesno_sections_options');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('student_answers_yesno_values', function (Blueprint $t) {
			//$t->dropForeign('option_ref_id');
			$t->dropColumn('yes_no_option_id');
		});
	}

}
