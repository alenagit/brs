<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_tasks', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_rs');
          $table->integer('number');
          $table->integer('total_score')->nullable(); //балл за работу
          $table->string('name')->nullable();
          $table->string('info')->nullable();
          $table->string('pattern')->nullable();
          $table->string('comment')->nullable();
          $table->date('date_start')->nullable();
          $table->date('date_end')->nullable();
          $table->boolean('necessary')->nullable(); //обязательно для сдачи
          $table->integer('total_question')->nullable(); //для теста
          $table->integer('id_info_task')->nullable(); //для работы связать с именем и количеством
          $table->boolean('type'); //тест, итог. тест, лаба
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
        Schema::dropIfExists('info_tasks');
    }
}
