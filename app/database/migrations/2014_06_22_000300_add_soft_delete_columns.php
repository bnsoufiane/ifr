<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('series', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('series', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
	}

}
