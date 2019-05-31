<?php

namespace App\Http\Controllers\Teacher;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\CreateRSFiveRequest;
use App\Http\Requests\EditRSFiveRequest;
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



class RSFiveController extends Controller
{
  public function editIndex(int $id_rs) //страница с формой для создания брс
  {
    $rs = RS::find($id_rs);
    if(Auth::user()->id == $rs->id_teacher)
    {
      $data = array('rs' => $rs);
      return view('teacher.rs.edit-five', $data);
    }
    else{return view('teacher');}

  }

  public function index(int $id) //страница с таблицами учета успеваемости
  {

    $rs = RS::find($id);
    $user_id = Auth::user()->id;
    $students = User::where('id_group', $rs->id_group)->get();
    $dates = Date::where('id_rs', $id)->get();

    if($rs->id_teacher == $user_id)
    {
      $data = array(
        'rs' => $rs,
        'students' => $students,
        'dates' => $dates

      );
      return view('teacher.rs.journal-five.index', $data);
    }

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

      return view('teacher.rs.create-five', $data);
  }

  public function save(CreateRSFiveRequest $request)
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
      'type' => $type,
      'type_rs' => 1,
      'bonuse' => $bonuse,
      'lesson_subgroup' => $request->total_lesson_half,
      'total_lesson' => $request->total_lesson,
      'total_test' => $request->total_test,
      'total_main_test' => $request->total_main_test,

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
            'total_task' => $mass_total_tasks[$i]
          ]);
        }

        $rs_tasks = DB::table('rs_tasks')->where('id_rs', $id_rs)->get();

        foreach ($rs_tasks as $tkey => $rs_task)
        {
          $count_task = $rs_task->total_task;

            for ($ti=0; $ti < $count_task; $ti++)
            {
              $info_task = InfoTask::create([
                'id_rs' => $id_rs,
                'number' => $ti+1,
                'type' => 'task',
                'id_info_task' => $rs_task->id
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
    }

  }
  public function editRS(EditRSFiveRequest $request) //сохранить изменения
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
    $rs->type = $type;
    $rs->bonuse = $bonuse;
    $rs->total_lesson = $request->total_lesson;
    $rs->total_test = $request->total_test;
    $rs->total_main_test = $request->total_main_test;
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

      if($new_total_task == 0)
      {
        $task_del = InfoTask::where('id_info_task', $task->id)->get();

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
        $rstask->save();

      }



    }

    if(isset($request->total_task))  //резервируем строки для работ
    {

      $mass_names_tasks = explode( ',', $request->name_task); //разбиваем строки на массивы для записи в таблицу
      $mass_total_tasks = explode( ',', $request->total_task);


      $count_work = count($mass_names_tasks);

      for ($p=0; $p < $count_work; $p++)
      {

        $rs_task = RSTask::create([
          'id_rs' => $id_rs,
          'name_task' => $mass_names_tasks[$p],
          'total_task' => $mass_total_tasks[$p]
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

    return redirect()->route('teacher')->with('success', 'ПС успешно сохранена');

  }

}
