<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderColumns extends Migration {

	private static $tables = array(
		'activities',
		'activity_template_cartoon_pictures',
		'activity_template_select_options',
		'activity_template_story_items',
		'activity_template_yesno_sections',
		'activity_template_yesno_sections_options',
		'lessons',
		'series',
	);

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		foreach (self::$tables as $table) {
			Schema::table($table, function (Blueprint $table) {
				$table->unsignedInteger('order');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		foreach (self::$tables as $table) {
			Schema::table($table, function (Blueprint $table) {
				$table->dropColumn('order');
			});
		}
	}

}
