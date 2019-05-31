<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RSTask extends Model
{
  protected $table = "rs_tasks";

  protected $fillable = [
      'id_rs', 'name_task', 'total_task', 'total_task_score'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }

  public function tasks()
  {
    return $this->hasMany('App\InfoTask', 'id_info_task');
  }
}
