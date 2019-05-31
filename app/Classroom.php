<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
  protected $table = "classrooms";

  protected $fillable = [
      'id_teacher', 'id_group'
  ];
}
