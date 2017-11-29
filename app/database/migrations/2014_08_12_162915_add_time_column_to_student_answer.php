<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeColumnToStudentAnswer extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('student_answers', function (Blueprint $t) {
            $t->unsignedInteger('time_needed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('student_answers', function (Blueprint $t) {
            $t->dropColumn('time_needed');
        });
    }

}
