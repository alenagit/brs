<?php

namespace App\Http\Controllers\Personal;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
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
use App\Reminder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;



class AccountController extends Controller
{
  public function index() //страница с формой для создания брс
  {
    return view('personal.index');

  }

}
