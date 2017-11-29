<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFillblankActivityTypeTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_template_fillblank', function (Blueprint $t) {
            $t->increments('id');
            $t->text('description');
        });

        Schema::create('activity_template_fillblank_items', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->unsignedInteger('fillblank_id');

            $t->foreign('fillblank_id')
                ->references('id')->on('activity_template_fillblank')
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
        Schema::drop('activity_template_fillblank_items');
        Schema::drop('activity_template_fillblank');
    }

}
