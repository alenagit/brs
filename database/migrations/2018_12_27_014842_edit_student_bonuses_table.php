<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditStudentBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_bonuses', function (Blueprint $table) {
             $table->dropColumn('theme');
             $table->dropColumn('counter');
             $table->integer('id_date_bonuses')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_bonuses', function (Blueprint $table) {
            //
        });
    }
}
