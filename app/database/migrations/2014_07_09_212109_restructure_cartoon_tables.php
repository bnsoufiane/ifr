<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RestructureCartoonTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activity_template_cartoon_pictures', function (Blueprint $table) {
			$table->increments('id');

			$table->string('file');

			$table->unsignedInteger('cartoon_id');

            $table->foreign('cartoon_id')
                ->references('id')->on('activity_template_cartoon')
                ->onDelete('cascade');
		});

		Schema::table('activity_template_cartoon', function (Blueprint $table) {
			$table->dropColumn('comic_file');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('activity_template_cartoon', function (Blueprint $table) {
			$table->string('comic_file');
		});

		Schema::drop('activity_template_cartoon_pictures');
	}

}
