<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DateBonuse extends Model
{
  protected $table = "dates_bonuses";

  protected $fillable = [
      'id_rs', 'date', 'comment', 'name', 'round'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }

  public static function getDate(int $id)
  {
    $date = DateBonuse::find($id);

      $date_d = $date->date;
      return  substr($date_d, 3, 2).substr($date_d, 0, 2).".d";


  }

}
