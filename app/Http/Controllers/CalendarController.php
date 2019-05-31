<?php

namespace App\Http\Controllers;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\CreateRSRequest;
use Session;
use DB;
use Cookie;
use App\User;
use App\Date;
use App\RS;
use App\KTP;
use App\Discipline;
use App\Group;
use App\Specialty;
use App\Institution;
use App\StudentWork;
use App\StudentLesson;
use App\StudentBonuse;
use App\InfoTask;
use App\InfoMark;
use App\RSTask;
use App\StudentMark;
use App\StudentOffset;
use App\StudentAttestation;
use App\InfoAttestation;

use \App\Http\Controllers\Student\CalculateController;



class CalendarController extends Controller
{
  public static function getStudentCalendarInfo(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $dates = array();
    $dates_names = array();

    $lessons = StudentLesson::where('id_rs', $id_rs)->where('id_student', $id_student)->where('value', '>', 0)->get(); //MODEL

    $tasks = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('type', 'task')->where('value', '>', 0)->get();

    $tests = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('type', 'test')->where('value', '>', 0)->get();

    $main_tests = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('type', 'main_test')->where('value', '>', 0)->get();

    $bbs = StudentBonuse::where('id_rs', $id_rs)->where('id_student', $id_student)->where('value', '>', 0)->get();

    $score_one_lesson = CalculateController::scoreOneLesson($id_rs);

    //баллы за лекции

    foreach ($lessons as $lesson)
    {
      if(isset($dates[$lesson->date->date]))
      {
        $dates[$lesson->date->date] = $dates[$lesson->date->date] + ($lesson->value * $score_one_lesson);

      }
      else
      {
        $dates += [$lesson->date->date => $lesson->value * $score_one_lesson];
      }


      if(isset($dates_names[$lesson->date->date]))
      {
        $dates_names[$lesson->date->date] = $dates_names[$lesson->date->date].'<br /><strong>Пара:</strong> '.$lesson->value.' ('.round($lesson->value * $score_one_lesson, 1).'Б)';

      }
      else
      {
        $dates_names += [$lesson->date->date => '<strong>Пара:</strong> '.$lesson->value.' ('.round($lesson->value * $score_one_lesson, 1).'Б)'];

      }
    }

    foreach ($tasks as $task)
    {
      $date = mb_substr($task->updated_at, 8, 2).'.'.mb_substr($task->updated_at, 5, 2);
      $score_one = CalculateController::scoreOneTask($id_rs, $task->id_task);
      $name_task = CalculateController::getNameTask($task->id_task);

      if(isset($dates[$date]))
      {
        $dates[$date] = $dates[$date] + (($task->value / 100) * $score_one);

      }
      else
      {
        $dates += [$date => (($task->value / 100) * $score_one)];
      }

      if(isset($dates_names[$date]))
      {
        $dates_names[$date] = $dates_names[$date].'<br /><strong>'.$name_task.':</strong> '.$task->value.'% ('.(($task->value / 100) * $score_one).'Б)';

      }
      else
      {
        $dates_names += [$date => '<strong>'.$name_task.':</strong> '.$task->value.'% ('.(($task->value / 100) * $score_one).'Б)'];

      }
    }

    foreach ($tests as $test)
    {
      $date = mb_substr($test->updated_at, 8, 2).'.'.mb_substr($test->updated_at, 5, 2);
      $score_one_test = CalculateController::scoreOneTest($id_rs, $test->id_task);
      $test_info = InfoTask::find($test->id_task);
      $test_score = 0;

      if($test_info->total_question > 0)
      {
        $test_score = round(($test->value / $test_info->total_question) * $score_one_test, 1);
      }


        if(isset($dates[$date]))
        {
          $dates[$date] = $dates[$date] + $test_score;

        }
        else
        {
          $dates += [$date => $test_score];
        }

        if(isset($dates_names[$date]))
        {
          $dates_names[$date] = $dates_names[$date].'<br /><strong>Тест '.'№'.$test_info->number.':</strong> '.$test->value.' п.о. ('.$test_score.'Б)';

        }
        else
        {
          $dates_names += [$date => '<strong>Тест '.'№'.$test_info->number.':</strong> '.$test->value.' п.о. ('.$test_score.'Б)'];
        }
    }

    foreach ($main_tests as $mtest)
    {
      $date = mb_substr($mtest->updated_at, 8, 2).'.'.mb_substr($mtest->updated_at, 5, 2);
      $score_one_mtest = CalculateController::scoreOneTest($id_rs, $mtest->id_task);
      $mtest_info = InfoTask::find($mtest->id_task);
      $mtest_score = 0;

      if($mtest_info->total_question > 0)
      {
        $mtest_score = round(($mtest->value / $mtest_info->total_question) * $score_one_mtest,1);
      }


        if(isset($dates[$date]))
        {
          $dates[$date] = $dates[$date] + $mtest_score;

        }
        else
        {
          $dates += [$date => $mtest_score];
        }

        if(isset($dates_names[$date]))
        {
          $dates_names[$date] = $dates_names[$date].'<br /><strong>Итоговый тест '.'№'.$mtest_info->number.':</strong> '.$mtest->value.' п.о. ('.$mtest_score.'Б)';

        }
        else
        {
          $dates_names += [$date => '<strong>Итоговый тест '.'№'.$mtest_info->number.':</strong> '.$mtest->value.' п.о. ('.$mtest_score.'Б)'];
        }
    }

    foreach ($bbs as $bb)
    {
      $date = $bb->date->date;
      $naem_bb = $bb->date->name;

      if(isset($dates[$date]))
      {
        $dates[$date] = $dates[$date] + $bb->value;
      }
      else
      {
        $dates += [$date => $bb->value];
      }


      if(isset($dates_names[$date]))
      {
        $dates_names[$date] = $dates_names[$date].'<br /><strong>ББ:</strong> ('.$naem_bb.') '.$bb->value.'Б';

      }
      else
      {
        $dates_names += [$date => '<strong>ББ:</strong> ('.$naem_bb.') '.$bb->value.'Б'];

      }
    }



    $data = array('dates' => $dates, 'dates_names' => $dates_names);

    return $data;


  }
}
