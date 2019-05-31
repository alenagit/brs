<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KTP extends Model
{
  protected $table = "ktps";

  protected $fillable = [
      'id_rs', 'name', 'type', 'hour'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }


}
