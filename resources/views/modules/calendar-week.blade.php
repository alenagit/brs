<?php
use Carbon\Carbon;
use \App\Http\Controllers\Teacher\AccountController;
use \App\Http\Controllers\FunctionController;

//$dates = getStudentLecture(int $id_rs, int $id_student); @include('modules.calendar-sub-month')
$id_rs = $rs->id;
?>
@extends('layouts.teacher')

@section('content')

  <div class="edit-sub">
    <p>Выберите студента чью посещаемость хотите глянуть</p>
    <select id="id_user" name="id_group" class="form-control" value="Нет данных" style="width: 300px;float: left;margin-right: 20px;">

      @foreach($users as $user)
      <option value="{{$user->id}}">

        {{FunctionController::getNameTeacher($user->id)}}

      </option>

      @endforeach
    </select>
    <button onclick="view_calendar()">Выбрать</button>
  </div>
<div style="margin-top:20px;" id="calendar">
</div>


@section('js')
<script>

function view_calendar()
{

  var id_student = $("#id_user").val();
  var id_rs = <? echo $id_rs; ?>;

 $.ajax({
  type: "POST",
  beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
  url:'{{URL::to("/view-calendar")}}',
  data:{
    id_student: id_student,
    id_rs:id_rs
  },
  success: function(){
    $("#calendar").load
    ('/teacher/'+ id_rs + '/calendar/' + id_student);


  }
});
}
</script>
  @endsection
  @endsection
