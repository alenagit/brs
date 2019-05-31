<?php

namespace App\Http\Controllers\Student;


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
use App\Discipline;
use App\Group;
use App\Specialty;
use App\Institution;
use App\StudentWork;
use App\StudentLesson;
use App\StudentBonuse;
use App\DateBonuse;
use App\InfoTask;
use App\RSTask;
use App\Reminder;
use App\Mem;
use App\MemWin;
use App\Http\Controllers\Teacher\RSController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;


class CalculateController extends Controller
{
  public static function getGroupById(int $id_user)
  {
    $user = DB::table('users')->where('id', $id_user)->get();
    return $user[0]->id_group;
  }
  public static function statusKTP(int $id_rs)
  {
    $actual_ktp = array();
    $ktps = DB::table('ktps')->where('id_rs', $id_rs)->get();
    $dates = DB::table('dates')->where('id_ktp', '!=', NULL)->get();

    foreach ($ktps as $key => $ktp)
    {
      $count_dates = $dates->where('id_ktp', $ktp->id)->count() * 2;

      $actual_ktp += [$ktp->id => $ktp->hour - $count_dates];
    }
    $data = array('ktps' => $ktps, 'done_hour' => $actual_ktp);
    return $data;
  }

  public static function countUploadMemYest(int $id_rs)
  {
    date_default_timezone_set('Europe/Moscow');
    $yesterday = date('d.m.Y', time() - 86400);
    $count = DB::table('memes')->where('date', $yesterday)->where('id_rs', $id_rs)->count();
    return $count;
  }

  public static function countUploadMem(int $id_rs)
  {
    date_default_timezone_set('Europe/Moscow');
    $today = date("d.m.Y");
    $count = DB::table('memes')->where('date', $today)->where('id_rs', $id_rs)->count();
    return $count;
  }
  public static function getCan(int $id_rs, int $id_user)
  {
    date_default_timezone_set('Europe/Moscow');
    $today = date("d.m.Y");
    $memes = DB::table('memes')->where('date', $today)->where('id_user', $id_user)->where('id_rs', $id_rs)->get();
    if(count($memes) > 0)
    {
      return 0;
    }
    else {
      return 1;
    }
  }

