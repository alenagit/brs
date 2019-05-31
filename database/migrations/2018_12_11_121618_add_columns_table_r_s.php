<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsTableRS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rs_tasks', function (Blueprint $table) {
            $table->integer('id_rs');
            $table->integer('name_task')->nullable();
            $table->integer('total_task')->nullable();
            $table->integer('total_task_score')->nullable();          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_tasks', function (Blueprint $table) {
            //
        });
    }
}
