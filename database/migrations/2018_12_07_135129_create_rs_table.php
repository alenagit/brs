<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('id_teacher');
            $table->integer('id_discipline');
            $table->integer('id_institution');
            $table->integer('id_group');
            $table->boolean('type_rs')->nullable(); //тип: 100-бальная 5-бальная
            $table->boolean('type'); //тип: зачет экзамен
            $table->integer('total_score')->nullable(); //количество баллов на всю дисциплину
            $table->integer('total_lesson'); //количество ауд. занятий
            $table->integer('total_lesson_score')->nullable(); //количество баллов за все лекции
            $table->integer('total_test')->nullable(); //количество тестов
            $table->integer('total_test_score')->nullable(); //количество баллов за тесты
            $table->integer('total_main_test')->nullable(); //количество итоговых тестов
            $table->integer('total_main_test_score')->nullable(); //количество баллов за итоговые тесты
            $table->boolean('bonuse')->nullable(); //проставление бонусных баллов
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
        Schema::dropIfExists('rs');
    }
}
