<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignKeysInTemplates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // Add cascade delete
        Schema::table('activity_template_select_options', function ($table) {
            $table->dropForeign('select_id');

            $table->foreign('activity_template_select_id', 'select_id')
                ->references('id')->on('activity_template_select')
                ->onDelete('cascade');
        });

        Schema::table('activity_template_story_items', function ($table) {
            $table->dropForeign('story_id');

            $table->foreign('activity_template_story_id', 'story_id')
                ->references('id')->on('activity_template_story')
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
        Schema::table('activity_template_select_options', function ($table) {
            $table->dropForeign('select_id');

            $table->foreign('activity_template_select_id', 'select_id')
                ->references('id')->on('activity_template_select');
        });

        Schema::table('activity_template_story_items', function ($table) {
            $table->dropForeign('story_id');

            $table->foreign('activity_template_story_id', 'story_id')
                ->references('id')->on('activity_template_story');
        });
	}

}
