<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentMark extends Model
{
  protected $table = "student_marks";

  protected $fillable = [
      'id_rs', 'id_student', 'mark'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }
}
