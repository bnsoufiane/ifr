<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCalculationTableColumnsCustomisable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_template_calculation', function (Blueprint $t) {
            $t->string('column_1')->default("");
            $t->string('column_2')->default("");
            $t->string('column_3')->default("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_template_calculation', function (Blueprint $t) {
            $t->dropColumn('column_1');
            $t->dropColumn('column_2');
            $t->dropColumn('column_3');
        });
    }

}
