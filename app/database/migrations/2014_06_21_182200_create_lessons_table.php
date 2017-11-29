<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lessons', function(Blueprint $table)
		{
			$table->increments('id');

            $table->string('title');

            // Reference to the series table
            $table->unsignedInteger('series_id');

			$table->timestamps();
		});

        Schema::table('lessons', function (Blueprint $table)
        {
            $table->foreign('series_id')
                ->references('id')->on('series');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lessons');
	}

}
