<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoAttestationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_attestations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_rs');
            $table->integer('id_task_info')->nullable();
            $table->integer('value')->nullable();
            $table->integer('type')->nullable(); //лекция работа тест
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('info_attestations');
    }
}
