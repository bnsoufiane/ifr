<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activities', function(Blueprint $table)
		{
			$table->increments('id');

            $table->string('title');

            // Reference to the lesson.
            $table->unsignedInteger('lesson_id');

            // Image to the right of the title
            $table->string('illustration_image');

            $table->string('background_image');

            $table->string('audio_version');

            $table->string('pdf_version');

            // Reference for an activity template
            $table->morphs('template');

			$table->timestamps();
		});

        Schema::table('activities', function(Blueprint $table)
        {
            $table->foreign('lesson_id')
                ->references('id')->on('lessons');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('activities');
	}

}
