<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValueRand extends Model
{
  protected $table = "values_rands";


    protected $fillable = [
        'id_rs', 'value','type'
    ];

    public function rs()
    {
      return $this->belongsTo('App\RS', 'id_rs', 'id');
    }

}
