<?
use \App\Http\Controllers\Student\CalculateController;
use \App\Http\Controllers\Teacher\RSController;
$rs_array =  CalculateController::getArrayStudentTOP($rs->id);
$top_hooky = CalculateController::getHookyTOP($rs->id);
?>
@extends('layouts.teacher')
@section('title')


    <title>Параметры работ</title>
  @endsection
  @section('style')




  @endsection
@section('content')
<div class="cab-teacher">
<div class="btn-task-opt">
  @foreach($rss as $brs)

  <a class="brs-task-opt
  @if(strpos(Request::url(), "$brs->id") !== false)
  active-task-opt
  @endif
  " href="{!! route('top', ['id' => $brs->id]) !!}">{{$brs->name}}<br /> <span style="color:#20c997">{{RSController::getGroupName($brs->id_group)}}</span></a>

  @endforeach
</div>

<h3>ТОПы <br/><span style="font-size:18px;">{{$rs->name}}, <span style="color:#20c997">{{RSController::getGroupName($rs->id_group)}}</span></span></h3>
<div class="flex-row">
  <div class="rs-info top one" style="margin-top: 0% !important;">
    <div class="blur-block"></div>
    <div class="titale-rs">
      ТОП лучших
    </div>
    <div class="body">
      <ol>

      @foreach($rs_array as $id => $t)
      <li nowrap>
        <? $ava = CalculateController::getAva($id); ?>

        @if($ava != NULL)
        <div class="ava-top" style="background: url('/public/img/{{$ava}}');">

        </div>
        @else
        <i class="fas fa-user-secret"></i>
        @endif

         <span class="fio-top">{{CalculateController::getFIO($id)}}</span> <span class="right-text">{{$t}} Б</span></li>
      @endforeach

      </ol>
    </div>
  </div>

  <div class="rs-info top" style="margin-top: 0% !important;">
    <div class="blur-block"></div>
    <div class="titale-rs">
      ТОП прогульщиков
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
         <span class="fio-top">{{CalculateController::getFIO($id_h)}}</span> <span class="right-text">{{$h}} п</span></li>
      @endforeach

      </ol>
    </div>
  </div>

  </div>
</div>
@endsection
