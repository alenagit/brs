<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentAttestation extends Model
{
  protected $table = "student_attestations";

  protected $fillable = [
      'id_rs', 'id_student', 'value'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }
}
