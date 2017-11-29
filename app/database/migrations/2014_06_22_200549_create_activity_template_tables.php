<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTemplateTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // Freeform answers
		Schema::create('activity_template_freeform', function(Blueprint $table)
		{
			$table->increments('id');
            $table->text('description')->nullable();
		});

        // Comic strip
        Schema::create('activity_template_comic', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('comic_file');
        });

        // Select from many options
        Schema::create('activity_template_select', function (Blueprint $table)
        {
            $table->increments('id');
            $table->text('description')->nullable();
        });

        Schema::create('activity_template_select_options', function (Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('activity_template_select_id');
            $table->string('option');

            $table->foreign('activity_template_select_id', 'select_id')
                ->references('id')->on('activity_template_select');
        });

        // Story
        Schema::create('activity_template_story', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
        });

        Schema::create('activity_template_story_items', function (Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('activity_template_story_id');
            $table->text('text');

            $table->foreign('activity_template_story_id', 'story_id')
                ->references('id')->on('activity_template_story');
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

		Schema::drop('activity_template_freeform');
        Schema::drop('activity_template_comic');
        Schema::drop('activity_template_select');
        Schema::drop('activity_template_select_options');
        Schema::drop('activity_template_story');
        Schema::drop('activity_template_story_items');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}

}
