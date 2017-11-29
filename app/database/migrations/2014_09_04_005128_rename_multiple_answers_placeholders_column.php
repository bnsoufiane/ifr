<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMultipleAnswersPlaceholdersColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activity_template_multiple_answers', function (Blueprint $b) {
			$b->renameColumn('sample_answer', 'placeholder_answers');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('activity_template_multiple_answers', function (Blueprint $b) {
			$b->renameColumn('placeholder_answers', 'sample_answer');
		});
	}

}
