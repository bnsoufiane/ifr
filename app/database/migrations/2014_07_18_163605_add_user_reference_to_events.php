<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserReferenceToEvents extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('student_events', function (Blueprint $t) {
			$t->unsignedInteger('user_id');

			$t->foreign('user_id')
			  ->references('id')->on('users')
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
		Schema::table('student_events', function (Blueprint $t) {
			$t->dropForeign('student_events_user_id_foreign');
			$t->dropColumn('user_id');
		});		
	}

}
