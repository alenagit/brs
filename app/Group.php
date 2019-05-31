<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  protected $table = "groups";

  protected $fillable = [
      'year_adms', 'id_specialty'
  ];

  public function specialty()
  {
    return $this->hasOne('App\Specialty', 'id', 'id_specialty');
  }

}
