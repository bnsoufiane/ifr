<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefaultValuesForActivities extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            foreach (array(
                         'background_image' => '',
                         'illustration_image' => '',
                         'audio_version' => '',
                         'pdf_version' => ''
                     ) as $column => $defaultValue) {
                DB::statement("ALTER TABLE `" . $table->getTable() . "` CHANGE COLUMN `" . $column . "` `" . $column . "` varchar(255) NOT NULL DEFAULT '" . $defaultValue . "';");
            }
            foreach (array(
                         'feedback' => ''
                     ) as $column => $defaultValue) {
                DB::statement("ALTER TABLE `" . $table->getTable() . "` CHANGE COLUMN `" . $column . "` `" . $column . "` text NOT NULL DEFAULT '" . $defaultValue . "';");
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            foreach (array('background_image', 'illustration_image', 'audio_version', 'pdf_version') as $column) {
                DB::statement("ALTER TABLE `" . $table->getTable() . "` CHANGE COLUMN `" . $column . "` `" . $column . "` varchar(255) NOT NULL;");
            }
        });
    }

}
