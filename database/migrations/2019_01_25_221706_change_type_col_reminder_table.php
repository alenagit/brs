<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeColReminderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->string('date_start',15)->nullable()->change(); //тип: зачет экзамен
            $table->string('date_end',15)->nullable()->change(); //тип: зачет экзамен
            $table->mediumText('full_info')->nullable()->change(); //тип: зачет экзамен
            $table->longText('short_info')->nullable()->change(); //тип: зачет экзамен
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reminders', function (Blueprint $table) {
            //
        });
    }
}
