<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefaultGradedStatusForOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activity_template_select_options', function (Blueprint $table) {
			DB::statement('ALTER TABLE `' . $table->getTable() . '`' .
			              ' CHANGE COLUMN `graded` `graded` TINYINT(1) NOT NULL DEFAULT 0');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('activity_template_select_options', function (Blueprint $table) {
			DB::statement('ALTER TABLE `' . $table->getTable() . '`' .
			              ' CHANGE COLUMN `graded` `graded` TINYINT(1) NOT NULL');
		});
	}

}
