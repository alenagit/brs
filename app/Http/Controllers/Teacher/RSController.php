<?php

namespace App\Http\Controllers\Teacher;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\CreateRSRequest;
use App\Http\Requests\EditRSRequest;
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
use App\ValueRand;
use App\RSRand;
use App\DateBonuse;
use App\Reminder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;

use \App\Http\Controllers\Student\CalculateController;



class RSController extends Controller
{

  public function delStudent(Request $request)
  {
    StudentLesson::where('id_student', $request->id)->delete();
    StudentWork::where('id_student', $request->id)->delete();
    StudentBonuse::where('id_student', $request->id)->delete();
    Reminder::where('id_whom', $request->id)->delete();
    User::where('id', $request->id)->delete();
  }


  public static function getDatesPaperOPT(int $id)
  {

    $dates = DB::table('dates')->where('id_rs', $id)->get();
    $tasks = DB::table('info_tasks')->where('id_rs', $id)->get();

    $dates_mass = array();

    foreach ($dates as $date)
    {
      if($date->date != NULL)
      {
        $dates_mass += [(($date->id) . '.d') => ( substr($date->date, 3, 2).substr($date->date, 0, 2) )];
      }
    }

    foreach ($tasks as $task)
    {
      if($task->date_start != NULL)
      {
        $dates_mass += [(($task->id) . '.t') => ( substr($task->date_start, 3, 2).substr($task->date_start, 0, 2) )];
      }
    }

    asort($dates_mass);

    $imp_mass_dates = array();

    foreach ($dates_mass as $key => $mdate)
    {
      if(substr($key, -1) == "d")
      {
        if(isset($imp_mass_dates[($mdate . '.d')]))
        {
          $imp_mass_dates[($mdate . '.d')] = $imp_mass_dates[($mdate . '.d')].",".mb_substr($key, 0, -2);
        }
        else
        {
          $imp_mass_dates += [($mdate. '.d') => mb_substr($key, 0, -2)];
        }
      }
      else
      {
        $imp_mass_dates += [($mdate . '.t') => mb_substr($key, 0, -2)];
      }
    }

    return $imp_mass_dates;
  }


  public static function getDatesPaper(int $id)
  {

    $dates = DB::table('dates')->where('id_rs', $id)->get();
    $tasks = DB::table('info_tasks')->where('id_rs', $id)->get();

    $dates_mass = array();

    foreach ($dates as $date)
    {
      if($date->date != NULL)
      {
        $dates_mass += [(($date->id) . '.d') => ( substr($date->date, 3, 2).substr($date->date, 0, 2) )];
      }
    }

    foreach ($tasks as $task)
    {
      if($task->date_start != NULL)
      {
        $dates_mass += [(($task->id) . '.t') => ( substr($task->date_start, 3, 2).substr($task->date_start, 0, 2) )];
      }
    }


    asort($dates_mass);

    return $dates_mass;
  }

  public static function getDates(int $id)
  {

    $dates = DB::table('dates')->where('id_rs', $id)->get();
    $dates_mass = array();

    foreach ($dates as $date)
    {
      if($date->date != NULL && $date->type <= 0)
      {
        array_push($dates_mass, $date->date);
      }
    }
    return $dates_mass;
  }


