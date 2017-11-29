<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrueFalseActivityTypeTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_template_true_false', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
        });

        Schema::create('activity_template_true_false_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->unsignedInteger('true_false_id');
            $table->unsignedInteger('order');
            $table->string('wrong_answer_desc')->default('');

            $table->foreign('true_false_id')
                ->references('id')->on('activity_template_true_false')
                ->onDelete('cascade');
        });

        Schema::create('activity_template_true_false_sections_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('option');
            $table->tinyInteger('graded')->default(0);
            $table->unsignedInteger('true_false_section_id');
            $table->unsignedInteger('order');

            $table->foreign('true_false_section_id', 'true_false_section_id')
                ->references('id')->on('activity_template_true_false_sections')
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::drop('activity_template_true_false_sections_options');
        Schema::drop('activity_template_true_false_sections');
        Schema::drop('activity_template_true_false');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
