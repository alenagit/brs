<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
  protected $table = "reminders";

  protected $fillable = [
      'id_from', 'id_whom', 'theme', 'short_info', 'full_info', 'date_start', 'date_end', 'seen', 'done'
  ];
}