  //Отдает массив оценок все работ студента для одной брс
  public static function getMarksTaskPaperJurnal(int $id_rs, int $id_student)
  {
    $mark = CalculateController::getAttMark($id_rs, $id_student);
    $bonuses = CalculateController::scoreBBStudent($id_rs, $id_student);
    $task_marks = array();

    $rs = RS::find($id_rs);
    $five = $rs->infomarks->five;
    $four = $rs->infomarks->four;
    $three = $rs->infomarks->three;

    $need_percent = 0;

    switch ($mark)
    {
      case 5:
            $need_percent = $five;
            break;
      case 4:
            $need_percent = $four;
            break;
      case 3:
            $need_percent = $three;
            break;
    }


    $student_tasks = DB::table('student_works')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('type', 'task')->get();

    foreach ($student_tasks as $student_task)
    { $task = InfoTask::find($student_task->id_task);
      if($task)
      {
        if($student_task->value != 0 || ($task->date_end != NULL && $student_task->value == 0 && CalculateController::moreTodayDate($task->date_end)))
        {
          $mark_task = CalculateController::getMark($id_rs, $student_task->value);

          $task_marks += [$student_task->id_task => $mark_task];
        }
      }
    }

    $student_tests = StudentWork::where('id_rs', $id_rs)->where('id_student', $id_student)->get(); //нужно чтобы был тип МОДЕЛЬ


    foreach ($student_tests as $student_test)
    {
      if($student_test->type == 'test' || $student_test->type == 'main_test')
      {
        $test = InfoTask::find($student_test->id_task);

        if($test)
        {
          if($student_test->value > 0  || ($test->date_end != NULL && $student_test->value == 0 && CalculateController::moreTodayDate($test->date_end)))
          {
            $mark_test = CalculateController::markTest($id_rs, $student_test);
            if($mark_test == "-") $mark_test = 2;
            $task_marks += [$student_test->id_task => $mark_test];
          }
        }
      }
    }


    $sum_rst = 0;
    $count_rst = 0;
    $avg = 0;



    foreach($rs->rstasks as $rstask)
    {
      $sum_rst += $rstask->total_task_score;
      $count_rst += $rstask->total_task;
    }


    if($count_rst > 0)
    {
      $avg = $sum_rst / $count_rst;
    }

    $adaptive_avg = $five * ($avg / 100);

    $student_bbs = DB::table('student_bonuses')->where('id_rs', $id_rs)->where('id_student', $id_student)->where('value', '!=', 0)->pluck('value', 'id_date_bonuses');

    $bb_now = 0;

    foreach ($student_bbs as $id_date_bb => $student_bb)
    {
      $bb_now += $student_bb;
      $date_bb = DateBonuse::getDate($id_date_bb);
      $part = 0;

      if($adaptive_avg > 0)
      {
        $part = intdiv($bb_now, $adaptive_avg);
      }


      if($part > 0)
      {
        if(isset($task_marks[$date_bb]))
        {

          for ($i=0; $i < $part; $i++)
          {
            $task_marks[$date_bb] = $task_marks[$date_bb]. ",5";
          }

          $bb_now -= $part * $adaptive_avg;
        }
        else
        {
          $task_marks += [$date_bb => "5"];


          for ($i=0; $i < $part-1 ; $i++)
          {
            $task_marks[$date_bb] = $task_marks[$date_bb]. ",5";
          }

          $bb_now -= $part * $adaptive_avg;
        }
      }

    }

      return $task_marks;
  }


  public function editKTP(Request $request) //добавляет комент
  {
    $ktp = KTP::find($request->id);
    $ktp->name = $request->name;
    $ktp->save();
  }

  public function addCommentTask(Request $request) //добавляет комент
  {
    $student_work = StudentWork::find($request->id);
    $student_work->comment = $request->comment;
    $student_work->save();
  }


  public function addComment(Request $request) //добавляет комент
  {
    $student_lesson = StudentLesson::find($request->id);
    $student_lesson->comment = $request->text;
    $student_lesson->save();
  }

  public function saveLesson(Request $request) //добавляет комент
  {
    $student_lesson = StudentLesson::find($request->id);
    $rs = RS::find($student_lesson->id_rs);
    $value = 0.0;

      if($request->text > 0)
      {
        $value = $request->text;
      }


      $student_lesson->value = $value;
      $student_lesson->save();

      $score = CalculateController::scoreLessonStudent($student_lesson->id_rs, $student_lesson->id_student);
      $percent = CalculateController::donePercentLessons($student_lesson->id_rs, $student_lesson->id_student);

      if($rs->type_rs == 1)
      {
        $score = CalculateController::sumLessonStudent($student_lesson->id_rs, $student_lesson->id_student);
        $percent = CalculateController::getHookySubgroupStudent($student_lesson->id_rs, $student_lesson->id_student)['progul'];
      }


      $data = array(
        'score' => $score,
        'percent' => $percent
      );

      return $data;


  }
  public function saveTask(Request $request) //добавляет комент
  {
    $student_work = StudentWork::find($request->id);
    $value = 0;
    $task = InfoTask::find($student_work->id_task);
    $score_one = CalculateController::scoreOneTask($student_work->id_rs, $task->id);

    if($student_work->value != $request->value)
    {
      if($request->value != "")
      {
        $value = (int)$request->value;
      }
      $student_work->value = $value;
      $student_work->save();


      $mark = "-";

      if($student_work->type == "task")
      {
        $score = CalculateController::scoreTaskOneTypeStudent($student_work->id_rs, $student_work->id_student, $task->id_info_task);

        if(RS::find($student_work->id_rs)->type_rs == 1)
        {
          $score = round(CalculateController::markTaskOneTypeStudent($student_work->id_rs, $student_work->id_student, $task->id_info_task));
        }
        else {
          $mark = CalculateController::getMark($student_work->id_rs, $request->value);
        }


        $data = array(
          'score' => $score,
          'mark' => $mark
        );

        return $data;
      }

      if($student_work->type == "test")
      {
        $score = CalculateController::scoreTestStudent($student_work->id_rs, $student_work->id_student);
        $mark = CalculateController::markTest($student_work->id_rs, $student_work);
        if(RS::find($student_work->id_rs)->type_rs == 1)
        {
         $score = round(CalculateController::markTestStudent($student_work->id_rs, $student_work->id_student));
        }

        $data = array(
          'score' => $score,
          'mark' => $mark
        );

        return $data;
      }

      if($student_work->type == "main_test")
      {
        $score = CalculateController::scoreMainTestStudent($student_work->id_rs, $student_work->id_student);
        $mark = CalculateController::markTest($student_work->id_rs, $student_work);
        if(RS::find($student_work->id_rs)->type_rs == 1)
        {
         $score = round(CalculateController::markMainTestStudent($student_work->id_rs, $student_work->id_student));
        }



        $data = array(
          'score' => $score,
          'mark' => $mark
        );

        return $data;
      }



    }
    else
    {
      return 'Это уже было';
    }

  }

