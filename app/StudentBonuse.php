<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentBonuse extends Model
{
  protected $table = "student_bonuses";

  protected $fillable = [
      'id_rs', 'id_group', 'id_student', 'value', 'theme', 'comment', 'counter', 'id_date_bonuses'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }

  public function date()
  {
    return $this->hasOne('App\DateBonuse', 'id', 'id_date_bonuses');
  }
}
