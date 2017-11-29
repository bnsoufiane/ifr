<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentActivityTypeTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_template_assessment', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
        });

        Schema::create('activity_template_assessment_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->unsignedInteger('assessment_id');
            $table->unsignedInteger('order');
            $table->string('wrong_answer_desc')->default('');

            $table->foreign('assessment_id')
                ->references('id')->on('activity_template_assessment')
                ->onDelete('cascade');
        });

        Schema::create('activity_template_assessment_sections_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('option');
            $table->tinyInteger('graded')->default(0);
            $table->unsignedInteger('assessment_section_id');
            $table->unsignedInteger('order');

            $table->foreign('assessment_section_id', 'assessment_section_id')
                ->references('id')->on('activity_template_assessment_sections')
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

        Schema::drop('activity_template_assessment_sections_options');
        Schema::drop('activity_template_assessment_sections');
        Schema::drop('activity_template_assessment');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
