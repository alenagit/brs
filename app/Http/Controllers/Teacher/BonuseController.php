<?php

namespace App\Http\Controllers\Teacher;


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
use App\InfoTask;
use App\RSTask;
use App\UserParam;
use App\RSRand;
use App\DateBonuse;
use App\ValueRand;
use App\Http\Controllers\Student\CalculateController;


class BonuseController extends Controller
{

  public static function saveValue(Request $request)
  {
    $rand = ValueRand::create([
      'id_rs' => $request->id_rs,
      'value' => $request->value,
      'type' => $request->type,
    ]);

  }
  public static function delValue(Request $request)
  {
    ValueRand::where('id', $request->id)->delete();
  }



  public static function addInfoBB(Request $request)
  {
    $date_bonuse = DateBonuse::find($request->id);
    $color_rgba = $request->color.'66';
    if($date_bonuse)
    {
      $date_bonuse->name = $request->name;
      $date_bonuse->comment = $request->comment;
      $date_bonuse->color = $request->color;
      $date_bonuse->color_rgba = $color_rgba;

      $date_bonuse->save();
    }

  }

  public static function addCommentBB(Request $request)
  {
    $student_bonuse = StudentBonuse::find($request->id);
    $operaion = $request->operation;
    $new_val = 0;


    if($operaion != "null")
    {
      switch ($operaion)
      {
      case "0":
            $new_val = $student_bonuse->value + $request->value;
            $student_bonuse->value = $new_val;
            break;
      case "1":
      $new_val = $student_bonuse->value - $request->value;
            $student_bonuse->value = $new_val;
            break;
      case "2":
      $new_val = $student_bonuse->value * $request->value;
            $student_bonuse->value = $new_val;
            break;
      case "3":
            $new_val = $student_bonuse->value / $request->value;
            $student_bonuse->value = $new_val;
            break;
      }
    }

    $student_bonuse->comment = $request->comment;
    $student_bonuse->save();

    $summ = CalculateController::scoreBBStudent($student_bonuse->id_rs, $student_bonuse->id_student);
    $data = array('summ' => $summ, 'new_val' => $new_val);
    return $data;

  }

  public static function saveTheme(Request $request)
  {
    $rand = ValueRand::create([
      'id_rs' => $request->id_rs,
      'value' => $request->value,
      'type' => $request->type,
    ]);

  }

  //изменение ББ
  public static function updateBB(Request $request)
  {
    $value = 0;
    if($request->value != "")
    {
      $value = $request->value;
    }
    $student_bonuse = StudentBonuse::find($request->id);
    $student_bonuse->value = $value;
    $student_bonuse->save();

    return CalculateController::scoreBBStudent($request->id_rs, $request->id_student);

  }
  //удаление столбца из ББ
  public static function delColumnBB(Request $request)
  {
    $date_bonuse = DateBonuse::find($request->id);
    if($date_bonuse)
    {
      StudentBonuse::where('id_date_bonuses', $date_bonuse->id)->delete();
      DateBonuse::where('id', $request->id)->delete();
    }
  }