  public static function getMem(int $id_rs)
  {
    date_default_timezone_set('Europe/Moscow');
    $rs = RS::find($id_rs);
    $score_for_stud = 0;
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();
    $today = date("d.m.Y");
    $yesterday = date('d.m.Y', time() - 86400);
    $mem = DB::table('mem_day')->where('date', $today)->where('id_rs', $id_rs)->get();
    $mem_info = array();

    if(count($mem) > 0)
    {
      foreach ($mem as $me)
      {
        $mem_info = array('id_user' => $me->id_user, 'path' => $me->path, 'score' => $me->score);
      }
    }
    else
    {
      $memes = DB::table('memes')->where('date', $yesterday)->where('id_rs', $id_rs)->pluck('id')->toArray();

      if(count($memes) > 0)
      {
        if(count($memes) == 1)
        {

          $mem_win = DB::table('memes')->where('id', $memes[0])->get();
        }
        else
        {

          $rand = array_rand($memes);
          $mem_win = DB::table('memes')->where('id', $memes[$rand])->get();
        }


        if(isset($mem_win[0]))
        {
          $score_win = $mem_win[0]->score;
          if(count($memes) < 5)
          {
            $score_for_stud = 0;
          }
          else
          {
            $score_for_stud = 10;
            if($score_win > 0) $score_for_stud = $score_win * 2;
            if($score_win > 15) $score_for_stud = 30;
          }


          $mem_winer = new MemWin;
          $mem_winer->id_user = $mem_win[0]->id_user;
          $mem_winer->id_rs = $mem_win[0]->id_rs;
          $mem_winer->score = $score_for_stud;
          $mem_winer->path = $mem_win[0]->path;
          $mem_winer->date = $today;
          $mem_winer->save();

          if(count($memes) >= 5)
          {

            $date_bonuse = DB::table('dates_bonuses')->where('id_rs', $id_rs)->where('name', "За мемасы")->get();

            if(isset($date_bonuse[0]))
            {
              $stud_bonuses = DB::table('student_bonuses')->where('id_date_bonuses', $date_bonuse[0]->id)->where('id_student', $mem_win[0]->id_user)->get();

              if(isset($stud_bonuses[0]))
              {
                $stud_bonuse = StudentBonuse::find($stud_bonuses[0]->id);
                $stud_bonuse->value = $stud_bonuse->value + $score_for_stud;
                $stud_bonuse->save();
              }
            }
            else
            {
              $date_bonuse = DateBonuse::create([
                'id_rs' => $id_rs,
                'name' => "За мемасы",
                'date' => date("d.m"),
                'round' => 1
              ]);


              foreach ($students as $student)
              {
                $rand = StudentBonuse::create([
                  'id_rs' => $id_rs,
                  'id_student' => $student->id,
                  'id_group' => $rs->id_group,
                  'value' => 0,
                  'id_date_bonuses' => $date_bonuse->id
                ]);

                if($mem_win[0]->id_user == $student->id)
                {
                  $rand->value = $score_for_stud;
                  $rand->save();
                }
              }
            }

            $memes_minus = DB::table('memes')->where('date', $yesterday)->where('id_rs', $id_rs)->where('score', '>', 0)->get();
            if(count($memes) > 0)
            {
              foreach ($memes_minus as $minus)
              {
                $date_bonuse_m = DB::table('dates_bonuses')->where('id_rs', $id_rs)->where('name', "За мемасы")->get();
                $stud_bonuses_m = DB::table('student_bonuses')->where('id_date_bonuses', $date_bonuse_m[0]->id)->where('id_student', $minus->id_user)->get();

                $minus_score = abs($minus->score);
                if($minus->score > 15)
                {
                  $minus_score = 15;
                }

                if(isset($stud_bonuses_m[0]))
                {
                  $stud_bonuse_mm = StudentBonuse::find($stud_bonuses_m[0]->id);
                  $stud_bonuse_mm->value = $stud_bonuse_mm->value - $minus_score;
                  $stud_bonuse_mm->save();
                }
              }
            }
          }

          $mem_info = array('id_user' => $mem_win[0]->id_user, 'path' => $mem_win[0]->path, 'score' => $score_for_stud);
        }


      }
    }

    return $mem_info;

  }
  public static function saveMem(Request $request)
  {
    date_default_timezone_set('Europe/Moscow');

    $path = '';
    $today = date("d.m.Y");

    if(!empty($request->file('mem')))
    {
        $path = Storage::disk('ftp')->putFile('memes',$request->file('mem'));

        $mem = new Mem;
        $mem->id_user = $request->id_user;
        $mem->id_rs = $request->id_rs;
        $mem->score = $request->score;
        $mem->path = $path;
        $mem->date = $today;

        $mem->save();
    }
  }
  public static function getArrayStudentScore(int $id_rs) //отдает массив студентов по брс с их баллами (лекции, работы, тесты, бб, сумма всех балло)
  {

    $rs = DB::table('rs')->where('id', $id_rs)->get();

    if(isset($rs[0]))
    {
      $users = DB::table('users')->where('id_group', $rs[0]->id_group)->get();
      $info_tasks = DB::table('info_tasks')->where('id_rs', $id_rs)->get();
      $score_one_lesson = CalculateController::scoreOneLesson($id_rs);
      $arr_info_tasks = array();
      $arr_student_info = array();

      foreach ($info_tasks as $info_task)
      {

        $arr_info_tasks += [$info_task->id => array()];

        $arr_info_tasks[$info_task->id] += ['score' => CalculateController::scoreOneTask($id_rs, $info_task->id)];

        if($info_task->type == "test" || $info_task->type == "main_test")
        {
          $arr_info_tasks[$info_task->id] += ['quests' => $info_task->total_question];
        }
      }

      foreach ($users as $user)
      {

        $score_works = 0;
        $score_tests = 0;
        $score_main_tests = 0;

        $works = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $user->id)->where('value', '!=', NULL)->get();
        $tasks = $works->where('type', "task");
        $tests = $works->where('type', "test");
        $main_tests = $works->where('type', "main_test");

        $arr_student_info += [$user->id => array()];

        $arr_student_info[$user->id] += ['lesson' => DB::table('student_lessons')->where('id_rs', $id_rs)->where('id_student', $user->id)->where('value', '>', 0)->get()->sum('value') * $score_one_lesson];


        foreach ($tasks as $task)
        {
          $score_works += ($task->value / 100) * $arr_info_tasks[$task->id_task]['score'];
        }

        foreach ($tests as $test)
        {
          $score_tests += ($test->value / $arr_info_tasks[$test->id_task]['quests']) * $arr_info_tasks[$test->id_task]['score'];
        }

        foreach ($main_tests as $main_test)
        {
          $score_main_tests += ($main_test->value / $arr_info_tasks[$main_test->id_task]['quests']) * $arr_info_tasks[$main_test->id_task]['score'];
        }

        $arr_student_info[$user->id] += ['test' => $score_tests];
        $arr_student_info[$user->id] += ['main_test' => $score_main_tests];
        $arr_student_info[$user->id] += ['bonuse' => DB::table('student_bonuses')->where('id_rs', $id_rs)->where('id_student', $user->id)->where('value', '!=', NULL)->get()->sum('value')];
        $arr_student_info[$user->id] += ['score' => $arr_student_info[$user->id]['lesson'] + $arr_student_info[$user->id]['bonuse'] + $score_works + $score_tests + $score_main_tests];

      }

      return $arr_student_info;
    }


  }
  public static function getArrayStudentTOP(int $id_rs) //отдает массив с оценкой, аттестаций, баллами за все и по отдельности за все
  {

    $rs = DB::table('rs')->where('id', $id_rs)->get();

    if(isset($rs[0]))
    {
      $users = DB::table('users')->where('id_group', $rs[0]->id_group)->get();
      $info_tasks = DB::table('info_tasks')->where('id_rs', $id_rs)->get();
      $rs_tasks = DB::table('rs_tasks')->where('id_rs', $id_rs)->get();
      $score_one_lesson = CalculateController::scoreOneLesson($id_rs);
      $att_score = CalculateController::getAttScore($id_rs); //сколько всего можно было набрать за дисциплину
      $arr_info_tasks = array();
      $arr_student_info = array();

      foreach ($info_tasks as $info_task)
      {

        $arr_info_tasks += [$info_task->id => array()];



        if($info_task->type == "test" || $info_task->type == "main_test")
        {
          $arr_info_tasks[$info_task->id] += ['score' => CalculateController::scoreOneTest($id_rs, $info_task->id)];
          $arr_info_tasks[$info_task->id] += ['quests' => $info_task->total_question];
        }
        else
        {
          $arr_info_tasks[$info_task->id] += ['score' => CalculateController::scoreOneTask($id_rs, $info_task->id)];
          $arr_info_tasks[$info_task->id] += ['rs_task' => $info_task->id_info_task];
        }
      }

      foreach ($users as $user)
      {

        $score_works = 0;
        $score_tests = 0;
        $score_main_tests = 0;

        $works = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $user->id)->where('value', '!=', NULL)->get();
        $tasks = $works->where('type', "task");
        $tests = $works->where('type', "test");
        $main_tests = $works->where('type', "main_test");
        $arr_student_info += [$user->id => 0];



        $ar_lessin = DB::table('student_lessons')->where('id_rs', $id_rs)->where('id_student', $user->id)->where('value', '>', 0)->get()->sum('value') * $score_one_lesson;

        foreach ($rs_tasks as $rs_task)
        {
          $sum = 0;

          foreach ($tasks as $task)
          {
            if($arr_info_tasks[$task->id_task]['rs_task'] == $rs_task->id)
            {
              $sum += ($task->value / 100) * $arr_info_tasks[$task->id_task]['score'];

            }
          }

          $score_works += $sum;

        }

        foreach ($tests as $test)
        {
          $score_tests += ($test->value / $arr_info_tasks[$test->id_task]['quests']) * $arr_info_tasks[$test->id_task]['score'];
        }

        foreach ($main_tests as $main_test)
        {
          $score_main_tests += ($main_test->value / $arr_info_tasks[$main_test->id_task]['quests']) * $arr_info_tasks[$main_test->id_task]['score'];
        }

        $ar_bonuse = DB::table('student_bonuses')->where('id_rs', $id_rs)->where('id_student', $user->id)->where('value', '!=', NULL)->get()->sum('value');
        $arr_student_info[$user->id] += round($ar_lessin + $ar_bonuse + $score_works + $score_tests + $score_main_tests);



    }
    arsort($arr_student_info);

    return $arr_student_info;

  }
}

  public static function getArrayStudentSMA(int $id_rs) //отдает массив с оценкой, аттестаций, баллами за все и по отдельности за все
  {

    $rs = DB::table('rs')->where('id', $id_rs)->get();

    if(isset($rs[0]))
    {
      $users = DB::table('users')->where('id_group', $rs[0]->id_group)->get();
      $info_tasks = DB::table('info_tasks')->where('id_rs', $id_rs)->get();
      $rs_tasks = DB::table('rs_tasks')->where('id_rs', $id_rs)->get();
      $score_one_lesson = CalculateController::scoreOneLesson($id_rs);
      $att_score = CalculateController::getAttScore($id_rs); //сколько всего можно было набрать за дисциплину
      $arr_info_tasks = array();
      $arr_student_info = array();

      foreach ($info_tasks as $info_task)
      {

        $arr_info_tasks += [$info_task->id => array()];



        if($info_task->type == "test" || $info_task->type == "main_test")
        {
          $arr_info_tasks[$info_task->id] += ['score' => CalculateController::scoreOneTest($id_rs, $info_task->id)];
          $arr_info_tasks[$info_task->id] += ['quests' => $info_task->total_question];
        }
        else
        {
          $arr_info_tasks[$info_task->id] += ['score' => CalculateController::scoreOneTask($id_rs, $info_task->id)];
          $arr_info_tasks[$info_task->id] += ['rs_task' => $info_task->id_info_task];
        }
      }

      foreach ($users as $user)
      {

        $score_works = 0;
        $score_tests = 0;
        $score_main_tests = 0;

        $works = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $user->id)->where('value', '!=', NULL)->get();
        $tasks = $works->where('type', "task");
        $tests = $works->where('type', "test");
        $main_tests = $works->where('type', "main_test");

        $arr_student_info += [$user->id => array()];

        $arr_student_info[$user->id] += ['lesson' => DB::table('student_lessons')->where('id_rs', $id_rs)->where('id_student', $user->id)->where('value', '>', 0)->get()->sum('value') * $score_one_lesson];

        foreach ($rs_tasks as $rs_task)
        {
          $sum = 0;

          foreach ($tasks as $task)
          {
            if($arr_info_tasks[$task->id_task]['rs_task'] == $rs_task->id)
            {
              $sum += ($task->value / 100) * $arr_info_tasks[$task->id_task]['score'];

            }
          }

          $score_works += $sum;
          $arr_student_info[$user->id] += [$rs_task->id => $sum];
        }

        foreach ($tests as $test)
        {
          $score_tests += ($test->value / $arr_info_tasks[$test->id_task]['quests']) * $arr_info_tasks[$test->id_task]['score'];
        }

        foreach ($main_tests as $main_test)
        {
          $score_main_tests += ($main_test->value / $arr_info_tasks[$main_test->id_task]['quests']) * $arr_info_tasks[$main_test->id_task]['score'];
        }

        $arr_student_info[$user->id] += ['test' => $score_tests];
        $arr_student_info[$user->id] += ['main_test' => $score_main_tests];
        $arr_student_info[$user->id] += ['bonuse' => DB::table('student_bonuses')->where('id_rs', $id_rs)->where('id_student', $user->id)->where('value', '!=', NULL)->get()->sum('value')];
        $arr_student_info[$user->id] += ['score' => round($arr_student_info[$user->id]['lesson'] + $arr_student_info[$user->id]['bonuse'] + $score_works + $score_tests + $score_main_tests,1)];
        $arr_student_info[$user->id] += ['persent' => (($arr_student_info[$user->id]['score'] / $rs[0]->total_score) * 100)];
        if($att_score > 0)
        {
          $arr_student_info[$user->id] += ['persent_att' => (($arr_student_info[$user->id]['score'] / $att_score) * 100)];
        }
        else{$arr_student_info[$user->id] += ['persent_att' => 0];}

        $arr_student_info[$user->id] += ['att' => CalculateController::getMark($id_rs, $arr_student_info[$user->id]['persent_att'])];
        $arr_student_info[$user->id] += ['mark' => CalculateController::getMark($id_rs, $arr_student_info[$user->id]['persent'])];

      }
      $arr_student_info += ['mast_att' => $att_score];

      return $arr_student_info;

    }

  }


  public static function getMyReminders(int $id_user)
  {
    $reminders = DB::table('reminders')->where('id_from', $id_user)->get()->unique('theme')->sortByDesc('created_at');
    return $reminders;
  }
  public static function getNameRSTASK(int $id_rstask)
  {
    $task = RSTask::find($id_rstask);
    if($task)
    {return $task->name_task;}

  }
  public static function getHookInfo($rss, $students, $dates)
  {
    $result_array = array();

    foreach ($dates as $date)
    {
      $result_array += [$date => array()];

      foreach ($rss as $rs)
      {

        $vrem_date = array();
        $dates_rs = DB::table('dates')->where('id_rs', $rs->id)->where('date', $date)->get()->toArray();

        if($dates_rs != NULL)
        {
          foreach ($dates_rs as $date_rs)
          {
            $rs_date_info = array();
            $rs_date_students = array();
            $rs_date_info += ['type' => $date_rs->type];
            $rs_date_info += ['subgroup' => $date_rs->subgroup];

            foreach ($students as $student)
            {
              $lesson_value = DB::table('student_lessons')->where('id_rs', $rs->id)->where('id_student', $student->id)->where('id_date', $date_rs->id)->pluck('value')->toArray()[0];

              if($lesson_value < 1 && ($date_rs->subgroup == $student->subgroup || $date_rs->subgroup == 0))
              {
                array_push($rs_date_students, ($student->id.'/'.$lesson_value));
              }
            }

            $var_ar = array(
              'info' => $rs_date_info,
              'students' => $rs_date_students
            );

            $vrem_date += [$date_rs->id => $var_ar];

          }
        }
         $result_array[$date] += [$rs->id => $vrem_date];
      }
    }

    foreach ($result_array as $id_res => $result_ar)
    {
      $max_count_stud = 0;
      foreach ($rss as $rs)
      {
        foreach ($result_ar[$rs->id] as $rs_in)
        {
          $count_stud = count($rs_in['students']);

          if($max_count_stud < $count_stud)
          {$max_count_stud = $count_stud;}
        }

      }

      $result_array[$id_res] += ['max' => $max_count_stud];

    }

    return $result_array;
  }
  public static function getDateRS(int $id_rs, $date)
  {
    $date = DB::table('dates')->where('id_rs', $id_rs)->where('date', $date)->get()->toArray();
    if(isset($date[0]))
    {
      return $date;
    }
  }
  public static function getDatesHookClassroom(int $id_teacher)
  {

    $class = DB::table('classrooms')->where('id_teacher', $id_teacher)->get();

    if(isset($class[0]))
    {
      $id_group = $class[0]->id_group;

      $rss = RS::where('id_group', $id_group)->get();

      $dates = array();

      foreach ($rss as $rs)
      {
        $dates += [$rs->id => DB::table('dates')->where('id_rs', $rs->id)->where('date', '!=', NULL)->pluck('date','id')->toArray()];
      }

      $ke = -1;

      foreach ($dates as $key => $date)
      {
        if($ke != -1)
        {
          $vrem = array();
          $dates[$key]  = array_merge($dates[$ke], $dates[$key]);
          unset($dates[$ke]);
        }
        $ke = $key;
      }

      $dates = array_unique($dates[$ke]);

      return $dates;
    }
  }

  public static function getHookHas(int $id_rs, int $id_student, $date)
  {
    $dates = DB::table('dates')->where('id_rs', $id_rs)->where('date', $date)->pluck('id')->toArray();
    $hooks = array();

    foreach ($dates as $date)
    {
      $hooks += [$date => DB::table('student_lessons')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('id_date', $date)->pluck('value')->toArray()[0]];
    }


    $data  = array(
      'dates' => $dates,
      'hooks' => $hooks
    );
    return $data;
  }
  public static function getColorMark($mark)
  {
    $color = "";

    switch ($mark) {
    case 5:
      $color = "rgba(0,200,0,0.";
      break;
    case 4:
      $color = "rgba(0,100,255,0.";
      break;
    case 3:
      $color = "rgba(255,150,0,0.";
      break;
    case "-":
      $color = "rgba(255,0,0,0.";
      break;
    case 2:
      $color = "rgba(255,0,0,0.";
      break;
  }
  return $color;

  }

  //отдает массив Н и оценок по массиву дат для всех студентов группы БРС
  public static function getMarksPaperStudent(int $id_rs, array $dates)
  {
    $rs = RS::find($id_rs);
    $students = DB::table('users')->where('id_group', $rs->id_group)->pluck('id');

    $array_mark_student = array();

    foreach ($students as $student)
    {
      $marks = array();
      $task_marks = RSController::getMarksTaskPaperJurnal($id_rs, $student);
      $n_mark = 0;


      foreach ($dates as $key_d => $date)
      {
        $marks += [$key_d => "Н"];

        if(substr($key_d, -1) == "d")
        {
          $pos = strpos($date, ",");

          if($pos !== false) { $id_date_mass = explode(",", $date); }

          if(isset($id_date_mass[0])) // если в дате несколько дат
          {
            foreach ($id_date_mass as $id_date_m)
            {
              $lesson = DB::table('student_lessons')->where('id_date', $id_date_m)->where('id_student', $student)->pluck('value');
              if(isset($lesson[0]))
              {
                $lesson = $lesson[0];


                if(Date::getSubgroup($id_date_m) == User::getSubgroup($student) || Date::getSubgroup($id_date_m) < 1)
                {
                  if($lesson != 0 || $lesson != NULL)
                  {
                    $marks[$key_d] = "";
                    break;
                  }
                }
              }



            }
            unset($id_date_mass);

          }
          else
          {
            $lessoni = DB::table('student_lessons')->where('id_date', $date)->where('id_student', $student)->pluck('value');

            if(isset($lessoni[0])) {$lessoni = $lessoni[0];}

            if(Date::getSubgroup($date) == User::getSubgroup($student) || Date::getSubgroup($date) < 1)
            {
              if($lessoni != 0 || $lessoni != NULL)
              {
                $marks[$key_d] = "";
              }
            }
          }

          if($marks[$key_d] == "" && $n_mark != 0)
          {
            $marks[$key_d] = $n_mark;
            $n_mark = 0;
          }



          if(isset($task_marks[$key_d]))
          {
            $pos = strpos($task_marks[$key_d], ",");

            if($pos !== false) { $id_date_mass = explode(",", $task_marks[$key_d]); }

            if($marks[$key_d] != "")
            {
              if(end($dates) == $date)
              {
                $marks[$key_d] = $task_marks[$key_d];
              }
              else
              {
                $n_mark = $task_marks[$key_d];
              }
            }
            else
            {
              $marks[$key_d] = $task_marks[$key_d];
            }

          }
        }

        if(substr($key_d, -1) == "t")
        {
          if(isset($task_marks[$date]))
          {
            $marks[$key_d] = $task_marks[$date];
          }
        }


      }

      $array_mark_student += [$student => $marks];
    }

    return $array_mark_student;
  }
  public static function getReminders(int $id_user)
  {
    $reminders = 0;
    $reminders = DB::table('reminders')->where('id_whom', $id_user)->get()->sortByDesc('created_at');
    return $reminders;
  }

  public static function getAva(int $id_user)
  {

    $ava = User::find($id_user)->img;
    return $ava;

  }
  public static function getDateWhereKtp(int $id_rs)
  {
    $dates = 0;

    $dates = DB::table('dates')->where('id_rs', $id_rs)->where('id_ktp', '!=', NULL)->get();
    $saves_date = array();

    foreach ($dates as $key => $date)
    {
      if(isset($previous))
      {
        if($date->date == $previous['date'] && $date->id_ktp == $previous['id_ktp'])
        {

        }
        else
        {
          $saves_date += [$date->id => $date];
          $previous = array('date' => $date->date, 'id_ktp' => $date->id_ktp);
        }
      }
      else
      {
        $previous = array('date' => $date->date, 'id_ktp' => $date->id_ktp);
      }
    }

    return $saves_date;
  }
  public static function getCountPneTypeKTP(int $id_rs, int $id_ktp, $date)
  {
    $count_dates = DB::table('dates')->where('id_rs', $id_rs)->where('id_ktp', $id_ktp)->where('date', $date)->count();
    return $count_dates;
  }
  public static function getInfoKtp(int $id_ktp)
  {
    $ktp = DB::table('ktps')->where('id', $id_ktp)->get();
    if(isset($ktp[0]))
    {
      return $ktp[0];
    }

  }
  public static function getDateKtp(int $id_ktp)
  {
    $dates = 0;

    $dates = DB::table('dates')->where('id_ktp', $id_ktp)->get();

    return $dates;

  }
  public static function getTaskStudentFive(int $id_rs, int $id_student, int $infotask_id)
  {

    $id_stud_work = DB::table('student_works')->where('id_student', $id_student)->where('id_rs', $id_rs)->where('id_task', $infotask_id)->get();

    if(isset($id_stud_work[0]))
    {
      return $id_stud_work[0];
    }

  }
  //Возвращает количество посещеных пар
  public static function getHaveLessonStudent(int $id_rs, int $id_student)
  {

    $lesson = DB::table('student_lessons')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('value', '>', 0)->sum('value');
    return round($lesson, 1);
  }
  public static function getBBStudent(int $id_rs, int $id_student)
  {
    $bb = DB::table('student_bonuses')->where('id_rs', $id_rs)->where('id_student', $id_student)->get();
    $bb_date = array();

    foreach ($bb as $b)
    {
      $bb_date += [$b->id => DateBonuse::find($b->id_date_bonuses)];
    }

    $data = array('bb' => $bb, 'bb_date' => $bb_date);

    return $data;
  }
  public static function getInfoMainTestStudent(int $id_rs, int $id_student)
  {
    $tasks = DB::table('info_tasks')->where('id_rs', $id_rs)->where('type', 'main_test')->get();

    $info_arr = array();
    foreach ($tasks as $task)
    {
      $info_arr += [$task->number => 0];
    }

    foreach ($tasks as $task)
    {
      $work = DB::table('student_works')->where('id_student', $id_student)->where('id_task', $task->id)->get();

      if(isset($work[0]))
      {
        if($work[0]->value > 0)
        {
          $info_arr[$task->number] = 2;
        }
        else
        {
          if($task->date_start != NULL && CalculateController::moreTodayDate($task->date_start))
          {
            $info_arr[$task->number] = 1;
          }

          if($task->date_end != NULL && CalculateController::moreTodayDate($task->date_end))
          {
            $info_arr[$task->number] = -1;
          }
        }
      }
    }

    return $info_arr;
  }


  public static function getInfoTestStudent(int $id_rs, int $id_student)
  {
    $tasks = DB::table('info_tasks')->where('id_rs', $id_rs)->where('type', 'test')->get();

    $info_arr = array();
    foreach ($tasks as $task)
    {
      $info_arr += [$task->number => 0];
    }

    foreach ($tasks as $task)
    {
      $work = DB::table('student_works')->where('id_student', $id_student)->where('id_task', $task->id)->get();

      if(isset($work[0]))
      {
        if($work[0]->value > 0)
        {
          $info_arr[$task->number] = 2;
        }
        else
        {
          if($task->date_start != NULL && CalculateController::moreTodayDate($task->date_start))
          {
            $info_arr[$task->number] = 1;
          }

          if($task->date_end != NULL && CalculateController::moreTodayDate($task->date_end))
          {
            $info_arr[$task->number] = -1;
          }
        }
      }
    }

    return $info_arr;
  }

  //массив со сданными не сданными работами
  public static function getInfoTaskStudent(int $id_rs, int $id_student, int $id_task_info)
  {
    $tasks = DB::table('info_tasks')->where('id_rs', $id_rs)->where('type', 'task')->where('id_info_task', $id_task_info)->get();

    $info_arr = array();
    foreach ($tasks as $task)
    {
      $info_arr += [$task->number => 0];
    }

    foreach ($tasks as $task)
    {
      $work = DB::table('student_works')->where('id_student', $id_student)->where('id_task', $task->id)->get();

      if(isset($work[0]))
      {
        if($work[0]->value > 0)
        {
          $info_arr[$task->number] = 2;
        }
        else
        {
          if($task->date_start != NULL && CalculateController::moreTodayDate($task->date_start))
          {
            $info_arr[$task->number] = 1;
          }

          if($task->date_end != NULL && CalculateController::moreTodayDate($task->date_end))
          {
            $info_arr[$task->number] = -1;
          }
        }
      }
    }

    return $info_arr;
  }

  public static function getTotalLessonStudent(int $id_rs, int $id_student)
  {
    $stud_lesson = DB::table('student_lessons')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('value', '>', 0)->sum('value');
    return $stud_lesson;
  }
  public static function getStudentWork(int $id_rs, int $id_student, int $id_task)
  {
    $stud_lesson = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('id_task', $id_task)->get();
    if(isset($stud_lesson[0]))
    {
      return $stud_lesson[0];
    }
  }
  public static function getTopStudents(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();
    $array_top = array();

    foreach ($students as $student)
    {
      $array_top += [$student->id => round(CalculateController::allScoreStudent($id_rs, $student->id))];
    }

    arsort($array_top);
    return $array_top;


  }
  public static function getDoneSubgroup(int $id_rs, int $id_student)
  {
    $summ = 0;
    $user = User::find($id_student);

    $dates = DB::table('dates')->where('id_rs', $id_rs)->where('date', '!=', NULL)->get();

    foreach ($dates as $date)
    {
      if($date->subgroup == 0 || $date->subgroup == NULL || $date->subgroup == $user->subgroup)
      {
        $summ++;
      }
    }

    return $summ;

  }

  //возвращает сумму прогулов
  public static function getHookySubgroupStudent(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $summd = CalculateController::getDoneSubgroup($id_rs, $id_student);
    $summ = CalculateController::getTotalLessonStudent($id_rs, $id_student);
    $progul = round($summd - $summ, 1);
    $percent = ($progul / ($rs->total_lesson + $rs->lesson_subgroup)) * 100;

    if($progul < 0) $progul = 0;

    $data = array('progul' =>  $progul, 'percent' => $percent);

    return $data;
  }

  public static function getHookyTOP(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();
    $arr_hooky = array();

    foreach ($students as $student)
    {
      $summd = CalculateController::getDoneSubgroup($id_rs, $student->id);
      $summ = CalculateController::getTotalLessonStudent($id_rs, $student->id);
      $arr_hooky += [$student->id => round($summd - $summ, 1)];
    }
    arsort($arr_hooky);


    return $arr_hooky;
  }

  public static function getDoneSubgroupStudent(int $id_rs, int $id_student)
  {
  }


  public static function getHookyStudent(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $stud_lesson = CalculateController::getTotalLessonStudent($id_rs, $id_student);
    $done_lesson = CalculateController::doneLessons($id_rs);
    $progul = round($done_lesson - $stud_lesson, 1);
    $percent = ($progul / ($rs->total_lesson + $rs->lesson_subgroup)) * 100;

    $data = array('progul' =>  $progul, 'percent' => $percent);

    return $data;
  }

  public static function getPercentLessonStudent(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $percent = 0;
    if($rs)
    {
      $stud_lesson = CalculateController::getTotalLessonStudent($id_rs, $id_student);
      $total_lesson = $rs->total_lesson + $rs->lesson_subgroup;
      if($total_lesson > 0)
      {
        $percent = ($stud_lesson / $total_lesson) * 100;
      }
    }

    return $percent;
  }

  public static function getLessonArrs(int $id_rs, Collection $students)
  {
    $dates = DB::table('dates')->where('id_rs', $id_rs)->get();

    $data = array();

    foreach($students as $student)
    {

      $stud_lesson = DB::table('student_lessons')->where('id_rs', $id_rs)->where('id_student', $student->id)->get();
      ${'less' . $student->id} = array();

      $arr_date_val = array();
      $arr_date_id = array();

      foreach ($dates as $date)
      {
        foreach ($stud_lesson as $lesson)
        {
          if($lesson->id_date == $date->id)
          {
            $arr_date_val += [$date->id => $lesson->value];
            $arr_date_id += [$date->id => $lesson->id];
          }

        }
      }
      ${'less' . $student->id} += [ 'value' => $arr_date_val];
      ${'less' . $student->id} += [ 'id' => $arr_date_id ];

      $data += ['less' . $student->id => ${'less' . $student->id}];
    }

    return $data;
  }

  public static function getLessonOBJ(int $id_date, int $id_student)
  {
    $stud_lesson = DB::table('student_lessons')->where('id_date', $id_date)->where('id_student', $id_student)->get();
    if($stud_lesson)
    {
      return $stud_lesson[0];
    }
  }

  public static function getArrScoreLesson(int $id_rs, Collection $students)
  {
    $lesson_score_student = array();

    foreach($students as $i => $student)
    {
      $lesson_score_student += [$student->id => CalculateController::scoreLessonStudent($id_rs, $student->id)];
    }

    return $lesson_score_student;
  }


  public static function getStillTaskType(int $id_rs, int $id_type)
  {

    $total = RSTask::find($id_type)->total_task;
    $done_task = CalculateController::countDoneTaskType($id_rs, $id_type);
    $still = $total - $done_task;

    return $still;
  }
  public static function getPercentDoneTaskType(int $id_rs, int $id_type)
  {
    $total = RSTask::find($id_type)->total_task;
    $done_task = CalculateController::countDoneTaskType($id_rs, $id_type);
    $percent = 0;

    if($total > 0)
    {
      $percent = ($done_task / $total) * 100;
    }

    return $percent;
  }

  public static function getStillLesson(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $total_lesson = $rs->total_lesson + $rs->lesson_subgroup;
    $done_lesson = CalculateController::doneLessons($id_rs);
    $still = 0;

    $still = $total_lesson - $done_lesson;

    if($still < 0) $still = 0;

    return $still;
  }

  public static function getStillLessonTEACH(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $total_lesson = $rs->total_lesson + ($rs->lesson_subgroup * 2);
    $done_lesson = CalculateController::doneLessons($id_rs);
    $still = 0;

    $still = $total_lesson - $done_lesson;

    if($still < 0) $still = 0;

    return $still;
  }

  public static function getPercentDoneLesson(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $total_lesson = $rs->total_lesson + $rs->lesson_subgroup;
    $done_lesson = CalculateController::doneLessons($id_rs);
    $percent = 0;

    if($total_lesson > 0)
    {
      $percent = ($done_lesson / $total_lesson) * 100;
    }
    return $percent;
  }

  public static function getPercentDoneLessonTEACH(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $total_lesson = $rs->total_lesson + ($rs->lesson_subgroup * 2);
    $done_lesson = CalculateController::doneLessonsTeacher($id_rs);
    $percent = 0;

    if($total_lesson > 0)
    {
      $percent = ($done_lesson / $total_lesson) * 100;
    }
    return $percent;
  }
  //отдает массив дат в удобном формате
  public static function getMassDateBonuse(int $id_rs)
  {
    $date_bonuse = DB::table('dates_bonuses')->where('id_rs', $id_rs)->get();
    $mass_date = array();

    foreach ($date_bonuse as $date) {
      if(isset($mass_date[$date->date]))
      {
        $mass_date[$date->date] += 1;
      }
      else
      {
        $mass_date += [$date->date => 1];
      }
    }

    return $mass_date;
  }
  //сравнивает какую-то дату с сегодняшней и если сегодня больше, то возвращает истину (типа если сроки прошли то тру)
  public static function moreTodayDate(string $date)
  {
    $today = date("Ymd");
    $one_date = substr($date, -4).substr($date, 3, 2).substr($date, 0, 2);


    $ret = false;
    if((int)$today >= (int)$one_date)
    {
      $ret = true;
    }
    return $ret;
  }


  //балл за лекцию
  public static function scoreOneLesson(int $id_rs)
  {
    $rs = RS::find($id_rs);
    if(($rs->total_lesson + $rs->lesson_subgroup) != 0)
    $score_one = $rs->total_lesson_score / ($rs->total_lesson + $rs->lesson_subgroup);

    return $score_one;
  }

  public static function getNameTask(int $id_task)
  {
    $task = InfoTask::find($id_task);
    $rstask = RSTask::find($task->id_info_task);

    return $rstask->name_task." №".$task->number;

  }

  //балл за практическую
  public static function scoreOneTask(int $id_rs, int $id_task)
  {

    $task = InfoTask::find($id_task);
    $score_task = 0;

    if($task->type == "task")
    {

      $score_task = $task->total_score;

      if($score_task == NULL && $task->def_score > 0)
      {
        $score_task = $task->def_score;
      }

      if($score_task == NULL)
      {
        $id_type_task = $task->id_info_task;
        $total_score_task = $task->rstask->total_task_score;
        $total_task = $task->rstask->total_task;

        $all_tasks = DB::table('info_tasks')->where('id_info_task', $id_type_task)->sum('total_score');

        $tasks = DB::table('info_tasks')->where('id_info_task', $id_type_task)->where('total_score', '>', 0)->count();

        if($total_task - $tasks > 0)
        {
          $score_task = ($total_score_task - $all_tasks) / ($total_task - $tasks);
        }
      }
    }

    return $score_task;
  }
  public static function scoreOneTest(int $id_rs, int $id_test)
  {
    $rs = RS::find($id_rs);
    $test = InfoTask::find($id_test);

    $score_test = $test->total_score;

    if($score_test == NULL && $test->def_score > 0)
    {
      $score_test = $test->def_score;
    }

    $total_question = $test->total_question;
    $type = $test->type;
    $total_score_test = 0;
    $total_test = 0;
    if($type == 'test')
    {
      $total_score_test = $rs->total_test_score;
      $total_test = $rs->total_test;
    }
    if($type == 'main_test')
    {
      $total_score_test = $rs->total_main_test_score;
      $total_test = $rs->total_main_test;
    }


    if($score_test <= 0)
    {
      $all_tasks = DB::table('info_tasks')->where('id_rs', $id_rs)->where('type', $type)->sum('total_score');

      $tests = DB::table('info_tasks')->where('id_rs', $id_rs)->where('type', $type)->where('total_score', '>', 0)->count();

      $score_test = ($total_score_test - $all_tasks) / ($total_test - $tests);
    }

    return $score_test;

  }

  //общая оценка по тестам
  public static function markMainTestStudent(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $all_student_tests = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('type', 'main_test')->get();
    $sum_mark_test = 0;
    $count = 0;
    $mark = '-';


    foreach ($all_student_tests as $student_test)
    {
      if($student_test->value > 0)
      {
        $test = StudentWork::find($student_test->id);

        $mark = CalculateController::markTest($student_test->id_rs, $test);


        $sum_mark_test += $mark;
        $count++;
      }
    }
    if($count > 0)
    {
      $mark = $sum_mark_test / $count;
    }

    return $mark;

  }



  //общая оценка по тестам
  public static function markTestStudent(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $all_student_tests = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('type', 'test')->get();
    $sum_mark_test = 0;
    $count = 0;
    $mark = '-';


    foreach ($all_student_tests as $student_test)
    {
      if($student_test->value > 0)
      {
        $test = StudentWork::find($student_test->id);
        $mark = CalculateController::markTest($student_test->id_rs, $test);

        $sum_mark_test += $mark;
        $count++;
      }
    }
    if($count > 0)
    {
      $mark = $sum_mark_test / $count;
    }

    return $mark;

  }

  //баллы за все тесты студента
  public static function scoreTestStudent(int $id_rs, int $id_student)
  {

    $all_student_tests = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('type', 'test')->where('value', '>', 0)->get();
    $sum_score_test = 0;


    foreach ($all_student_tests as $student_test)
    {
      $test = InfoTask::find($student_test->id_task);
      $total_question = $test->total_question;
      $one_quest = 0;
      if($total_question > 0)
      {
        $one_quest = CalculateController::scoreOneTest($id_rs, $student_test->id_task) / $total_question;
      }


      $sum_score_test += $student_test->value * $one_quest;
    }

    return round($sum_score_test, 1);

  }

  //баллы за все итоговые тесты студента
  public static function scoreMainTestStudent(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $all_student_tests = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('type', 'main_test')->where('value', '>', 0)->get();
    $sum_score_test = 0;


    foreach ($all_student_tests as $student_test)
    {
      $test = InfoTask::find($student_test->id_task);
      $total_question = $test->total_question;
      $one_quest = 0;
      if($total_question > 0)
      {
        $one_quest = CalculateController::scoreOneTest($id_rs, $student_test->id_task) / $total_question;
      }

      $sum_score_test += $student_test->value * $one_quest;
    }

    return round($sum_score_test, 1);

  }

  //процент правильных ответов в тесте
  public static function markTest(int $id_rs, StudentWork $studentwork)
  {
    $rs = RS::find($id_rs);
    $test = InfoTask::find($studentwork->id_task);
    $total_question = $test->total_question;
    $percent = 0;

    if($total_question > 0)
    {
      $percent = ($studentwork->value / $total_question) * 100;
    }
    $mark = CalculateController::getMark($id_rs,$percent);


    return $mark;
  }


  //возвращает сумму баллов за посещения конкретного студента, по конкретной брс
  public static function scoreLessonStudent(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $lessons = DB::table('student_lessons')->where('id_student', $id_student)->where('id_rs', $id_rs)->where('value', '>', 0)->get();

    $score_one_lesson = CalculateController::scoreOneLesson($id_rs);
    $summ_score_lesson = $lessons->sum('value') * $score_one_lesson;

    return round($summ_score_lesson, 1);

  }

  //возвращает сумму ББ конкретного студента, по конкретной брс
  public static function scoreBBStudent(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $bb = DB::table('student_bonuses')->where('id_student', $id_student)->where('id_rs', $id_rs)->get();

    $summ_bb = $bb->sum('value');

    return round($summ_bb, 1);

  }

  //возвращает оценку за тип практических типа за все лабы нарешал на 5
  public static function markTaskOneTypeStudent(int $id_rs, int $id_student, int $id_type)
  {
    $rs = RS::find($id_rs);
    $all_student_tasks = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('type', 'task')->get();
    $sum_score_tasks = 0;
    $count = 0;
    $mark = '-';

    if($all_student_tasks->count() > 0)
    {
        foreach ($all_student_tasks as $key => $student_task)
        {
          if(InfoTask::find($student_task->id_task)->id_info_task != $id_type)
          {
            $all_student_tasks->forget($key);
          }
        }

        foreach ($all_student_tasks as $student_task)
        {
          if($student_task->value > 0)
          {
            $sum_score_tasks += $student_task->value;
            $count++;
          }

        }

        if($count > 0)
        {
          $mark = $sum_score_tasks / $count;
        }

    }

    return $mark;
  }

  //возвращает сумму баллов за работы одного типа конкретного студента, по конкретной брс
  public static function scoreTaskOneTypeStudent(int $id_rs, int $id_student, int $id_type)
  {
    $rs = RS::find($id_rs);

    $type_tasks = DB::table('info_tasks')->where('id_info_task', $id_type)->pluck('id')->toArray();

    $all_student_tasks = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('type', 'task')->where('value', '!=', NULL)->get();
    $sum_score_tasks = 0;


    foreach ($all_student_tasks as $key => $student_task)
    {
      if(!in_array($student_task->id_task, $type_tasks))
      {
        $all_student_tasks->forget($key);
      }
    }

    foreach ($all_student_tasks as $student_task)
    {
        $sum_score_tasks += ($student_task->value / 100) * CalculateController::scoreOneTask($id_rs, $student_task->id_task);
    }

    return round($sum_score_tasks, 1);
  }
  public static function scoreOneTaskStudent(int $id_rs, int $id_student, int $id_task)
  {
    $work = DB::table('student_works')->where('id_student', $id_student)->where('id_task', $id_task)->get()->toArray();
    if(isset($work[0]))
    {
      $score_task = ($work[0]->value / 100) * CalculateController::scoreOneTask($id_rs, $id_task);
      return round($score_task);
    }
  }

  public static function scoreOneTestStudent(int $id_rs, int $id_student, int $id_task)
  {
    $work = DB::table('student_works')->where('id_student', $id_student)->where('id_task', $id_task)->get()->toArray();
    $info_test = DB::table('info_tasks')->where('id', $id_task)->get()->toArray();

    if(isset($work[0]) && isset($info_test[0]))
    {
      $one_quest = CalculateController::scoreOneTest($id_rs, $id_task) / $info_test[0]->total_question;

      $score_test = $work[0]->value * $one_quest;

      return $score_test;
    }
  }

  //отдает оценку за работы для студента без учета бонусных баллов
  public static function getMarkWithoutBonuse(int $id_rs, int $id_student)
  {
    $student_tasks = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->get();
    $mark_task = 0;
    $mark_count = 0;

    foreach ($student_tasks as $student_task)
    {
      if($student_task->value > 0)
      {
        $mark_task += CalculateController::getMark($id_rs, $student_task->value);
        $mark_count++;
      }
    }
    $main_mark = 0;
    if($mark_count > 0)
    {
      $main_mark = $mark_task / $mark_count;
    }
    return round($main_mark);
  }

  //возвращает оценку за процент
  public static function getMark(int $id_rs, $percent)
  {
    $rs = RS::find($id_rs);
    $five = $rs->infomarks->five;
    $four = $rs->infomarks->four;
    $three = $rs->infomarks->three;
    $mark = '-';
    if($percent > 0)
    {
      if($percent >= $five) $mark = 5;
      if($percent < $five && $percent >= $four) $mark = 4;
      if($percent < $four && $percent >= $three) $mark = 3;
      if($percent < $three) $mark = 2;
    }
    if($percent < 0)
    {
      $mark = 2;
    }

    return $mark;
  }

  //возвращает оценку за процент
  public static function getBMark(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $total = $rs->total_score;
    $arr_mark_b = array();

    $arr_mark_b += ['5' => ($rs->infomarks->five / 100) * $total];
    $arr_mark_b += ['4' => ($rs->infomarks->four / 100) * $total];
    $arr_mark_b += ['3' => ($rs->infomarks->three / 100) * $total];

    return $arr_mark_b;
  }

  //возвращает сумму посещений пар конкретного студента, по конкретной брс
  public static function sumLessonStudent(int $id_rs, int $id_student)
  {
    $lessons = DB::table('student_lessons')->where('id_student', $id_student)->where('id_rs', $id_rs)->get();

    return round($lessons->sum('value'), 1);
  }

  //возвращает оценку студента для 5-й системы
  public static function getMarkFive(int $id_rs, int $id_student)
  {
    $sum_mark = 0;
    $count_mark = 0;
    $rs = RS::find($id_rs);
    $main_mark = "-";

    foreach($rs->rstasks as $type_task)
    {
      $tasks = CalculateController::markTaskOneTypeStudent($rs->id, $id_student, $type_task->id);

      if(gettype($tasks) != 'string')
      {$sum_mark += $tasks;$count_mark++;}

    }

    $test_arr = CalculateController::markTestStudent($rs->id, $id_student);

    if(gettype($test_arr) != 'string')
    {$sum_mark += $test_arr;$count_mark++;}

    $main_test_arr = CalculateController::markMainTestStudent($rs->id, $id_student);

    if(gettype($main_test_arr) != 'string')
    {$sum_mark += $main_test_arr;$count_mark++;}

    if($count_mark > 0)
    {
      $main_mark = $sum_mark / $count_mark;
    }

    return $main_mark;

  }



  //возвращает процент посещений пар конкретного студента, по конкретной брс
  public static function percentLessonStudent(int $id_rs, int $id_student)
  {
    $done_lesson = CalculateController::doneLessons($id_rs);
    $sum_lesson = CalculateController::sumLessonStudent($id_rs, $id_student);

    if($done_lesson != 0)
    $percent = ($sum_lesson / $done_lesson) * 100;

    return  round($percent, 1);

  }

  //возвращает сколько пар прошли на данный момент по брс ЭТО ДЛЯ ПРЕПОДА
  public static function doneLessons(int $id_rs)
  {
    $lessons = DB::table('student_lessons')->where('id_rs', $id_rs)->get();
    $dates = DB::table('dates')->where('id_rs', $id_rs)->where('date', '!=', 'NULL')->get();
    $mass_date = array();
    $subgroup_lesson = 0;

    foreach ($dates as $date)
    {
      $mass_date += [$date->id => 0];

      if($date->subgroup >= 1)
      {
        $subgroup_lesson++;
      }
    }

    foreach ($dates as $date)
    {
      foreach ($lessons as $lesson)
      {
        if($lesson->id_date == $date->id && $mass_date[$date->id] < $lesson->value && $date->subgroup < 1)
        {
          $mass_date[$date->id] = $lesson->value;
        }


      }
    }

    $done_lessons = array_sum($mass_date);
    $done_lessons += $subgroup_lesson / 2;



    return round($done_lessons, 1);
  }

  public static function doneLessonsTeacher(int $id_rs)
  {
    $lessons = DB::table('student_lessons')->where('id_rs', $id_rs)->get();
    $dates = DB::table('dates')->where('id_rs', $id_rs)->where('date', '!=', 'NULL')->get();
    $mass_date = array();
    $subgroup_lesson = 0;

    foreach ($dates as $date)
    {
      $mass_date += [$date->id => 0];

      if($date->subgroup >= 1)
      {
        $subgroup_lesson++;
      }
    }

    foreach ($dates as $date)
    {
      foreach ($lessons as $lesson)
      {
        if($lesson->id_date == $date->id && $mass_date[$date->id] < $lesson->value && $date->subgroup < 1)
        {
          $mass_date[$date->id] = $lesson->value;
        }


      }
    }

    $done_lessons = array_sum($mass_date);
    $done_lessons += $subgroup_lesson;



    return round($done_lessons, 1);
  }


  //возвращает какие тесты надо было сдать
  public static function doneTest(int $id_rs)
  {

    $tests = DB::table('info_tasks')->where('id_rs', $id_rs)->where('type', 'test')->get();
    $total_must_test = array();
    $today = date('Ymd');

    foreach ($tests as $test)
    {
        $total_must_test += [$test->id => 0];
    }

    foreach ($tests as $test)
    {


        $date = substr($test->date_end, -4).substr($test->date_end, 3, 2).substr($test->date_end, 0, 2);


        if($date != "" && (int)$date <= (int)$today)
        {
          $total_must_test[$test->id] = 1;
        }

    }

    return $total_must_test;
  }

  //возвращает сколько тестов надо было сдать
  public static function countDoneTest(int $id_rs)
  {
    $done_test = CalculateController::doneTest($id_rs);
    $count = 0;
    foreach ($done_test as $test)
    {
      if($test > 0)
      {
        $count++;
      }
    }
    return $count;
  }

  public static function getStillTest(int $id_rs)
  {

    $total = RS::find($id_rs)->total_test;
    $done_test = CalculateController::countDoneTest($id_rs);
    $still = $total - $done_test;

    return $still;
  }


  public static function getPercentDoneTest(int $id_rs)
  {
    $total = RS::find($id_rs)->total_test;
    $done_test = CalculateController::countDoneTest($id_rs);
    $percent = 0;

    if($total > 0)
    {
      $percent = ($done_test / $total) * 100;
    }

    return $percent;
  }



  //возвращает сколько баллов можно было получить за тесты
  public static function doneScoreTest(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $done_test = CalculateController::doneTest($id_rs);
    $total_test = 0;

    foreach ($done_test as $key => $test)
    {
      $total_test += $test * CalculateController::scoreOneTest($id_rs, $key);
    }

    return $total_test;
  }

  //возвращает сколько баллов можно было получить за итоговые тесты
  public static function doneScoreMainTest(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $done_test = CalculateController::doneMainTest($id_rs);
    $total_test = 0;

    foreach ($done_test as $key => $test)
    {
      $total_test += $test * CalculateController::scoreOneTest($id_rs, $key);
    }

    return $total_test;
  }

  public static function getPercentDoneMainTest(int $id_rs)
  {
    $total = RS::find($id_rs)->total_main_test;
    $done_test = CalculateController::countDoneMainTest($id_rs);
    $percent = 0;

    if($total > 0)
    {
      $percent = ($done_test / $total) * 100;
    }

    return $percent;
  }


  //возвращает сколько тестов надо было сдать
  public static function countDoneMainTest(int $id_rs)
  {
    $done_test = CalculateController::doneMainTest($id_rs);
    $count = 0;
    foreach ($done_test as $test)
    {
      if($test > 0)
      {
        $count++;
      }
    }
    return $count;
  }

  public static function getStillMainTest(int $id_rs)
  {

    $total = RS::find($id_rs)->total_main_test;
    $done_test = CalculateController::countDoneMainTest($id_rs);
    $still = $total - $done_test;

    return $still;
  }


  //возвращает какие итоговые тесты надо было сдать
  public static function doneMainTest(int $id_rs)
  {

    $tests = DB::table('info_tasks')->where('id_rs', $id_rs)->where('type', 'main_test')->get();
    $total_must_test = array();
    $today = date('Ymd');

    foreach ($tests as $test)
    {

        $total_must_test += [$test->id => 0];
    }

    foreach ($tests as $test)
    {
        $date = substr($test->date_end, -4).substr($test->date_end, 3, 2).substr($test->date_end, 0, 2);


        if($date != "" && (int)$date < (int)$today)
        {
          $total_must_test[$test->id] = 1;
        }

    }

    return $total_must_test;
  }


  //возвращает сколько работ пройдено
  public static function countDoneTaskType(int $id_rs, int $id_type)
  {
    $done_test = CalculateController::doneTask($id_rs, $id_type);
    $count = 0;
    foreach ($done_test as $test)
    {
      if($test > 0)
      {
        $count++;
      }
    }
    return $count;
  }



  //возвращает какие работы надо было сдать
  public static function doneTask(int $id_rs, int $id_type)
  {

    $tests = DB::table('info_tasks')->where('id_rs', $id_rs)->where('type', 'task')->where('id_info_task', $id_type)->get();
    $total_must_test = array();
    $today = date('Ymd');

    foreach ($tests as $test)
    {

        $total_must_test += [$test->id => 0];
    }

    foreach ($tests as $test)
    {
        $date = substr($test->date_end, -4).substr($test->date_end, 3, 2).substr($test->date_end, 0, 2);


        if($date != "" && (int)$date < (int)$today)
        {
          $total_must_test[$test->id] = 1;
        }

    }

    return $total_must_test;
  }

  //возвращает сколько баллов можно было получить за работы
  public static function doneScoreTask(int $id_rs, int $id_type)
  {
    $rs = RS::find($id_rs);
    $done_test = CalculateController::doneTask($id_rs, $id_type);
    $total_test = 0;

    foreach ($done_test as $key => $test)
    {
      $total_test += $test * CalculateController::scoreOneTask($id_rs, $key);
    }

    return $total_test;
  }



  //просчитывает процент полученных баллов за посещения
  public static function donePercentLessons(int $id_rs, int $id_student)
  {
    $done_lesson = CalculateController::doneLessons($id_rs);
    $score_one_lesson = CalculateController::scoreOneLesson($id_rs);
    $done_score = $done_lesson * $score_one_lesson;

    $score_student = CalculateController::scoreLessonStudent($id_rs, $id_student);
    $done_percent_student = 0;

    if($done_score != 0)
    $done_percent_student = ($score_student / $done_score) * 100;

    return round($done_percent_student, 1);
  }

  //просчитывает все баллы студента
  public static function allScoreStudent(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $sum_score = 0;
    if($rs)
    {
      $sum_score += CalculateController::scoreLessonStudent($id_rs, $id_student); //суммирует посещаемость

      foreach($rs->rstasks as $task)
      {
        $sum_score += CalculateController::scoreTaskOneTypeStudent($id_rs, $id_student, $task->id); //суммирует практические
      }

      if($rs->total_test > 0)
      {
        $sum_score += CalculateController::scoreTestStudent($id_rs, $id_student); //суммирует тесты
      }

      if($rs->total_main_test > 0)
      {
        $sum_score += CalculateController::scoreMainTestStudent($id_rs, $id_student); //суммирует итоговые
      }

      if($rs->bonuse > 0)
      {
        $sum_score += CalculateController::scoreBBStudent($id_rs, $id_student); //суммирует бонусные
      }
    }

    return round($sum_score, 1);

  }

  //возвращает процент заработанных баллов относительно всех баллов
  public static function getPercentStudent(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $sum_score = CalculateController::allScoreStudent($id_rs, $id_student);
    $percent = 0;
    if($rs->total_score > 0)
    {
      $percent = ($sum_score / $rs->total_score) * 100;
    }

    return $percent;
  }

  public static function getPercentStudentOPT(int $id_rs, int $id_student, int $sum_score)
  {
    $rs = RS::find($id_rs);
    $percent = 0;
    if($rs->total_score > 0)
    {
      $percent = ($sum_score / $rs->total_score) * 100;
    }

    return $percent;
  }

  //отдает оценку студента
  public static function getmarkStudent(int $id_rs, int $id_student)
  {
    $rs = RS::find($id_rs);
    $sum_score = CalculateController::allScoreStudent($id_rs, $id_student);
    $percent = 0;
    if($rs->total_score > 0)
    {
      $percent = ($sum_score / $rs->total_score) * 100;
    }

    $mark = CalculateController::getMark($id_rs, $percent);
    return $mark;
  }

  //отдает оценку студента
  public static function getmarkStudentOPT(int $id_rs, int $id_student, int $sum_score)
  {
    $rs = RS::find($id_rs);
    $percent = 0;
    if($rs->total_score > 0)
    {
      $percent = ($sum_score / $rs->total_score) * 100;
    }

    $mark = CalculateController::getMark($id_rs, $percent);
    return $mark;
  }

  //отдает сколько всего можно было получить баллов типа это 100% для аттестации
  public static function getAttScore(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $total_score_att = 0;
    $score_one_lecture = CalculateController::scoreOneLesson($id_rs);

    $total_score_att += CalculateController::doneLessons($id_rs) * $score_one_lecture;
    if($rs->total_test >0)
    {
      $total_score_att += CalculateController::doneScoreTest($id_rs);
    }

    if($rs->total_main_test >0)
    {
      $total_score_att += CalculateController::doneScoreMainTest($id_rs);
    }

    foreach ($rs->rstasks as $task)
    {
      $total_score_att += CalculateController::doneScoreTask($id_rs, $task->id);
    }

    return round($total_score_att, 1);
  }


  //отдает сколько процентов студент заработал от аттестации
  public static function getAttPercent(int $id_rs, int $id_student)
  {
    $percent = 0;
    $total_score_att = CalculateController::getAttScore($id_rs);
    $total_score_student = CalculateController::allScoreStudent($id_rs, $id_student);

    if($total_score_att > 0)
    {
      $percent =  ($total_score_student / $total_score_att) * 100;
    }

    return round($percent, 1);

  }

  //отдает сколько процентов студент заработал от аттестации
  public static function getAttPercentOPT(int $id_rs, int $id_student, int $total_score_att, int $total_score_student)
  {
    $percent = 0;

    if($total_score_att > 0)
    {
      $percent =  ($total_score_student / $total_score_att) * 100;
    }

    return round($percent, 1);

  }





  //аттестация по оценкам которые увеличены с помощью ББ
  public static function getAttMarkHard(int $id_rs, int $id_student)
  {
    $mark_arr = RSController::getMarksTaskPaperJurnal($id_rs, $id_student);
    $summ = 0;
    $main_mark = 0;
    $count_mark = 0;
    foreach ($mark_arr as $mark)
    {
      if($mark > 0 && $mark != "-")
      {
        $summ += (int)$mark;
        $count_mark++;

      }
    }
    if($count_mark > 0)
    {
      $main_mark = $summ / $count_mark;
    }


    return round($main_mark);

  }

  //отдает оценку студента
  public static function getAttMark(int $id_rs, int $id_student)
  {
    $percent = CalculateController::getAttPercent($id_rs, $id_student);
    $mark = CalculateController::getMark($id_rs,$percent);

    return $mark;

  }

  public static function getAttMarkOPT(int $id_rs, int $id_student, int $percent)
  {
    $mark = CalculateController::getMark($id_rs,$percent);

    return $mark;

  }


  //возвращает студентов которые присутствуют на паре
  public static function studentThereToday(int $id_rs)
  {
    date_default_timezone_set('Europe/Moscow');

    $today = date("d.m");
    $dates = DB::table('dates')->where('id_rs', $id_rs)->where('date', $today)->get();
    $count_dates = count($dates);
    $id_there = array();

    if($count_dates > 1)
    {
      for ($i=1; $i < $count_dates; $i++)
      {
        if($dates[$i-1] < $dates[$i])
        {
          $dates->forget($i-1);
        }
      }
    }

    $date = $dates->toArray();
    if(!empty($date))
    {
      $lessons = DB::table('student_lessons')->where('id_rs', $id_rs)->where('id_date', array_shift($date)->id)->get();

        foreach ($lessons as $key => $lesson)
        {
          if($lesson->value != 0 || $lesson->value != NULL)
          {
            array_push($id_there, $lesson->id_student);
          }
        }
    }

    return $id_there;
  }

  //отдает фио юзера
  public static function getFIO(int $id_user)
  {
    $user = User::find($id_user);

    $fio = $user->surname." ".mb_substr($user->name, 0, 1).".".mb_substr($user->patronymic, 0, 1).".";

    return $fio;

  }



  //отдает студов первой подгруппы
  public static function studentSubgroup1(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $students_there_id = CalculateController::studentThereToday($id_rs);
    $students = DB::table('users')->where('id_group', $rs->id_group)->where('subgroup', 1)->get();
    $one_subgroup = array();

    foreach ($students_there_id as $student_there)
    {
      foreach ($students as $student)
      {
        if($student->id == $student_there)
        {
          array_push($one_subgroup, $student_there);
        }
      }
    }

    return $one_subgroup;
  }

  //отдает студов второй подгруппы
  public static function studentSubgroup2(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $students_there_id = CalculateController::studentThereToday($id_rs);
    $students = DB::table('users')->where('id_group', $rs->id_group)->where('subgroup', 2)->get();
    $two_subgroup = array();

    foreach ($students_there_id as $student_there)
    {
      foreach ($students as $student)
      {
        if($student->id == $student_there)
        {
          array_push($two_subgroup, $student_there);
        }
      }
    }

    return $two_subgroup;
  }


  public static function studentAtt5(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $students_there_id = CalculateController::studentThereToday($id_rs);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();
    $att_five = array();

    foreach ($students_there_id as $student_there)
    {
      foreach ($students as $student)
      {
        if($student->id == $student_there && CalculateController::getAttMark($id_rs, $student_there) == 5)
        {
          array_push($att_five, $student_there);
        }
      }
    }

    return $att_five;
  }

  public static function studentAtt4(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $students_there_id = CalculateController::studentThereToday($id_rs);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();
    $att = array();

    foreach ($students_there_id as $student_there)
    {
      foreach ($students as $student)
      {
        if($student->id == $student_there && CalculateController::getAttMark($id_rs, $student_there) == 4)
        {
          array_push($att, $student_there);
        }
      }
    }

    return $att;
  }

  public static function studentAtt3(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $students_there_id = CalculateController::studentThereToday($id_rs);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();
    $att = array();

    foreach ($students_there_id as $student_there)
    {
      foreach ($students as $student)
      {
        if($student->id == $student_there && CalculateController::getAttMark($id_rs, $student_there) == 3)
        {
          array_push($att, $student_there);
        }
      }
    }

    return $att;
  }

  public static function studentAtt2(int $id_rs)
  {
    $rs = RS::find($id_rs);
    $students_there_id = CalculateController::studentThereToday($id_rs);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();
    $att = array();

    foreach ($students_there_id as $student_there)
    {
      foreach ($students as $student)
      {
        if($student->id == $student_there && CalculateController::getAttMark($id_rs, $student_there) == 2)
        {
          array_push($att, $student_there);
        }
      }
    }

    return $att;
  }


}
