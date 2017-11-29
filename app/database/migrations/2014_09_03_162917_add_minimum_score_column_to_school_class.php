<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinimumScoreColumnToSchoolClass extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('school_classes', function (Blueprint $t) {
            $t->unsignedInteger('minimum_score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('school_classes', function (Blueprint $t) {
            $t->dropColumn('minimum_score');
        });
    }

}
