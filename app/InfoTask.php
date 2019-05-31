<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoTask extends Model
{
  protected $table = "info_tasks";

  protected $fillable = [
      'id_rs', 'number', 'total_score', 'name', 'info', 'pattern', 'comment', 'date_start', 'date_end', 'necessary', 'total_question', 'id_info_task', 'type', 'def_score'
  ];

  public function rs()
  {
    return $this->belongsTo('App\RS', 'id_rs', 'id');
  }

  public function rstask()
  {
    return $this->hasOne('App\RSTask', 'id', 'id_info_task');
  }

  public function rstasks()
  {
    return $this->belongsTo('App\RS', 'id_info_task', 'id');
  }


  public static function getABName(int $id)
  {
    $task = InfoTask::find($id);
    $ab = "";
    if($task)
    {
      if($task->type == 'task')
      {
        $task_name_mass = explode(' ', $task->rstask->name_task);

        foreach ($task_name_mass as $task_mass)
        {
          $ab = $ab.mb_substr($task_mass, 0, 1).". ";
        }
      }
      if($task->type == 'test')
      {
        $ab = "Т";
      }
      if($task->type == 'main_test')
      {
        $ab = "И.Т.";
      }

    }


    return $ab;

  }
  public static function getName(int $id)
  {
    $task = InfoTask::find($id);
    return $task->name;
  }

  public static function getInfo(int $id)
  {
    $task = InfoTask::find($id);
    return $task->info;
  }

}
