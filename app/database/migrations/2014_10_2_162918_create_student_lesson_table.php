<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentLessonTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('student_lesson', function (Blueprint $table) {
            $table->unsignedInteger('lesson_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('closed');
            $table->unsignedInteger('attempts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('student_lesson');
    }

}
