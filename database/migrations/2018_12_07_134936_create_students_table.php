<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('surname')->nullable();
            $table->string('name');
            $table->string('patronymic')->nullable();
            $table->string('login')->nullable();
            $table->string('email',40)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('id_institution')->nullable();
            $table->string('img')->nullable();
            $table->integer('subgroup')->nullable();

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
        Schema::dropIfExists('students');
    }
}
