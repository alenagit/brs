<?
use \App\Http\Controllers\Teacher\RSController;
use \App\Http\Controllers\Student\CalculateController;
$id_user = Auth::user()->id;
$marks = CalculateController::getBMark($rs->id);
$top = CalculateController::getTopStudents($rs->id);
$all_score = CalculateController::allScoreStudent($rs->id, $id_user);
$mark = CalculateController::getmarkStudent($rs->id, $id_user);
$top_hooky = CalculateController::getHookyTOP($rs->id);
if(Auth::user()->id_group == 4 || Auth::user()->id_group == 10 || Auth::user()->id_group == 3)
{
  $mem_winer = CalculateController::getMem($rs->id);
}
$can = CalculateController::getCan($rs->id, $id_user);
$att = CalculateController::getAttMark($rs->id, $id_user);
$count_lod = CalculateController::countUploadMem($rs->id);
$mem_yest = CalculateController::countUploadMemYest($rs->id);
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
         <span>Предварительная оценка: {{$mark}}</span> <span class="right-text">Аттестация: {{$att}}</span>
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
        <b-tab title="Календарь" style="display:none;">
          @include('modules.calendar-for-students', ['rs' => $rs, 'id_student' => $id_user])
        </b-tab>
        <b-tab title="Информация по баллам" style="display:none;">
          @include('student.mini-block.rs-table-score', ['rs' => $rs])


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
      <? $reminders = CalculateController::getReminders(Auth::user()->id); ?>
      <div class="rs-info">
        <div class="blur-block"></div>
        <div class="titale-rs">
          Напоминания
        </div>
        <div class="body">

          @if(gettype($reminders) != 'integer')
          <b-tabs pills>
          @foreach($reminders as $i_r => $reminder)
          <b-tab title="{{$i_r+1}}">
          <div class="grid-reminder">
            <div class="deadline">от: {{$reminder->date_start}} по: {{$reminder->date_end}}</div>
            <div class="from">
              <div class="name">от: {{CalculateController::getFIO($reminder->id_from)}}</div>
            </div>

            <div class="thene">{{$reminder->theme}}</div>
            <div class="info">{{$reminder->short_info}}</div>
            @if($reminder->full_info != "Подробное описание")
            <div class="info">{{$reminder->full_info}}</div>
            @endif
          </div>
          </b-tab>
          @endforeach
          </b-tabs>
          @endif


          @if(isset($mem_winer['path']) && (Auth::user()->id_group == 4 || Auth::user()->id_group == 10 || Auth::user()->id_group == 3))
          <img class="mem" src="/public/img/{{$mem_winer['path']}}">
          <p class="winmem"><span class="name-mem-win"> {{ CalculateController::getFIO($mem_winer['id_user']) }}</span>
          <span class="score-mem-win">  @if($mem_winer['score'] > 0)
            +{{$mem_winer['score']}} <span style="color:#c5a809">ББ</span>
            @endif
          </span></p>
          <p>Претендентов на мем дня было: {{$mem_yest}} шт.
            @if($mem_yest == 0)
            😡
          @endif
          @if($mem_yest == 1)
           😢
        @endif
        @if($mem_yest == 2)
         😔
      @endif
      @if($mem_yest == 3)
       😒
    @endif
    @if($mem_yest == 4)
      😐
  @endif
  @if($mem_yest == 5)
     😏
@endif
@if($mem_yest > 5)
   😊
@endif
      </p>
      <p style="border-top: 1px dashed rgba(255,255,255,0.3);padding-top: 12px;">Сегодня мемов загружено: {{$count_lod}} шт.
      @if($count_lod < 5)
      <br /><span  data-toggle="tooltip" data-html="true" title="Чтобы студент, чей мем выпадет, получил баллы.">Нужно еще {{5 - $count_lod }} шт.</span>
    @endif</p>


          @endif


          @if($att > 2 && $can == 1 && (Auth::user()->id_group == 4 || Auth::user()->id_group == 10 || Auth::user()->id_group == 3))
          <div class="up-mem-stud">
          <b-btn v-b-toggle.collapsemem class="m-1 mem-up-sp">😃 Загрузить мем</b-btn>
          <b-collapse id="collapsemem" style="display:none;">
            <div class="memday">
              <p>


              <input class="mem_score" id="mem_score{{$rs->id}}" type="number" data-toggle="tooltip" data-html="true" title="Max = 15" max="15" placeholder="Балл"/>
              <span class="file-input btn btn-mem btn-file" data-id-rs="{{$rs->id}}" data-toggle="tooltip" data-html="true" title="Выбрать мем">
                <i class="fas fa-image"></i> <input id="mem{{$rs->id}}" type="file" multiple accept="image/x-png,image/gif,image/jpeg">
              </span>
              <input id="mem_rs" type="hidden" value="{{$rs->id}}"/>
              <input id="mem_user" type="hidden" value="{{Auth::user()->id}}"/>
              <a data-toggle="tooltip" data-html="true" title="Загрузить" class="save_mem" data-id-rs="{{$rs->id}}"><i style="color:#fff;" class="fas fa-save"></i></a></p>


              <b-btn v-b-toggle.collapseinfo class="m-1" style="background:transparent; border:none;padding: 0px !important;border-bottom: 1px dashed;"><i class="fas fa-question" style=""></i> Подробнее, в чем прикол</b-btn>
              <b-collapse id="collapseinfo" style="display:none;">
                Загруженные вами мемасы выбираются рандомно. Если выпадает ваш мем вы можете получить +10 или больше, максимум 30 баллов.
                <ul>
                  <li style="color:#ffc83d;">Мемы можно заливать только один раз в день!</li>

                  <li>Поле "Балл" можно оставить пустым и если выпадет ваш мем, вы получите стандартно +10 баллов</li>
                  <li>Если вы поставили балл, например 10, и ваш мем не выигрывает у вас -10 баллов</li>
                  <li>Если вы поставили балл и выпал ваш мем, то удвоите поставленные баллы +20 баллов</li>
                  <li>Поставить вы можете MAX = 15, т.е. максимум вы можете получить 30 баллов (15 * 2)</li>
                  <li>Баллы ставятся только, если участвуют более 5 человек</li>
                  <li>Сегодня загружаете, завтра выпадает мем. Рандом выбирает только из вчерашних загрузок, поэтому нужно/можно грузить мемчики каждый день :) </li>
                  <li style="color:#ffc83d;">Грузить мемесы могут только, те кто аттестован хотя бы на 3</li>
                </ul>
              </b-collapse>

            </div>


          </b-collapse>
        </div>
          @endif

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
          ТОП лучших
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

             <span class="fio-top
             @if($id == $id_user)
             you
             @endif
             ">{{CalculateController::getFIO($id)}}</span> <span class="right-text
             @if($id == $id_user)
             you
             @endif
             ">{{$t}} Б</span></li>
          @endforeach

          </ol>
        </div>
      </div>

      <div class="rs-info top">
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
             <span class="fio-top
             @if($id_h == $id_user)
             you
             @endif
             ">{{CalculateController::getFIO($id_h)}}</span> <span class="right-text
             @if($id_h == $id_user)
             you
             @endif
             ">{{$h}} п</span></li>
          @endforeach

          </ol>
        </div>
      </div>


    </div>

  </div>
@endsection
