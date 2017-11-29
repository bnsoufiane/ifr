<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCharacterColumnToStoryItems extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('activity_template_story_items', function (Blueprint $table) {
            $table->unsignedInteger('character_id');
		});

        Schema::table('activity_template_story_items', function (Blueprint $table) {
            $table->foreign('character_id')
                ->references('id')->on('story_characters');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('activity_template_story_items', function (Blueprint $table) {
            $table->dropForeign('activity_template_story_items_character_id_foreign');
            $table->dropColumn('character_id');
		});
	}

}
