<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserParam extends Model
{
  protected $table = "user_params";


    protected $fillable = [
        'id_user', 'theme_color','theme_view'
    ];

}
