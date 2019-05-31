<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_works', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_rs');
            $table->integer('id_student');
            $table->integer('id_group');
            $table->integer('id_task');
            $table->float('value')->nullable(); //балл за работу
            $table->string('comment')->nullable();
            $table->date('date_pass')->nullable();
            $table->integer('total_question')->nullable(); //для теста
            $table->boolean('type')->nullable(); //тест, итог. тест, лаба
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
        Schema::dropIfExists('student_works');
    }
}
