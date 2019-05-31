<?
use \App\Http\Controllers\Teacher\RSController;
use \App\Http\Controllers\Student\CalculateController;
$months = array( 1 => 'Январь' , 'Февраль' , 'Март' , 'Апрель' , 'Май' , 'Июнь' , 'Июль' , 'Август' , 'Сентябрь' , 'Октябрь' , 'Ноябрь' , 'Декабрь' );
$n = 1;
$rs_array = array();
$student_atts = array();
$marks_att = array(5 => 0, 4 => 0, 3 => 0, 2 => 0, "-" => 0);

?>
@extends('layouts.teacher')
@section('title')
    <title>Информация по группе</title>
  @endsection
@section('content')

<div class="cab-classroom">
<h3>Информация по группе</h3>
  <b-tabs pills>


<b-tab title="Таблица успеваемости">
  <div class="in-usp" style="position:relative;">
  <b-tabs pills>
  @foreach($rss as $rs)
    <b-tab title="{{$rs->name}}">

      <? $sma = CalculateController::getArrayStudentSMA($rs->id);
      $marks = CalculateController::getBMark($rs->id);
      $rs_array += [$rs->id => $sma]; ?>
      <div class="marks-class-room">
        <div class="c-five">"5" - {{$marks['5']}} Б</div>
        <div class="c-four">"4" - {{$marks['4']}} Б</div>
        <div class="c-three">"3" - {{$marks['3']}} Б</div>
      </div>
    @include('teacher.rs.journal.progress', ['students' => $students, 'rs' => $rs, 'sma' => $sma])
    </b-tab>

    @endforeach
    </b-tabs>
  </div>
</b-tab>

    <b-tab title="Подробная информация">
<div class="in-usp" style="position:relative;">
      <b-tabs pills>
      @foreach($rss as $rs)
      <b-tab title="{{$rs->name}}">
        <div class="marks-class-room">
          <div class="c-five">"5" - {{$marks['5']}} Б</div>
          <div class="c-four">"4" - {{$marks['4']}} Б</div>
          <div class="c-three">"3" - {{$marks['3']}} Б</div>
        </div>

          <div class="info-color">
          <div class="k-five"><span class="color"></span>Работа сдана</div>
          <div class="k-four"><span class="color"></span>Работа не сдана, крайний срок сдачи не прошел</div>
          <div class="k-three"><span class="color"></span>Работа не сдана, крайний срок сдачи прошел</div>
        </div>


      <div class="classrom-progress">

      @foreach($students as $student)
      <div class="stud-progress">
        <div class="blur-block"></div>

        <b-tabs pills>
          <b-tab title="Успеваемость">

      @include('teacher.classroom-blocks.progress-panel', ['rs' => $rs, 'student' => $student, 'sma' => $rs_array[$rs->id]])
      </b-tab>
      {{-- <b-tab title="Календарь" style="display:none">
        @include('modules.calendar-for-students', ['rs' => $rs, 'id_student' => $student->id])
        </b-tab> --}}
        </b-tabs>
      </div>
      @endforeach

      </div>
      </b-tab>
        @endforeach
      </b-tabs>
