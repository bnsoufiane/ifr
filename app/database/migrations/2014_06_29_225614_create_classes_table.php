<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('school_classes', function (Blueprint $table) {
			$table->increments('id');

			$table->string('name');

			// A school that the class belongs to.
			$table->unsignedInteger('school_id');

			// a reference to a teacher that has created the class
			$table->unsignedInteger('created_by');

			$table->timestamps();
		});

		// Many-to-many relationship table
		Schema::create('student_school_classes', function (Blueprint $table) {
			$table->unsignedInteger('student_id');
			$table->unsignedInteger('school_class_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('school_classes');
		Schema::drop('student_school_classes');
	}

}
