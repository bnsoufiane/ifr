<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTestActivityColumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('test_configurations', function (Blueprint $b) {
            $b->renameColumn('activity_id', 'section_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('test_configurations', function (Blueprint $b) {
            $b->renameColumn('section_id', 'activity_id');
        });
    }

}
