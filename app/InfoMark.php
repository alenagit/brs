<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoMark extends Model
{
  protected $table = "info_marks";

  protected $fillable = [
      'id_rs', 'five', 'four', 'three'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }
}
