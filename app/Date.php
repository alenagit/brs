<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
  protected $table = "dates";

  protected $fillable = [
      'id_rs', 'date', 'comment', 'type', 'subgroup', 'id_ktp', 'optional'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }

  public static function getSubgroup(int $id)
  {
    $date = Date::find($id);
    if($date)
    {return $date->subgroup;}
    else{return "not";}

  }

}
