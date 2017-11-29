<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchoolColumnToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->unsignedInteger('school_id')->nullable();
		});

		Schema::table('users', function (Blueprint $table) {
			$table->foreign('school_id')->references('id')->on('schools');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropForeign('users_school_id_foreign');

			$table->dropColumn('school_id');
		});
	}

}