</div>
      </b-tab>

      <b-tab title="Прогулы (Рапортички)" style="display:none">
        @include('teacher.classroom-blocks.hooks', ['rss' => $rss, 'students' => $students])
      </b-tab>

      <b-tab title="Аттестация" style="display:none">
        <div class="att-table" style="padding-bottom:40px;">

        <a id="dow" onclick="javascript:fnExcelReport();">Скачать</a>

        <table id="main-att" class="main-att" style="overflow: auto;display: block;">
        <thead>

          <tr>
            <th style="text-align: center;padding: 7px 0;" colspan="{{13 + count($rss)}}">Ведомость аттестации</th>
          </tr>

          <tr>
            <th>За</th>
            <th>{{$months[date( 'n' )]}}</th>
            <th colspan="{{count($rss)}}">м-ц  уч.год группа {{RSController::getGroupName($rs->id_group)}}</th>
            <th colspan="2"></th>
            <th colspan="4">Кл. руководитель </th>
            <th colspan="5"></th>
          </tr>

          <tr>
            <th rowspan="3">№ п/п</th>
            <th rowspan="3">ФИО</th>
            <th colspan="{{count($rss)}}">Предметы</th>
            <th colspan="2">Не аттестованы</th>
            <th colspan="7">Аттестованы</th>
            <th colspan="2">Пропущено часов</th>
          </tr>

          <tr>
            @foreach($rss as $r)
            <th class="vertical-text" style="white-space: nowrap;">{{ CalculateController::getFIO($r->id_teacher) }}</th>
            <? $sma = CalculateController::getArrayStudentSMA($r->id);
            $rs_array += [$r->id => $sma]; ?>
            @endforeach

            <th class="vertical-text" rowspan="2">Всего</th>
            <th class="vertical-text" rowspan="2">без уважительных причин</th>

            <th class="vertical-text" rowspan="2">на "5"</th>
            <th class="vertical-text" rowspan="2">на "4" и "5"</th>
            <th class="vertical-text" rowspan="2">только на "3"</th>
            <th class="vertical-text" rowspan="2">с одной "3"</th>
            <th colspan="3" >на "2"</th>
            <th class="vertical-text" rowspan="2">Всего</th>
            <th class="vertical-text" rowspan="2">без уважительных причин</th>


          </tr>

          <tr>
            @foreach($rss as $r)
            <th class="vertical-text">{{$r->name}}</th>
            @endforeach

            <th class="vertical-text">с одной "2"</th>
            <th class="vertical-text">с двумя "2"</th>
            <th class="vertical-text" style="border-right: 2px solid rgba(0, 0, 0, 0.5) !important;">тремя и больше на "2"</th>
          </tr>

        </thead>

        <tbody>
          @foreach($students as $user)
          <? if(!isset($student_atts[$user->id]))
          {
            $student_atts += [$user->id => array()];
            $student_atts[$user->id] = $marks_att;
          }

          ?>

          <tr>
            <td>{{$n}} <? $n++; ?></td>

            <td>{{ CalculateController::getFIO($user->id) }}</td>

            @foreach($rss as $r)
            <? if($rs_array[$r->id][$user->id]['att'] == '-')
            {
              $student_atts[$user->id][2] += 1;
            }
            else
            {
              $student_atts[$user->id][$rs_array[$r->id][$user->id]['att']] += 1;
            }
             ?>
            <td>{{$rs_array[$r->id][$user->id]['att']}}</td>
            @endforeach

            <td>@if($student_atts[$user->id][2] > 0) {{$student_atts[$user->id][2]}} @endif</td>

            <td></td>


            <td>@if($student_atts[$user->id][5] == count($rss)) + @endif</td>

            <td>@if($student_atts[$user->id][4] > 0 && $student_atts[$user->id][5] > 0 && $student_atts[$user->id][3] == 0 && $student_atts[$user->id][2] == 0) + @endif</td>

            <td>@if($student_atts[$user->id][3] == count($rss)) + @endif</td>

            <td>@if($student_atts[$user->id][3] == 1 && $student_atts[$user->id][2] == 0) + @endif</td>

            <td>@if($student_atts[$user->id][2] == 1) + @endif</td>

            <td>@if($student_atts[$user->id][2] == 2) + @endif</td>

            <td>@if($student_atts[$user->id][2] > 3) + @endif</td>

            <td></td>

            <td></td>

          </tr>

          @endforeach
        </tbody>
      </table>
    </div>
      </b-tab>






      </b-tabs>



    </b-tab>

    </b-tabs>

</div>
@endsection
@section('js')
<script>
$(document).ready(function () {
$(document).on({
    mouseenter: function () {
      var tr = $(this).find('[data-hover*="row_s"]');
      var class_css = $(this).attr("data-hover");
      tr.not('[data-hover='+class_css+']').removeClass("selected-row");
      $('[data-hover='+class_css+']').addClass("selected-row");
    },
    mouseleave: function () {
        var tr = $(this).find('[data-hover*="row_s"]');

        var class_css = $(this).attr("data-hover");
        $('[data-hover='+class_css+']').removeClass("selected-row");

    }
},'div');
})
function fnExcelReport() {
 var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
 tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
 tab_text = tab_text + '<x:Name>Аттестация</x:Name>';
 tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
 tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
 tab_text = tab_text + "<table border='1px'>";

//get table HTML code
 tab_text = tab_text + $('#main-att').html();
 tab_text = tab_text + '</table></body></html>';

 var data_type = 'data:application/vnd.ms-excel';

 var ua = window.navigator.userAgent;
 var msie = ua.indexOf("MSIE ");
 //For IE
 if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
      if (window.navigator.msSaveBlob) {
      var blob = new Blob([tab_text], {type: "application/csv;charset=utf-8;"});
      navigator.msSaveBlob(blob, 'Test file.xls');
      }
 }
//for Chrome and Firefox
else {
 $('#dow').attr('href', data_type + ', ' + encodeURIComponent(tab_text));
 $('#dow').attr('download', 'Test file.xls');
}


}
</script>
@endsection
