<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYesnoActivityTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activity_template_yesno', function (Blueprint $table) {
			$table->increments('id');
			$table->text('description');
		});

		Schema::create('activity_template_yesno_sections', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->unsignedInteger('yes_no_id');

			$table->foreign('yes_no_id')
				  ->references('id')->on('activity_template_yesno')
				  ->onDelete('cascade');
		});

		Schema::create('activity_template_yesno_sections_options', function (Blueprint $table) {
			$table->increments('id');
			$table->string('option');
			$table->tinyInteger('graded')->default(0);
			$table->unsignedInteger('yes_no_section_id');

			$table->foreign('yes_no_section_id', 'section_id')
				  ->references('id')->on('activity_template_yesno_sections')
				  ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		
		Schema::drop('activity_template_yesno_sections_options');
		Schema::drop('activity_template_yesno_sections');
		Schema::drop('activity_template_yesno');

		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}

}
