<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesToSchoolRelationshipTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('school_series', function (Blueprint $t) {
			$t->unsignedInteger('school_id');
			$t->unsignedInteger('series_id');
		});

		Schema::table('school_series', function (Blueprint $t) {
			$t->foreign('school_id')->references('id')->on('schools')
			  ->onDelete('cascade');

			$t->foreign('series_id')->references('id')->on('series')
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
		Schema::drop('school_series');
	}

}
