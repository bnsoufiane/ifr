<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolDistrictsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('school_districts', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
            $table->timestamps();
		});

        Schema::table('schools', function (Blueprint $table) {
            // A school district that the school belongs to.
            $table->unsignedInteger('school_district_id')->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('school_districts');

        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('school_district_id');
        });
	}

}
