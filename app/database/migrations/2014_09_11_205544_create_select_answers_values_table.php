<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectAnswersValuesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('student_answers_select_values', function (Blueprint $b) {
            $b->unsignedInteger('select_answer_id');
            $b->unsignedInteger('option');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('student_answers_select_values');
    }

}
