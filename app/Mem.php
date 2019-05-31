<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mem extends Model
{
  protected $table = "memes";

  protected $fillable = [
      'id_user', 'id_rs', 'path', 'score', 'date'
  ];
}
