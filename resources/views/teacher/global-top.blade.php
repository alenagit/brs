<?
use \App\Http\Controllers\Teacher\RSController;
use \App\User;
use \App\Http\Controllers\Student\CalculateController;
date_default_timezone_set('Europe/Moscow');
$place = 0;
?>
@extends('layouts.teacher')

@section('style')
<link href="{{ asset('css/slick.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/slick-theme.css') }}" rel="stylesheet" type="text/css">
<link async href="http://fonts.googleapis.com/css?family=Passero%20One" data-generated="http://enjoycss.com" rel="stylesheet" type="text/css"/>
@endsection


@section('title')
    <title>Рейтинг студентов</title>
  @endsection
@section('content')

<div class="global-top">
<h3>Рейтинг  студентов МАГУ г. Кировск</h3>

<div class="slider-for">
  @foreach($student_score_array_sum as $id_student => $student)
  <? $place++; ?>
  <div class="main-top-block">

    <div class="main-slide">
      <div class="blur-block"></div>
      <p class="place">{{$place}}</p>

    <div class="foro-main-top">
      <? $ava = CalculateController::getAva($id_student); ?>

      @if($ava != NULL)
       <div class="ava-top-slider" style="background: url('/public/img/{{$ava}}')"></div>
      @else
       <i class="fas fa-user-secret"></i>
      @endif

    </div>

    <p class="info-main-top" style=""><span class="fio-top-main">{{CalculateController::getFIO($id_student)}}</span>
    <span class="score-top-main">{{round($student)}} ББ</span></p>
    <div style="clear: both"></div>
    <p class="group-top">{{RSController::getGroupName(CalculateController::getGroupById($id_student))}}</p>


</div>
</div>
  @endforeach
</div>


<div class="rs-info top" style="margin-top: 0% !important;">
  <div class="blur-block"></div>
  <div class="titale-rs">
    ТОП
  </div>
  <div class="body">
    <ol>

    @foreach($student_score_array_sum as $id_student => $student)
    <li>
      <? $ava = CalculateController::getAva($id_student); ?>

      @if($ava != NULL)
      <div class="ava-top" style="background: url('/public/img/{{$ava}}')">

      </div>
      @else
      <i class="fas fa-user-secret"></i>
      @endif
       <span class="fio-top">{{CalculateController::getFIO($id_student)}}</span> <span class="right-text">{{round($student)}} ББ</span></li>
    @endforeach

    </ol>
  </div>
</div>




</div>
@endsection
@section('js')

<script>
$(document).ready(function () {
$('.slider-for').slick({
  dots: true,
         infinite: true,
         slidesToShow: 5,
         slidesToScroll: 1,
         responsive: [
           {
             breakpoint: 1950,
             settings: {
               slidesToShow: 3,
               slidesToScroll: 3,
               dots: false
             }
           },
           {
             breakpoint: 1500,
             settings: {
               slidesToShow: 2,
               slidesToScroll: 2,
               dots: false
             }
           },
    {
      breakpoint: 1100,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false
      }
    }
  ]
});
})


</script>
@endsection
