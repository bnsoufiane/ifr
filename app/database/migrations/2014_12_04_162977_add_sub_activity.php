<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubActivity extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('activities', function (Blueprint $t) {
            $t->unsignedInteger('parent_activity')->nullable();
            DB::statement('ALTER TABLE `activities` MODIFY `lesson_id` INTEGER UNSIGNED NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('lessons', function (Blueprint $t) {
            $t->dropColumn('parent_activity');
            DB::statement('ALTER TABLE `activities` MODIFY `lesson_id` INTEGER UNSIGNED;');
        });
    }

}
