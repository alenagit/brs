<?
use \App\Http\Controllers\RatingController;
use \App\Http\Controllers\FunctionController;
use \App\Http\Controllers\Helpful\AttFunctionController;
use \App\Http\Controllers\Teacher\AccountController;
use \App\Http\Controllers\Helpful\ScoreInfoController;
$path = '';
$path = AccountController::getavast(Auth::user()->id);
$ar_top = RatingController::getTop();
$place = 0;
?>
@extends('layouts.student')

@section('style')
<link href="{{ asset('css/slick.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/slick-theme.css') }}" rel="stylesheet" type="text/css">
<link async href="http://fonts.googleapis.com/css?family=Passero%20One" data-generated="http://enjoycss.com" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

<style>
.prob{
  background-image: url('/public/img/<? echo $path;?>');
  width: 130px;
    height: 130px;
    background-position: center;
    background-size: 100%;
    margin: 15px auto;
    display: block;
    background-repeat: no-repeat;
    border-radius: 130px;
}
</style>

  <div id="app">
  <div class="left-admin-menu">
    <div class="user-option">
      @if($path != '')
      <div class="prob"></div>
      @else
      <i class="fas fa-user-graduate"></i>
      @endif
  </div>
  </div>

<div class="backg">
  <h2 style="font-family: 'Russo One';">Рейтинг филиала МАГУ г. Кировск</h2>
<div class="slider-for">
  @foreach($ar_top as $id_student => $top)
  <? $place++; ?>
  <div class="main-top-block">
    <div class="main-slide">
      <p class="place">{{$place}}</p>


    <div class="foro-main-top">
      @if(!empty(AccountController::getavast($id_student)))
    <div style="background: url('/public/img/{{AccountController::getavast($id_student)}}');" class="ava-main-top"></div>
    @else
    <p style="margin-bottom: 6px;"><i class="fas fa-user-secret in-top"></i></p>
    @endif



    </div>

    <p class="info-main-top" style=""><span class="fio-top-main">{{FunctionController::getNameTeacher($id_student)}}</span> <span class="score-top-main">{{round($top)}}<img class="coin" src="/public/img/site/monetka.png"></span></p>
    <div style="clear: both"></div>
    <p class="group-top">{{RatingController::getGroup($id_student)}}</p>
</div>
</div>
  @endforeach
</div>
<? $place = 0; //<div style="background: url('/public/img/top/place-1.gif');" class="gif-main-top"></div>
/*@if($place == 1)
<div style="background: url('/public/img/top/korona.gif');" class="gif-main-top"></div>

@endif
@if($place == 2)
<div style="background: url('/public/img/top/2.gif');" class="too-main-top"></div>
@endif
@if($place == 3)
<div style="background: url('/public/img/top/3.gif');    background-position-x: -13px !important;" class="too-main-top"></div>
@endif

@if($place == 4)
<div style="background: url('/public/img/top/4.gif');" class="too-main-top"></div>
@endif*/
?>

<div class="text-top-main">
  <? $place = 0;?>

  <table class="table-top">
    <tr>
      <th>
        №
      </th>
      <th>
        Студент
      </th>
      <th>
        Рейтинг
      </th>
    </tr>
@foreach($ar_top as $id_student => $top)
<? $place++; ?>
<tr>
<td>
    <span class="place">{{$place}}</span>
</td>
<td align="center">
  @if(!empty(AccountController::getavast($id_student)))
<div style="background: url('/public/img/{{AccountController::getavast($id_student)}}');float:left;margin-right: 10px;margin-top: 4px;" class="ava-text-top"></div>
@else
<i class="fas fa-user-secret in-top-text" style="float:left;margin-right: 10px;margin-top: 4px;"></i>
@endif
<p style="margin-top: 8px;margin-bottom: 6px;">
  <span class="text-top-name">{{FunctionController::getNameTeacher($id_student)}}</span><br/>
<span class="text-top-group">{{RatingController::getGroup($id_student)}}</span>
</p>

</td>

<td>
    <span class="top-text">{{round($top)}} <img class="coin-text" src="/public/img/site/monetka.png"></span>
</td>


<tr>
@endforeach

<table>
</div>


</div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/slick.min.js') }}"></script>
<script>

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



</script>
@endsection
