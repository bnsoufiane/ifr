<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSideColumnToStoryItems extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activity_template_story_items', function (Blueprint $table) {
			$table->boolean('is_right_side')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('activity_template_story_items', function (Blueprint $table) {
			$table->dropColumn('is_right_side');
		});
	}

}
