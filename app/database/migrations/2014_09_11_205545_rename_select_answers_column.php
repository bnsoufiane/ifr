<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSelectAnswersColumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('student_answers_select', function (Blueprint $b) {
            $b->renameColumn('select_option_id', 'select_answer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('student_answers_select', function (Blueprint $b) {
            $b->renameColumn('select_answer_id', 'select_option_id');
        });
    }

}
