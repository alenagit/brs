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
use App\Reminder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;
use App\Http\Controllers\Student\CalculateController;


class AccountController extends Controller
{
  public function commonTop()
  {
    $accept_groups = array(2,3,4,10);

    $rss = DB::table('rs')->whereIn('id_group', [2,3,4,10])->get();

    $student_score_array = array();
    $student_score_array_sum = array();

    foreach ($rss as $key => $rs)
    {
      $student_score_array += [$rs->id => CalculateController::getArrayStudentTOP($rs->id)];
    }

    $students = DB::table('users')->whereIn('id_group', [2,3,4,10])->get();

    foreach ($students as $key => $student)
    {
      foreach ($rss as $key => $rs)
      {
        if($rs->id_group == $student->id_group)
        {
          $rate = $rs->total_score / 1000;

          if(isset($student_score_array_sum[$student->id]))
          {
            $student_score_array_sum[$student->id] = $student_score_array_sum[$student->id]  + ($student_score_array[$rs->id][$student->id] / $rate);
          }
          else
          {
            $student_score_array_sum += [$student->id => ($student_score_array[$rs->id][$student->id] / $rate)];
          }


        }
      }
    }

    arsort($student_score_array_sum);

    $data = array(
      'student_score_array' => $student_score_array,
      'student_score_array_sum' => $student_score_array_sum
    );

    return view('teacher.global-top', $data);



  }

public function isp(Request $req)
{
  $id_group = 9;
  $users = DB::table('users')->where('id_group', $id_group)->get();

  foreach ($users as $user)
  {
    $user_surname = $user->surname;
    $user->surname = $user->patronymic;
    $user->patronymic = $user_surname;
    $user->save();
  }
}

  public static function hasClassroom(int $id_teacher)
  {
    $class = DB::table('classrooms')->where('id_teacher', $id_teacher)->get();
    if(isset($class[0]))
    {
      return 1;
    }
  }

  public function classroom()
  {
    $user_id = Auth::user()->id;
    $class = DB::table('classrooms')->where('id_teacher', $user_id)->get();

    if(isset($class[0]))
    {
      $id_group = $class[0]->id_group;

      $rss = RS::where('id_group', $id_group)->get();
      $students = DB::table('users')->where('id_group', $id_group)->get();

      $data = array(
        'rss' => $rss,
        'students' => $students
      );

      return view('teacher.classroom', $data);
    }
  }

  public function seenReminder(Request $request)
  {
    $reminder = Reminder::find($request->id);
    $reminder->seen = $request->status;
    $reminder->save();
  }

  public function doneReminder(Request $request)
  {
    $reminder = Reminder::find($request->id);
    $reminder->done = $request->status;
    $reminder->save();
  }

  public function addReminder(Request $request)
  {
    $whos = explode(",", $request->whoms);

    foreach ($whos as $who)
    {
      $reminder = Reminder::create([
        'id_from' =>$request->from,
        'id_whom' =>$who,
        'theme'=>$request->theme,
        'short_info' =>$request->info,
        'full_info' =>$request->full_info,
        'date_start'=>$request->date_start,
        'date_end' =>$request->date_end
      ]);
    }
  }

  public static function getTeachers()
  {
    $teachers = DB::table('users')->where('status', '>=', 1)->get();
    return $teachers;
  }

  public static function getGroups()
  {
    $groups = Group::all();
    return $groups;
  }

  public static function getStudents()
  {
    $students = DB::table('users')->where('status', NULL)->get();
    return $students;
  }

  public function faq()
  {
    return view('flud.faq');
  }

  public function genPass()
  {
    $users = DB::table('users')->where('status', NULL)->get();

    return view('teacher.gen-pass',['users' => $users]);

  }

  public static function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');

    return str_replace($rus, $lat, $str);
  }


  public function genPassRequest(Request $request)
  {
    $users = DB::table('users')->where('password', NULL)->get();

    foreach ($users as $user)
    {

      $user->login = 'login'.AccountController::translit(mb_substr($user->name,0,5)).$user->id_group.$user->id;
      $user->password = Hash::make(mb_substr((136748 + ( ($user->id * 3 + 66) * 11)), -6));
      $user->passgen = mb_substr((136748 + ( ($user->id * 3 + 66) * 11)), -6);
      $user->save();

    }

    return back()->with('success', 'Пароли и логины сгенерированы');
  }

  public function updateUserData()
  {
    $user = Auth::user();

    $data = array('user' => $user);

    if($user->status == 1 || $user->status == 99)
    {
      return view('auth.change-login', $data);
    }

    if($user->status == NULL)
    {
      return view('auth.change-login-stud', $data);
    }

  }

  public function updateUserDataRequest(Request $request)
  {
    $empty_flag = 0;

    $path= '';

    if(!empty($request->file('ava')))
    {

      $data_file = array('ava' => $request->file('ava'));

      $validator_f = Validator::make($data_file, [
          'ava' => 'max:1536',
      ]);

      if($validator_f->fails()) {

           return redirect()->back()->withErrors($validator_f->errors());
      }
      else
      {
        $path = Storage::disk('ftp')->putFile('avatar',$request->file('ava'));
        $empty_flag++;
      }


    }




    $objUser = User::find($request->id);
    if(!$objUser)
    {
      return abort(404);
    }

    if($request->input('login') == "")
    {
      return back()->with('error', 'Логин не может быть пустым');
    }
    else {


      if($request->input('login') != $request->input('old_login'))
      {
        $empty_flag++;

        $data = array('login' => $request->input('login'));

        $validator = Validator::make($data, [
            'login'  => 'sometimes|string|unique:users',
        ]);

        if($validator->fails()) {
            return back()->with('error', 'Логин занят');
        }
        else
        {
          $objUser->login = $request->input('login');
        }

      }
    }

    if($request->input('email') != $request->input('old_email'))
    {
      $empty_flag++;

      $datae = array('email' => $request->input('email'));


      $validatore = Validator::make($datae, [
          'email'  => 'sometimes|string|email|unique:users',
      ]);


      if($validatore->fails()) {
          return back()->with('error', 'Email занят');
      }
      else
      {
        $objUser->email = $request->input('email');
      }
    }


    if($request->input('password') != '')
    {
      $objUser->password = Hash::make($request->input('password'));
      $objUser->change_pass = true;
      $empty_flag++;
    }

    if($path != '')
    {
      $objUser->img = $path;
    }

    if($objUser->name != $request->input('name'))
    {$objUser->name = $request->input('name'); $empty_flag++;}

    if($objUser->patronymic != $request->input('patronymic'))
    {$objUser->patronymic = $request->input('patronymic'); $empty_flag++;}

    if($objUser->surname != $request->input('surname'))
    {$objUser->surname = $request->input('surname'); $empty_flag++;}






    if($empty_flag == 0)
    {
      return back()->with('success', 'Вы не ввели новых данных');
    }


    if($objUser->save())
    {
        return back()->with('success', 'Данные изменены');
    }
    else
    {
      return back()->with('error', 'Не удалось изменить данные пользователя');
    }
  }

  public function index() //страница с формой для создания брс
  {
    $user_id = Auth::user()->id;


    $rs = RS::where('id_teacher', $user_id)->get(); //тут должна быть МОДЕЛЬ

    $data = array('rs' => $rs);

    return view('teacher.index', $data);

  }
  public static function getRS()
  {
    $user_id = Auth::user()->id;
    $rs = DB::table('rs')->where('id_teacher', $user_id)->get();
    return $rs;
  }
  public static function getStudentRS()
  {

    $rss = RS::where('id_group', Auth::user()->id_group)->get();

    return $rss;
  }
}
