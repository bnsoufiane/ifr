<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLessonsMinimumScore extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('lessons', function (Blueprint $t) {
            $t->unsignedInteger('minimum_score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('lessons', function (Blueprint $t) {
            $t->dropColumn('minimum_score');
        });
    }

}