  public function saveQuestion(Request $request) //добавляет комент
  {

    $info_task = InfoTask::find($request->id);

    if($info_task->total_question != $request->value)
    {
      $info_task->total_question = $request->value;
      $info_task->save();

      return 'Сохранил новое';
    }
    else
    {
      return 'Это уже было';
    }

  }

  public function addLesson(Request $request) //добавляет комент
  {
    $rs = RS::find($request->id);
    if($rs)
    {
      $students = DB::table('users')->where('id_group', $rs->id_group)->get();
      $rs->save();

      $date = Date::create([
        'id_rs' => $request->id
      ]);

      foreach ($students as $student)
      {
        $lessons = StudentLesson::create([
          'id_rs' => $request->id,
          'id_student' => $student->id,
          'id_group' => $rs->id_group,
          'id_date' => $date->id
        ]);
      }

    }

  }

  public function addMoreLesson(Request $request) //добавляет комент
  {
    $rs = RS::find($request->id);
    $count_less = $request->count;
    if($rs)
    {
      $students = DB::table('users')->where('id_group', $rs->id_group)->get();

      for ($i=0; $i < $count_less; $i++)
      {
        $date = Date::create([
          'id_rs' => $request->id
        ]);

        foreach ($students as $student)
        {
          $lessons = StudentLesson::create([
            'id_rs' => $request->id,
            'id_student' => $student->id,
            'id_group' => $rs->id_group,
            'id_date' => $date->id
          ]);
        }
      }
    }
  }

  public function saveDate(Request $request) //добавляет комент
  {
    $date = Date::find($request->id);
    $date->date = $request->date;
    $date->save();
  }



  public function delLesson(Request $request) //добавляет комент
  {
    $rs = RS::find($request->id_rs);
    $rs->total_lesson = $rs->total_lesson - 1;
    $rs->save();

    Date::where('id', $request->id)->delete();
    StudentLesson::where('id_date', $request->id)->delete();
  }

  public function saveColorWork(Request $request) //добавляет комент
  {
    $info_task = InfoTask::find($request->id);

    $info_task->color = $request->color;

    if($request->type == "reset")
    {
      $info_task->color_rgba = $request->color."00";
    }
    else
    {
      $info_task->color_rgba = $request->color."66";
    }

    $info_task->save();

  }
  public function saveDateInfo(Request $request) //добавляет комент
  {
    $optional = 0;
    $id_ktp = 0;
    if($request->optional == true) { $optional = 1; }
    if($request->ktp != -1) { $id_ktp = $request->ktp; }


    $date = Date::find($request->id);
    $date->type = $request->type;
    $date->comment = $request->comment;
    $date->subgroup = $request->subgroup;
    $date->color = $request->color;
    $date->color_rgba = ($request->color."66");
    $date->optional = $optional;
    $date->id_ktp = $id_ktp;
    $date->date = mb_substr($request->text, 0, 5);
    $date->save();
  }

  public function saveColorDate(Request $request) //добавляет комент
  {
    $date = Date::find($request->id);

    if($date)
    {
      $date->color = "#ffffff";
      $date->color_rgba = "#ffffff00";
      $date->save();
    }

  }

