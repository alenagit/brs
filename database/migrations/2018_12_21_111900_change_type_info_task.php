<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeInfoTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('info_tasks', function (Blueprint $table) {
            $table->string('date_start')->change(); //тип: зачет экзамен
            $table->string('date_end')->change(); //тип: зачет экзамен
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('info_tasks', function (Blueprint $table) {
            //
        });
    }
}
