<?
use \App\Http\Controllers\Teacher\RSController;
use \App\Http\Controllers\Student\CalculateController;
$id_user = Auth::user()->id;
$marks = CalculateController::getBMark($rs->id);
$top = CalculateController::getTopStudents($rs->id);
$all_score = CalculateController::allScoreStudent($rs->id, $id_user);
$mark = CalculateController::getmarkStudent($rs->id, $id_user);
$top_hooky = CalculateController::getHookyTOP($rs->id);

?>
@extends('layouts.student')
@section('title')
<title>Кабинет</title>
@endsection
@section('content')
<div class="dis-student">
  <h3>{{$rs->name}}</h3>
  <div class="row-one">
    <div class="main-info">
      <div class="blur-block"></div>
      <div class="titale-rs">
         <span>Предварительная оценка: {{$mark}}</span> <span class="right-text">Аттестация: {{CalculateController::getAttMark($rs->id, $id_user)}}</span>
      </div>
      <div class="body">
        <div class="block-left">

        <div class="progress global">
          <div class="progress-bar bg-white" role="progressbar" style="color: #fff; width: {{CalculateController::getPercentStudentOPT($rs->id, $id_user,$all_score)}}%;
            @if($mark == 5)
            background: rgba(32, 201, 151, .5) !important;
            @endif
            @if($mark == 4)
            background: rgba(23, 162, 184, 0.5)!important;
            @endif
            @if($mark == 3)
            background: rgba(253, 126, 20, .5)!important;
            @endif
            @if($mark == 2)
            background: rgba(235, 96, 110, .5)!important;
            @endif
            " aria-valuemin="0" aria-valuemax="100">{{$all_score}}</div>
          <span class="progress-total">{{$rs->total_score}}</span>
        </div>


      <b-tabs pills>
        <b-tab title="Успеваемость">
          @include('student.mini-block.diag-progress', ['rs' => $rs])
        </b-tab>
        <b-tab title="Календарь">
          @include('modules.calendar-for-students', ['rs' => $rs, 'id_student' => $id_user])
        </b-tab>
        </b-tabs>
        </div>

        <div class="mark-b">
          <div><span class="five">"5" - {{$marks['5']}} Б</span></div>
          <div><span class="four">"4" - {{$marks['4']}} Б</span></div>
          <div><span class="three">"3" - {{$marks['3']}} Б</span></div>
        </div>
        <div class="clearfix" style="margin-bottom:20px;"></div>

      </div>
      </div>
      <div class="rs-info">
        <div class="blur-block"></div>
        <div class="titale-rs">
          Напоминания
        </div>
        <div class="body">

        </div>
      </div>
    </div>
    <div class="row-one">
      <div class="rs-info works">
        <div class="blur-block"></div>
        <div class="titale-rs">
          Самостоятельные работы
        </div>
        <div class="body">
          @include('student.mini-block.tasks', ['rs' => $rs])

        </div>
      </div>


      <div class="rs-info top one">
        <div class="blur-block"></div>
        <div class="titale-rs">
          ТОП 10 лучших
        </div>
        <div class="body">
          <ol>

          @foreach($top as $id => $t)
          <li nowrap>
            <? $ava = CalculateController::getAva($id); ?>

            @if($ava != NULL)
            <div class="ava-top" style="background: url('/public/img/{{$ava}}');">

            </div>
            @else
            <i class="fas fa-user-secret"></i>
            @endif

             {{CalculateController::getFIO($id)}} <span class="right-text">{{$t}} Б</span></li>
          @endforeach

          </ol>
        </div>
      </div>

      <div class="rs-info top">
        <div class="blur-block"></div>
        <div class="titale-rs">
          ТОП 10 прогульщиков
        </div>
        <div class="body">
          <ol>

          @foreach($top_hooky as $id_h => $h)
          <li>
            <? $ava = CalculateController::getAva($id_h); ?>

            @if($ava != NULL)
            <div class="ava-top" style="background: url('/public/img/{{$ava}}')">

            </div>
            @else
            <i class="fas fa-user-secret"></i>
            @endif
             {{CalculateController::getFIO($id_h)}} <span class="right-text">{{$h}} п</span></li>
          @endforeach

          </ol>
        </div>
      </div>


    </div>

  </div>
@endsection
