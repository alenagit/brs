<?
use \App\Http\Controllers\Teacher\AccountController;
use \App\Http\Controllers\Teacher\RSController;
$groups = AccountController::getGroups();
$students = AccountController::getStudents();
?>
@extends('layouts.teacher')

@section('title')
    <title>Генерация паролей</title>
  @endsection
@section('content')
<div class="del-stud">
<p>Выберите студента которого необхоимо удалить (Восстановить будет невозможно!)</p>
<select id="del-student" name="del-student" required placeholder="Удалить студента">

    @foreach($groups as $group)
    <optgroup label='{{RSController::getGroupNameShort($group->id)}}'>

      @foreach($students as $student)
      @if($student->id_group == $group->id)

      <option value='{{$student->id}}'>{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</option>

      @endif
      @endforeach


    </optgroup>
    @endforeach

</select>
<p>
<button type="submit" id="del-done" class="btn btn-primary del-btn">
    Удалить
</button>
</p>
</div>

<form method="POST">
  @csrf

  <button type="submit" class="btn btn-primary">
      Сгенерировать
  </button>

</form>



<table>
  <thead>
    <tr>
      <th>№</th>
      <th>Группа</th>
      <th>ФИО</th>
      <th>Логин</th>
      <th>Пароль</th>
    </tr>
  </thead>

  <tbody>
    @foreach($users as $i => $user)
    <tr>
      <td>{{$i}}</td>
      <td>{{RSController::getGroupNameShort($user->id_group)}}</td>
      <td>{{$user->surname." ".mb_substr($user->name, 0, 1).".".mb_substr($user->patronymic, 0, 1)."."}}</td>
      <td>{{$user->login}}</td>
      <td>{{$user->passgen}}</td>

    </tr>
    @endforeach

  </tbody>
</table>

  @endsection

@section('js')

<script>
$(document).ready(function () {


$(document).on('click', '#del-done', function(){

  var id_stud = $("#del-student").val();
  axios({
    method: 'post',
    url: '/api/del-stud',
    data: {
      id: id_stud
    }
  })
  .then(function (response)
  {

  });

});


});

  $(document).on('click', '#isp', function(){

      axios({
        method: 'post',
        url: '/api/isp'
      })
      .then(function (response) {
        console.log('vrode norm');
      });

  });
</script>
    @endsection
