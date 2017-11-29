<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('schoolclass_id');
            $table->timestamps();
        });

        Schema::create('test_configurations', function (Blueprint $table) {
            $table->unsignedInteger('test_id');
            $table->unsignedInteger('activity_id');
            $table->unsignedInteger('test_type');
            $table->timestamps();
        });

        Schema::create('test_students', function (Blueprint $table) {
            $table->unsignedInteger('test_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('learning_level');
            $table->unsignedInteger('status');
            $table->float('score');
            $table->unsignedInteger('attempts');
            $table->unsignedInteger('current_activity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tests');
        Schema::drop('test_configurations');
        Schema::drop('test_students');
    }

}
