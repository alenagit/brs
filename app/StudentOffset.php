<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentOffset extends Model
{
  protected $table = "student_offset";

  protected $fillable = [
      'id_rs', 'id_student', 'value'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }
}
