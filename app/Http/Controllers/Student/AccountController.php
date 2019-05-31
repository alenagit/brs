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
use App\InfoTask;
use App\RSTask;


class AccountController extends Controller
{
  public function index()
  {
    $id_group = Auth::user()->id_group;

    $rss = RS::where('id_group', $id_group)->get();

    $data = array('rss' => $rss);

    return view('student.index', $data);
  }

  public function indexDisciplineDD(int $id)
  {
    $rs = RS::find($id);
    $data = array('rs' => $rs);

    return view('student.disdd', $data);
  }

  public function indexDiscipline(int $id)
  {
    $rs = RS::find($id);
    $data = array('rs' => $rs);

    return view('student.discipline', $data);
  }


}
