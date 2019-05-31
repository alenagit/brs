<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentLesson extends Model
{
  protected $table = "student_lessons";

  protected $fillable = [
      'id_rs', 'id_student', 'id_group', 'id_date', 'value', 'comment'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }
  public function user()
  {
    return $this->hasOne('App\User', 'id', 'id_student');
  }
  public function date()
  {
    return $this->hasOne('App\Date', 'id', 'id_date');
  }

}