  public function saveTaskInfo(Request $request) //добавляет комент
  {
    $info_task = InfoTask::find($request->id);

    $old_total_score = $info_task->total_score;
    $old_def_score = $info_task->def_score;

    $info_task->name = $request->name;
    $info_task->total_score = $request->total_score;
    $info_task->date_start = $request->date_start;
    $info_task->date_end = $request->date_end;

    $info = $request->info;
    if($info != "")
    {
      $dom = new \DomDocument();
      $dom->loadHtml(mb_convert_encoding($info, 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
      $images = $dom->getElementsByTagName('img');




      foreach($images as $k => $img){ //здесь происходит магия, декодируется 64 и сохраняется картинка, берется путь и вставляется вместо прежнего src

          $data = $img->getAttribute('src');

          if(strlen($data) > 450) //если картинка уже имеет нормальный src то не трогаем
          {
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);

            $data = base64_decode($data);

            //$path = Storage::disk('ftp')->putFile('avatar', $data);
            $image_name= "/upload/" . time().$k.'.png';
            $path = public_path() . $image_name;
            file_put_contents($path, $data);
            $img->removeAttribute('src');
            $img->setAttribute('src', $image_name);


            $img->removeAttribute('src');
            $img->setAttribute('src', '/public'.$image_name);
          }

      }

      $info = $dom->saveHTML();
    }




    $info_task->info = $info;
    $info_task->pattern = $request->pattern;
    if($request->necessary == true) {$info_task->necessary = 1;}
    if($request->necessary == false) {$info_task->necessary = 0;}
    $info_task->save();


    $score_task = 0;

    if($info_task->id_info_task != NULL)
    {
      $def_tasks = InfoTask::where('id_info_task', $info_task->id_info_task)->where('total_score', NULL)->get();

      if(count($def_tasks) > 0)
      {
        $id_type_task = $info_task->id_info_task;
        $total_score_task = $info_task->rstask->total_task_score;
        $total_task = $info_task->rstask->total_task;

        $all_tasks = DB::table('info_tasks')->where('id_info_task', $id_type_task)->sum('total_score');

        $tasks = DB::table('info_tasks')->where('id_info_task', $id_type_task)->where('total_score', '>', 0)->count();

        if($total_task - $tasks > 0)
        {
          $score_task = ($total_score_task - $all_tasks) / ($total_task - $tasks);
        }

        foreach ($def_tasks as $def_task)
        {
          $def_task->def_score = $score_task;
          $def_task->save();
        }

      }

    }





  }

  public function index(int $id) //страница с таблицами учета успеваемости
  {

    $rs = RS::find($id);
    $user_id = Auth::user()->id;
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();
    $dates = DB::table('dates')->where('id_rs', $id)->get();

    if($rs->id_teacher == $user_id)
    {
      $data = array(
        'rs' => $rs,
        'students' => $students,
        'dates' => $dates

      );
      return view('teacher.rs.journal.index', $data);
    }

  }

  public function taskOption(int $id) //страница с таблицами учета успеваемости
  {
    $rs = RS::find($id);
    $user_id = Auth::user()->id;
    $rss = DB::table('rs')->where('id_teacher', $user_id)->get();
    if($rs->id_teacher == $user_id)
    {
      $data = array('rs' => $rs, 'rss' => $rss);
      return view('teacher.rs.journal.task-option', $data);
    }

  }
  public function top(int $id) //страница с топами
  {
    $rs = RS::find($id);
    $user_id = Auth::user()->id;
    $rss = DB::table('rs')->where('id_teacher', $user_id)->get();
    if($rs->id_teacher == $user_id)
    {
      $data = array('rs' => $rs, 'rss' => $rss);
      return view('teacher.tops', $data);
    }
  }




  public function editIndex(int $id_rs) //страница с формой для создания брс
  {
    $rs = RS::find($id_rs);
    if(Auth::user()->id == $rs->id_teacher)
    {
      $data = array('rs' => $rs);
      return view('teacher.rs.edit', $data);
    }
    else{return view('teacher');}

  }
  public function editRS(EditRSRequest $request) //сохранить изменения
  {

    $rs = RS::findOrFail($request->id_rs);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();
    $id_rs = $rs->id;
    $oldrs = RS::findOrFail($request->id_rs);

    $rs->name = $request->name;
    $type = 0;
    if($request->type == 'on'){ $type = 1; }
    $bonuse = 0;
    if($request->check_bb == 'on'){$bonuse = 1;}

    $rs->name = $request->name;
    $rs->total_score = $request->total_score;
    $rs->type = $type;
    $rs->bonuse = $bonuse;
    $rs->total_lesson = $request->total_lesson;
    $rs->total_lesson_score = $request->total_lesson_score;
    $rs->total_test = $request->total_test;
    $rs->total_test_score = $request->total_test_score;
    $rs->total_main_test = $request->total_main_test;
    $rs->total_main_test_score = $request->total_main_test_score;
    $rs->lesson_subgroup = $request->total_lesson_half;
    $rs->save();

    if($rs->lesson_subgroup != $oldrs->lesson_subgroup) //если количество лекций изменилось
    {
      if($oldrs->lesson_subgroup > $rs->lesson_subgroup)
      {
        $count_date = $oldrs->lesson_subgroup - $rs->lesson_subgroup;
        $dates = DB::table('dates')->where('id_rs', $id_rs)->orderBy('id', 'desc')->get();

        for ($i=0; $i < $count_date; $i++)
        {
          foreach ($dates as $dk => $date)
          {
            if($date->date != NULL)
            {
              Date::where('id', $date->id)->delete();
              StudentLesson::where('id_date', $date->id)->delete();
              $dates->forget($dk);
              break;
            }
          }
        }

      }

      if($oldrs->lesson_subgroup < $rs->lesson_subgroup)
      {
        $count_date = $rs->lesson_subgroup - $oldrs->lesson_subgrou;

        for ($i=0; $i < $count_date; $i++)
        {
          $date = Date::create([
            'id_rs' => $id_rs
          ]);

          foreach ($students as $key => $student) //резервируем для лекций
          {
            $lessons = StudentLesson::create([
            'id_rs' => $id_rs,
            'id_student' => $student->id,
            'id_group' => $rs->id_group,
            'id_date' => $date->id
          ]);
         }
        }

      }
    }



    if($request->total_lesson != $oldrs->total_lesson) //если количество лекций изменилось
    {
      if($oldrs->total_lesson > $request->total_lesson)
      {
        $count_date = $oldrs->total_lesson - $request->total_lesson;
        $dates = DB::table('dates')->where('id_rs', $id_rs)->orderBy('id', 'desc')->get();

        for ($i=0; $i < $count_date; $i++)
        {
          foreach ($dates as $dk => $date)
          {
            Date::where('id', $date->id)->delete();
            StudentLesson::where('id_date', $date->id)->delete();
            $dates->forget($dk);
            break;
          }
        }
      }
      if($oldrs->total_lesson < $request->total_lesson)
      {
        $count_date = $request->total_lesson - $oldrs->total_lesson;

        for ($i=0; $i < $count_date; $i++)
        {
          $date = Date::create([
            'id_rs' => $id_rs
          ]);

          foreach ($students as $key => $student) //резервируем для лекций
          {
            $lessons = StudentLesson::create([
            'id_rs' => $id_rs,
            'id_student' => $student->id,
            'id_group' => $rs->id_group,
            'id_date' => $date->id
          ]);
         }
        }
      }
    }

    if($oldrs->total_test != $request->total_test) //если количество тестов изменилось
    {
      if($oldrs->total_test > 0 && $request->total_test == 0)
      {
          InfoTask::where('id_rs', $id_rs)->where('type', 'test')->delete();
          StudentWork::where('id_rs', $id_rs)->where('type', 'test')->delete();
      }
      else
      {
        if($oldrs->total_test > $request->total_test) //удалить тесты
        {
          $tests = DB::table('info_tasks')->where('id_rs', $id_rs)->where('type', 'test')->orderBy('id', 'desc')->get();

          $count = $oldrs->total_test - $request->total_test;


          for ($i=0; $i < $count; $i++)
          {
            foreach ($tests as $tk => $test)
            {
              InfoTask::where('id', $test->id)->delete();
              StudentWork::where('id_task', $test->id)->delete();
              $tests->forget($tk);
              break;
            }
          }
        }

        if($oldrs->total_test < $request->total_test) //удалить тесты
        {
          $count_test = $request->total_test - $oldrs->total_test;

          for ($t=0; $t < $count_test; $t++)
          {
            $info_test = InfoTask::create([
            'id_rs' => $id_rs,
            'number' => $oldrs->total_test + $t + 1,
            'type' => 'test'
            ]);

            foreach ($students as $key => $student) //резервируем для лекций
            {
              $work_test = StudentWork::create([
                'id_rs' => $id_rs,
                'id_student' => $student->id,
                'id_group' => $rs->id_group,
                'id_task' => $info_test->id,
                'type' => 'test'
              ]);
            }
           }
         }
      }
    }

    if($oldrs->total_main_test != $request->total_main_test) //если количество итоговых тестов изменилось
    {
      if($oldrs->total_main_test > 0 && $request->total_main_test == 0)
      {
          InfoTask::where('id_rs', $id_rs)->where('type', 'main_test')->delete();
          StudentWork::where('id_rs', $id_rs)->where('type', 'main_test')->delete();
      }
      else
      {
        if($oldrs->total_main_test > $request->total_main_test) //удалить итоговые тесты
        {
          $tests = DB::table('info_tasks')->where('id_rs', $id_rs)->where('type', 'main_test')->orderBy('id', 'desc')->get();
          $count = $oldrs->total_main_test - $request->total_main_test;


          for ($i=0; $i < $count; $i++)
          {
            foreach ($tests as $tk => $test)
            {
              InfoTask::where('id', $test->id)->delete();
              StudentWork::where('id_task', $test->id)->delete();
              $tests->forget($tk);
              break;
            }
          }
        }
        if($oldrs->total_main_test < $request->total_main_test) //добавить итоговые тесты
        {
          $count_test = $request->total_main_test - $oldrs->total_main_test;

          for ($t=0; $t < $count_test; $t++)
          {
            $info_main_test = InfoTask::create([
            'id_rs' => $id_rs,
            'number' => $oldrs->total_main_test + $t + 1,
            'type' => 'main_test'
            ]);

            foreach ($students as $key => $student) //резервируем для лекций
            {
              $work_test = StudentWork::create([
                'id_rs' => $id_rs,
                'id_student' => $student->id,
                'id_group' => $rs->id_group,
                'id_task' => $info_main_test->id,
                'type' => 'main_test'
              ]);
            }
         }
        }
      }
    }

    foreach ($rs->rstasks as $task) //изменения работ
    {
      $new_name_task = $request->input('name-task-' . $task->id);
      $new_total_task = $request->input('total-task-' . $task->id);
      $new_score_task = $request->input('score-task-' . $task->id);

      if($new_total_task == 0)
      {
        $task_del = DB::table('info_tasks')->where('id_info_task', $task->id)->get();

        foreach($task_del as $del)
        {
          StudentWork::where('id_task', $del->id)->delete();
        }
          InfoTask::where('id_info_task', $task->id)->delete();
          RSTask::where('id',$task->id)->delete();


      }
      else
      {
      if(isset($new_total_task) && isset($task->total_task))
      {
        if($task->total_task != $new_total_task)
        {
          if($task->total_task > $new_total_task)
          {
            $count_task = $task->total_task - $new_total_task;
            $tasks = DB::table('info_tasks')->where('id_info_task', $task->id)->orderBy('id', 'desc')->get();

            for ($i=0; $i < $count_task; $i++)
            {
              foreach ($tasks as $tsk => $taskk)
              {
                InfoTask::where('id', $taskk->id)->delete();
                StudentWork::where('id_task', $taskk->id)->delete();
                $tasks->forget($tsk);
                break;
              }
            }
          }
          if($task->total_task < $new_total_task)
          {
            $count_task = $new_total_task - $task->total_task;

            for ($t=0; $t < $count_task; $t++)
            {
              $info_task = InfoTask::create([
              'id_rs' => $id_rs,
              'number' => ($task->total_task + $t + 1),
              'type' => 'task',
              'id_info_task' => $task->id
              ]);

              foreach ($students as $key => $student) //резервируем для лекций
              {
                $work_test = StudentWork::create([
                  'id_rs' => $id_rs,
                  'id_student' => $student->id,
                  'id_group' => $rs->id_group,
                  'id_task' => $info_task->id,
                  'type' => 'task'
                ]);
              }
             }
          }
        }
      }

        $rstask = RSTask::find($task->id);
        $rstask->name_task = $new_name_task;
        $rstask->total_task = $new_total_task;
        $rstask->total_task_score = $new_score_task;
        $rstask->save();

      }



    }

    if(isset($request->total_task))  //резервируем строки для работ
    {

      $mass_names_tasks = explode( ',', $request->name_task); //разбиваем строки на массивы для записи в таблицу
      $mass_total_tasks = explode( ',', $request->total_task);
      $mass_total_score_tasks = explode( ',', $request->total_task_score);

      $count_work = count($mass_names_tasks);

      for ($p=0; $p < $count_work; $p++)
      {

        $rs_task = RSTask::create([
          'id_rs' => $id_rs,
          'name_task' => $mass_names_tasks[$p],
          'total_task' => $mass_total_tasks[$p],
          'total_task_score' => $mass_total_score_tasks[$p]
        ]);


        for ($ti=0; $ti < $mass_total_tasks[$p]; $ti++)
        {
          $info_task = InfoTask::create([
            'id_rs' => $id_rs,
            'number' => $ti+1,
            'type' => 'task',
            'id_info_task' => $rs_task->id
          ]);

          foreach ($students as $key => $student) //резервируем для лекций
          {
            $work_main_test = StudentWork::create([
              'id_rs' => $id_rs,
              'id_student' => $student->id,
              'id_group' => $rs->id_group,
              'id_task' => $info_task->id,
              'type' => 'task'
            ]);
          }
        }
      }


    }

    return redirect()->route('teacher')->with('success', 'БРС успешно сохранена');

  }


  public function create() //страница с формой для создания брс
  {

    $groups = DB::table('groups')->get();
    $specialties = DB::table('specialties')->get();
    $disciplines = DB::table('disciplines')->get();

    $data = array(
      'groups' => $groups,
      'specialties' => $specialties,
      'disciplines' => $disciplines
    );

      return view('teacher.rs.create', $data);
  }
  public function chartData() //для графона
  {
    $data = array(
      'labels' => ['март','апрель','май','июнь'],
      'datasets' => array([
                  'data' => [15000,5000,10000,30000],
                  'backgroundColor' => ['#ffc0cb','#42aaff','#50c878','#ffc966']
          ])

    );
    return json_encode($data);
  }

  public static function getGroupName(int $id_group) // Отдает красивое имя группы, курс и специальность
  {
    $group = Group::find($id_group);

    $specialty = Specialty::find($group->id_specialty);

    $today_month = date("n");
    $today_year = date("Y");

    $course = 0;

    if($today_month > 8)
    {
      $course = $today_year - $group->year_adms + 1;
    }
    else
    {
      $course = $today_year - $group->year_adms;
    }

    return $specialty->name.' '.$course.' курс';
  }
  public static function getGroupNameShort(int $id_group) // Отдает красивое имя группы, курс и специальность
  {
    $group = Group::find($id_group);
    if($group)
    {
      $specialty = Specialty::find($group->id_specialty);
      if($specialty)
      {
        $today_month = date("n");
        $today_year = date("Y");

        $course = 0;

        if($today_month > 8)
        {
          $course = $today_year - $group->year_adms + 1;
        }
        else
        {
          $course = $today_year - $group->year_adms;
        }

        return $course."-".$specialty->name;

      }
    }
  }

  public function save(CreateRSRequest $request)
  {
    $type = 0;
    if($request->type == 'on'){ $type = 1; }
    $bonuse = 0;
    if($request->check_bb == 'on'){$bonuse = 1;}


    $rs = RS::create([
      'name' => $request->name,
      'id_teacher' => $request->id_teacher,
      'id_institution' => $request->id_institution,
      'id_discipline' => $request->id_discipline,
      'id_group' => $request->id_group,
      'total_score' => $request->total_score,
      'type' => $type,
      'bonuse' => $bonuse,
      'total_lesson' => $request->total_lesson,
      'lesson_subgroup' => $request->total_lesson_half,
      'total_lesson_score' => $request->total_lesson_score,
      'total_test' => $request->total_test,
      'total_test_score' => $request->total_test_score,
      'total_main_test' => $request->total_main_test,
      'total_main_test_score' => $request->total_main_test_score

    ]);

    $students = DB::table('users')->where('id_group', $request->id_group)->get();

    if($rs)  //если брс была создана
    {
      $id_rs = $rs->id;

      $marks = InfoMark::create([
        'id_rs' => $id_rs,
        'five' => 85,
        'four' => 70,
        'three' => 55
      ]);

      $count_lesson = ($request->total_lesson_half * 2) + $request->total_lesson;


      for ($d=0; $d < $count_lesson; $d++) //резервируем для дат
      {
        $dates = Date::create([
          'id_rs' => $id_rs
        ]);
      }

      $dates = DB::table('dates')->where('id_rs', $id_rs)->get();

      foreach ($students as $key => $student) //резервируем для лекций
      {
        foreach ($dates as $dkey => $date)
        {
          $lessons = StudentLesson::create([
            'id_rs' => $id_rs,
            'id_student' => $student->id,
            'id_group' => $request->id_group,
            'id_date' => $date->id
          ]);
        }
      }




      if(isset($request->total_test))  //резервируем строки для тестов
      {
        $count_test = $request->total_test;

        for ($t=0; $t < $count_test; $t++)
        {
          $info_test = InfoTask::create([
          'id_rs' => $id_rs,
          'number' => $t+1,
          'type' => 'test'
        ]);
       }

        $tests = DB::table('info_tasks')->where('type', 'test')->where('id_rs', $id_rs)->get();


        foreach ($students as $key => $student)
        {
          foreach ($tests as $tkey => $test)
          {
            $work_test = StudentWork::create([
              'id_rs' => $id_rs,
              'id_student' => $student->id,
              'id_group' => $request->id_group,
              'id_task' => $test->id,
              'type' => 'test'
            ]);
          }
        }
      }

      if(isset($request->total_main_test))  //резервируем строки для итоговых тестов
      {
        $count_main_test = $request->total_main_test;

        for ($tm=0; $tm < $count_main_test; $tm++)
        {
        $info_main_test = InfoTask::create([
          'id_rs' => $id_rs,
          'number' => $tm+1,
          'type' => 'main_test'
        ]);
       }

        $main_tests = DB::table('info_tasks')->where('type', 'main_test')->where('id_rs', $id_rs)->get();

        foreach ($students as $key => $student)
        {
          foreach ($main_tests as $mkey => $main_test)
          {
            $work_main_test = StudentWork::create([
              'id_rs' => $id_rs,
              'id_student' => $student->id,
              'id_group' => $request->id_group,
              'id_task' => $main_test->id,
              'type' => 'main_test'
            ]);
          }
        }
      }

      if(isset($request->total_task))  //резервируем строки для работ
      {
        $mass_names_tasks = explode( ',', $request->name_task); //разбиваем строки на массивы для записи в таблицу
        $mass_total_tasks = explode( ',', $request->total_task);
        $mass_total_score_tasks = explode( ',', $request->total_task_score);

        $count_work = count($mass_names_tasks);

        for ($i=0; $i < $count_work; $i++)
        {
          $rs_task = RSTask::create([
            'id_rs' => $id_rs,
            'name_task' => $mass_names_tasks[$i],
            'total_task' => $mass_total_tasks[$i],
            'total_task_score' => $mass_total_score_tasks[$i]
          ]);
        }

        $rs_tasks = DB::table('rs_tasks')->where('id_rs', $id_rs)->get();

        foreach ($rs_tasks as $tkey => $rs_task)
        {
          $count_task = $rs_task->total_task;
          $score_one = 0;
          if($count_task > 0)
          {
            $score_one = $rs_task->total_task_score / $count_task;
          }


            for ($ti=0; $ti < $count_task; $ti++)
            {
              $info_task = InfoTask::create([
                'id_rs' => $id_rs,
                'number' => $ti+1,
                'type' => 'task',
                'id_info_task' => $rs_task->id,
                'def_score' => $score_one
              ]);
            }
        }

        $info_tasks = DB::table('info_tasks')->where('type', 'task')->where('id_rs', $id_rs)->get();


          foreach ($students as $key => $student)
          {
            foreach ($info_tasks as $ikey => $info_task)
            {
              $work_main_test = StudentWork::create([
                'id_rs' => $id_rs,
                'id_student' => $student->id,
                'id_group' => $request->id_group,
                'id_task' => $info_task->id,
                'type' => 'task'
              ]);
            }
          }
      }
    }
    return redirect()->route('teacher')->with('success', 'БРС успешно создана');

  }


  public function deleteKTP(Request $request)
  {
    $id_rs = $request->id_rs;
    $rs = RS::find($id_rs);
    $ktp = KTP::where('id_rs', $id_rs)->delete();

  }

  public function deleteRs(Request $request)
  {
    $id_rs = $request->id_rs;
    $rs = RS::findOrFail($id_rs);


    if($rs)
    {
      RS::where('id', $id_rs)->delete();
      StudentLesson::where('id_rs', $id_rs)->delete();
      StudentWork::where('id_rs', $id_rs)->delete();
      StudentBonuse::where('id_rs', $id_rs)->delete();
      StudentMark::where('id_rs', $id_rs)->delete();
      StudentOffset::where('id_rs', $id_rs)->delete();
      StudentAttestation::where('id_rs', $id_rs)->delete();

      InfoMark::where('id_rs', $id_rs)->delete();
      InfoTask::where('id_rs', $id_rs)->delete();
      InfoAttestation::where('id_rs', $id_rs)->delete();
      KTP::where('id_rs', $id_rs)->delete();
      Date::where('id_rs', $id_rs)->delete();
      RSTask::where('id_rs', $id_rs)->delete();

      ValueRand::where('id_rs', $id_rs)->delete();
      RSRand::where('id_rs', $id_rs)->delete();
      DateBonuse::where('id_rs', $id_rs)->delete();

    }

  }


}
