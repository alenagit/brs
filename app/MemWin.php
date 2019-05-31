<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemWin extends Model
{
  protected $table = "mem_day";

  protected $fillable = [
      'id_user', 'id_rs', 'path', 'score', 'date'
  ];
}
