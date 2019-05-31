<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RS extends Model
{
  protected $table = "rs";

  protected $fillable = [
      'name', 'id_teacher', 'id_discipline', 'id_institution', 'id_group',
      'type_rs', 'type', 'total_score', 'total_lesson', 'lesson_subgroup', 'total_lesson_score',
      'total_test', 'total_test_score', 'total_main_test', 'total_main_test_score',
      'bonuse'
  ];




  public function rstasks()
  {
    return $this->hasMany('App\RSTask', 'id_rs');
  }

  public function ktp()
  {
    return $this->hasMany('App\KTP', 'id_rs');
  }

  public function dates()
  {
    return $this->hasMany('App\Date', 'id_rs');
  }

  public function infotasks()
  {
    return $this->hasMany('App\InfoTask', 'id_rs');
  }


  public function infomarks()
  {
    return $this->hasOne('App\InfoMark', 'id_rs', 'id');
  }


  public function studentlessons()
  {
    return $this->hasMany('App\StudentLesson', 'id_rs');
  }

  public function studentWorks()
  {
    return $this->hasMany('App\StudentWork', 'id_rs');
  }

  public function studentBonuses()
  {
    return $this->hasMany('App\StudentBonuse', 'id_rs');
  }

  public function datebonuse()
  {
    return $this->hasMany('App\DateBonuse', 'id_rs');
  }

  public function studentMarks()
  {
    return $this->hasMany('App\StudentMark', 'id_rs');
  }

  public function studentOffsets()
  {
    return $this->hasMany('App\StudentOffset', 'id_rs');
  }

  public function studentAttestations()
  {
    return $this->hasMany('App\StudentAttestation', 'id_rs');
  }

  public function valuerands()
  {
    return $this->hasMany('App\ValueRand', 'id_rs');
  }


}
