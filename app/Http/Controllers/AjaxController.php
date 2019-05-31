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



class AjaxController extends Controller
{


  public function getTest(int $id) //добавляет комент
  {
    $rs = RS::find($id);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();

    $data = array('rs' => $rs,'students' => $students);

    if($rs->type_rs == 1)
    {
      return view('teacher.rs.journal-five.mini-block-ajax.test-table', $data);
    }
    else {
      return view('teacher.rs.journal.mini-block-ajax.test-table', $data);
    }

  }

  public function getMainTest(int $id) //добавляет комент
  {
    $rs = RS::find($id);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();

    $data = array('rs' => $rs,'students' => $students);

    if($rs->type_rs == 1)
    {
      return view('teacher.rs.journal-five.mini-block-ajax.main-test-table', $data);
    }
    else {
      return view('teacher.rs.journal.mini-block-ajax.main-test-table', $data);
    }
  }

  public function getBonuseTable(int $id) //добавляет комент
  {
    $rs = RS::find($id);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();

    $data = array('rs' => $rs,'students' => $students,);

    if($rs->type_rs == 1)
    {
      return view('teacher.rs.journal-five.mini-block-ajax.bonuses-table', $data);
    }
    else {
      return view('teacher.rs.journal.mini-block-ajax.bonuses-table', $data);
    }
  }

  public function getPaper(int $id) //добавляет комент
  {
    $rs = RS::find($id);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();
    $data = array('rs' => $rs,'students' => $students);

    return view('teacher.rs.journal.mini-block-ajax.paper-journal', $data);
  }

  public function getLessonTable(int $id) //добавляет комент
  {
    $rs = RS::find($id);
    $dates = DB::table('dates')->where('id_rs', $id)->get();
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();

    $data = array('rs' => $rs,'students' => $students, 'dates' => $dates);

    if($rs->type_rs == 1)
    {
      return view('teacher.rs.journal-five.mini-block-ajax.lesson-table', $data);
    }
    else
    {
      return view('teacher.rs.journal.mini-block-ajax.lesson-table', $data);
    }


  }

  public function getTaskOption(int $id) //добавляет комент
  {
    $rs = RS::find($id);
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();
    $data = array('rs' => $rs,'students' => $students);

    if($rs->type_rs == 1)
    {
      return view('teacher.rs.journal-five.mini-block-ajax.task-option-ajax', $data);
    }
    else
    {
      return view('teacher.rs.journal.mini-block-ajax.task-option-ajax', $data);
    }



  }


  public function getProgressTable(int $id) //добавляет комент
  {
    $rs = RS::find($id);
    $dates = DB::table('dates')->where('id_rs', $id)->get();
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();

    $data = array('rs' => $rs,'students' => $students, 'dates' => $dates);

    if($rs->type_rs == 1)
    {
      return view('teacher.rs.journal-five.mini-block-ajax.progress-table', $data);
    }
    else {
      return view('teacher.rs.journal.mini-block-ajax.progress-table', $data);
    }
  }

  public function getTasksTable(int $id) //добавляет комент
  {
    $rs = RS::find($id);
    $dates = DB::table('dates')->where('id_rs', $id)->get();
    $students = DB::table('users')->where('id_group', $rs->id_group)->get();

    $data = array('rs' => $rs,'students' => $students, 'dates' => $dates);

    if($rs->type_rs == 1)
    {
      return view('teacher.rs.journal-five.mini-block-ajax.task-table', $data);
    }
    else {
      return view('teacher.rs.journal.mini-block-ajax.task-table', $data);
    }

  }



  public function getThemesAjax(int $id) //добавляет комент
  {
    $rs = RS::find($id);

    $data = array('rs' => $rs);

    return view('teacher.rs.journal.mini-block-ajax.themes-ajax', $data);
  }

  public function getValuesAjax(int $id) //добавляет комент
  {
    $rs = RS::find($id);

    $data = array('rs' => $rs);

    return view('teacher.rs.journal.mini-block-ajax.values-ajax', $data);
  }

  public function getThereStudentsAjax(int $id) //добавляет комент
  {
    $rs = RS::find($id);

    $data = array('rs' => $rs);

    return view('teacher.rs.journal.mini-block-ajax.there-students', $data);
  }

  public function getSelectStudentsAjax(int $id) //добавляет комент
  {
    $rs = RS::find($id);

    $data = array('rs' => $rs);

    return view('teacher.rs.journal.mini-block-ajax.select-students', $data);
  }

  public function getSub1StudentsAjax(int $id) //добавляет комент
  {
    $rs = RS::find($id);

    $data = array('rs' => $rs);

    return view('teacher.rs.journal.mini-block-ajax.subgroup-1', $data);
  }

  public function getSub2StudentsAjax(int $id) //добавляет комент
  {
    $rs = RS::find($id);

    $data = array('rs' => $rs);

    return view('teacher.rs.journal.mini-block-ajax.subgroup-2', $data);
  }

  public function getAtt5StudentsAjax(int $id) //добавляет комент
  {
    $rs = RS::find($id);

    $data = array('rs' => $rs);

    return view('teacher.rs.journal.mini-block-ajax.att-5-ajax', $data);
  }

  public function getAtt4StudentsAjax(int $id) //добавляет комент
  {
    $rs = RS::find($id);

    $data = array('rs' => $rs);

    return view('teacher.rs.journal.mini-block-ajax.att-4-ajax', $data);
  }

  public function getAtt3StudentsAjax(int $id) //добавляет комент
  {
    $rs = RS::find($id);

    $data = array('rs' => $rs);

    return view('teacher.rs.journal.mini-block-ajax.att-3-ajax', $data);
  }

  public function getAtt2StudentsAjax(int $id) //добавляет комент
  {
    $rs = RS::find($id);

    $data = array('rs' => $rs);

    return view('teacher.rs.journal.mini-block-ajax.att-2-ajax', $data);
  }
}
