<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RSRand extends Model
{
  protected $table = "rs_rands";


    protected $fillable = [
        'id_rs', 'rand_date', 'rand_round', 'rand_will','rand_was','type', 'theme'
    ];

}
