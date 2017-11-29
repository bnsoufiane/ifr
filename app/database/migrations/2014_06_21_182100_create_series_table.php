<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('series', function(Blueprint $table)
		{
			$table->increments('id');

            $table->string('title');

            $table->unsignedInteger('module_id');

			$table->timestamps();
		});

        Schema::table('series', function(Blueprint $table)
        {
            $table->foreign('module_id')
                ->references('id')->on('modules');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('series');
	}

}