  //добавление столбца в ББ
  public static function addColumnBB(Request $request)
  {
    date_default_timezone_set('Europe/Moscow');

    $today = date("d.m");

    $rs = RS::find($request->id_rs);
    $id_rs = $rs->id;
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();


    $rs_rand = DB::table('rs_rands')->where('id_rs', $id_rs)->get(); //смотрю что есть в рандоме

    if($rs_rand == "[]") //если такой брс нет, создаем
    {
      $rand = RSRand::create([
        'id_rs' => $id_rs,
        'rand_date' => $today,
        'rand_round' => 1,
        'theme' => $request->name
      ]);
      $rs_rand = $rand;
    }
    else
    {
      if(isset($rs_rand[0]))
      {
        $rand_model = RSRand::find($rs_rand[0]->id);

        if($rand_model->rand_date != $today) //если записи для данной БРС уже были, но они вчерашние то тогда обнуляй
        {
          $rand_model->rand_date = $today;
          $rand_model->rand_round = 1;
          $rand_model->theme = $request->name;
          $rand_model->save();
        }
        else
        {
          $rand_model->rand_round = $rand_model->rand_round + 1;
          $rand_model->theme = $request->name;
          $rand_model->save();
        }

        $rs_rand = $rand_model;
      }
    }



    $date_bonuse = DateBonuse::create([
      'id_rs' => $id_rs,
      'name' => $request->name,
      'date' => $today,
      'round' => $rs_rand->rand_round
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
    }
  }


  public static function getListStudentForRand(Request $request)
  {
    $students = 0;
    $param = $request->param;
    $id_rs = $request->id_rs;

    switch ($param)
    {
    case "def":
          $students = CalculateController::studentThereToday($id_rs);
          break;
    case "sub1":
          $students = CalculateController::studentSubgroup1($id_rs);
          break;
    case "sub2":
          $students = CalculateController::studentSubgroup2($id_rs);
          break;
    case "att5":
          $students = CalculateController::studentAtt5($id_rs);
          break;
    case "att4":
          $students = CalculateController::studentAtt4($id_rs);
          break;
    case "att3":
          $students = CalculateController::studentAtt3($id_rs);
          break;
    case "att2":
          $students = CalculateController::studentAtt2($id_rs);
          break;
    }

    return $students;
  }

  public static function getRand(Request $request)
  {
    date_default_timezone_set('Europe/Moscow');

    $students = "";
    $list_will = ""; //студенты,ч то еще будут выпадать в рандоме
    $list_was = ""; //студенты которые уже выпадали
    $rand = "";
    $id_rs = $request->id_rs;
    $theme = $request->theme;
    $today = date("d.m");
    $selected_student = 0;
    $str_students_was = "";
    $fio = "";
    $round = 1;
    $message = "";
    $param = 0;
    $fresh = 0;

    if (gettype($request->param) == "string")
    {
      $param = $request->param;
    }
    else {
      $param = 'list';
    }


    //беру список студентов по параметру
    if (gettype($request->param) == "string")
    { $students = BonuseController::getListStudentForRand($request); }
    else { $students = $request->param; }


    $rs_rand = DB::table('rs_rands')->where('id_rs', $id_rs)->get(); //смотрю что есть в рандоме
    $rand_model = "";


    if($rs_rand == "[]") //если такой брс нет, создаем
    {
      $str_students = implode(",", $students);

      $rand = RSRand::create([
        'id_rs' => $id_rs,
        'rand_will' => $str_students,
        'rand_date' => $today,
        'rand_round' => 1,
        'theme' => $theme,
        'type' => $param,
      ]);
    }
    else
    {
      if(isset($rs_rand[0]))
      {
        $rand_model = RSRand::find($rs_rand[0]->id);

        if($rand_model->rand_date != $today) //если записи для данной БРС уже были, но они вчерашние то тогда обнуляй
        {
          $str_students = implode(",", $students);

          $rand_model->rand_date = $today;
          $rand_model->rand_round = 1;
          $rand_model->type = $param;
          $rand_model->rand_will = $str_students;
          $rand_model->theme = $theme;
          $rand_model->rand_was = "";
          $rand_model->save();
        }
        else //если дата верная
        {
          if($rand_model->type != $param) //если изменился тип
          {
            $str_students = implode(",", $students);

            $rand_model->rand_round = $rand_model->rand_round + 1;
            $rand_model->type = $param;
            $rand_model->rand_will = $str_students;
            $rand_model->theme = $theme;
            $rand_model->rand_was = "";
            $rand_model->save();
          }

          if($rand_model->theme != $theme) //если изменился тип
          {
            $str_students = implode(",", $students);

            $rand_model->rand_round = $rand_model->rand_round + 1;
            $rand_model->type = $param;
            $rand_model->rand_will = $str_students;
            $rand_model->theme = $theme;
            $rand_model->rand_was = "";
            $rand_model->save();
          }


          if(empty($rand_model->rand_will)) //если изменился тип
          {
            $str_students = implode(",", $students);

            $rand_model->rand_round = $rand_model->rand_round + 1;
            $rand_model->rand_will = $str_students;
            $rand_model->rand_was = "";
            $rand_model->theme = $theme;
            $rand_model->save();


          }
        }

      }
    }

    $rs_rand = DB::table('rs_rands')->where('id_rs', $id_rs)->get(); //смотрю что есть в рандоме

    if(isset($rs_rand[0]))
    {
      $rand_model = RSRand::find($rs_rand[0]->id);
      $list_will = explode(",", $rand_model->rand_will); //перевожу строку в массив для рандома
      $list_was = array();
      if($rand_model->rand_was != "") {$list_was = explode(",", $rand_model->rand_was); } //перевожу строку в массив для рандома

      $selected_student = array_rand($list_will, 1); //рандомом выбераю студа
      $selected_student = $list_will[$selected_student];

      array_push($list_was, $selected_student); //добавила исключенного студа в строку

      if(($key = array_search($selected_student, $list_will)) !== FALSE)
      {
      unset($list_will[$key]); //исключила студа из будущих рандомов
      }


      $str_students_will = implode(",", $list_will);
      $str_students_was = implode(",", $list_was);
      $fio = CalculateController::getFIO($selected_student);
      $round = $rand_model->rand_round;


      $rand_model->rand_will = $str_students_will;
      $rand_model->rand_was = $str_students_was;
      $rand_model->save();

      $date_bonuse = DB::table('dates_bonuses')->where('id_rs', $rand_model->id_rs)->where('date', $rand_model->rand_date)->where('round', $rand_model->rand_round)->get();

      if($date_bonuse)
      {
        if(isset($date_bonuse[0]))
        {
          $date_bonuse = DateBonuse::find($date_bonuse[0]->id);
          $rs = RS::find($request->id_rs);

          $student_bonuse = StudentBonuse::create([
            'id_rs' => $rs->id,
            'id_student' => $selected_student,
            'id_group' => $rs->id_group,
            'value' => 0,
            'id_date_bonuses' => $date_bonuse->id
          ]);

          $fresh = 1;
        }

      }/*
      else
      {
        $rs = RS::find($request->id_rs);

        $date_bonuse = DateBonuse::create([
          'id_rs' => $rs->id,
          'name' => $request->name,
          'date' => $rand_model->rand_date,
          'round' => $rs_rand->rand_round
        ]);

        $student_bonuse = StudentBonuse::create([
          'id_rs' => $rs->id,
          'id_student' => $selected_student,
          'id_group' => $rs->id_group,
          'value' => 0,
          'id_date_bonuses' => $date_bonuse->id
        ]);

      }*/

      if($str_students_will == ""){$message = "Это последний студент в списке.";}


    }


    $data = array(
      'round' => $round,
      'fio' => $fio,
      'student' => $selected_student,
      'was' => $str_students_was,
      'will' => $str_students_will,
      'message' => $message,
      'fresh' => $fresh
    );

    return $data;
  }
  public static function right(Request $request)
  {
    $id_rs = $request->id_rs;
    $rs = RS::find($id_rs);

    $id_student = $request->id_student;

    $value = $request->value;


    $theme = $request->theme;
    $date = "";
    $round = 0;
    $id_date = 0;

    $rs_rand = DB::table('rs_rands')->where('id_rs', $id_rs)->get();

    if(isset($rs_rand[0]))
    {
      $rand_model = RSRand::find($rs_rand[0]->id);

      $date = $rand_model->rand_date;
      $round = $rand_model->rand_round;
    }

    $date_bonuse = DB::table('dates_bonuses')->where('id_rs', $id_rs)->where('name', $theme)->where('date', $date)->where('round', $round)->get();

    if(!isset($date_bonuse[0]))
    {
      $date_bonuse = DateBonuse::create([
        'id_rs' => $id_rs,
        'name' => $theme,
        'date' => $date,
        'round' => $round
      ]);

    }

    if($date_bonuse)
    {
      if(isset($date_bonuse[0]))
      {
        $id_date = $date_bonuse[0]->id;
      } else {$id_date = $date_bonuse->id;}


      $student_bonuse = DB::table('student_bonuses')->where('id_date_bonuses', $id_date)->where('id_student', $id_student)->get();

      if(!isset($student_bonuse[0]))
      {
        $student_bonuse = StudentBonuse::create([
          'id_date_bonuses' => $id_date,
          'id_rs' => $id_rs,
          'id_student' => $id_student,
          'value' => $value,
          'id_group' => $rs->id_group
        ]);
      }
      else
      {
        $student_bonuse = StudentBonuse::find($student_bonuse[0]->id);

        $student_bonuse->value = $student_bonuse->value + $value;
        $student_bonuse->save();
      }

    }

  }
}
