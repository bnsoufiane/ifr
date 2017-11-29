<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartedLearningColumnToStudentClass extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('school_class_student', function (Blueprint $t) {
            $t->unsignedInteger('started_learning');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('school_class_student', function (Blueprint $t) {
            $t->dropColumn('started_learning');
        });
    }

}
