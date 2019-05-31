<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentWork extends Model
{
  protected $table = "student_works";

  protected $fillable = [
      'id_rs', 'id_student', 'id_group', 'id_task', 'value',
      'comment', 'date_pass', 'total_question', 'type'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }

  public function infotask()
  {
    return $this->hasOne('App\InfoTask', 'id', 'id_task');
  }

  public function user()
  {
    return $this->hasOne('App\User', 'id', 'id_student');
  }


}
