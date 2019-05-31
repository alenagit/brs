<?
use \App\Http\Controllers\Teacher\RSController;
use \App\Http\Controllers\Student\CalculateController;

$sma = CalculateController::getArrayStudentSMA($rs->id);


$marks = CalculateController::getBMark($rs->id);
$update_paper = '/teacher/paper/'.$rs->id;
$update_task_options = '/teacher/task-options/'.$rs->id;
$update_progress_table = '/teacher/progress-table/'.$rs->id;
$update_bonuse_table = '/teacher/bonuse-table/'.$rs->id;
$update_lesson_table = '/teacher/lesson-table/'.$rs->id;
$update_task_table = '/teacher/task-table/'.$rs->id;

$update_test_table = '/teacher/tests/'.$rs->id;
$update_main_test_table = '/teacher/main-tests/'.$rs->id;

$update_values_ajax = '/teacher/values-ajax/'.$rs->id;
$update_themes_ajax = '/teacher/themes-ajax/'.$rs->id;
$update_there_student = '/teacher/there-students-ajax/'.$rs->id;
$update_select_student = '/teacher/select-students-ajax/'.$rs->id;

$update_sub_1_student = '/teacher/sub-1-students-ajax/'.$rs->id;
$update_sub_2_student = '/teacher/sub-2-students-ajax/'.$rs->id;

$update_att_5_student = '/teacher/att-5-students-ajax/'.$rs->id;
$update_att_4_student = '/teacher/att-4-students-ajax/'.$rs->id;
$update_att_3_student = '/teacher/att-3-students-ajax/'.$rs->id;
$update_att_2_student = '/teacher/att-2-students-ajax/'.$rs->id;


?>

@extends('layouts.teacher')
@section('title')


<title>Журнал</title>
@endsection
@section('content')
<div class="mark-b teach">
  <div><span class="five">"5" - {{$marks['5']}} Б</span></div>
  <div><span class="four">"4" - {{$marks['4']}} Б</span></div>
  <div><span class="three">"3" - {{$marks['3']}} Б</span></div>
</div>
<h4 class="name-rs">{{$rs->name}}<br /> <span class="name-group">{{RSController::getGroupName($rs->id_group)}}</span></h4>
<input type="hidden" id="id_rs" data-rs="{{$rs->id}}">

<input type="hidden" id="url-test" value="{{$update_test_table}}">
<input type="hidden" id="url-main-test" value="{{$update_main_test_table}}">

<input type="hidden" id="url-task-options" value="{{$update_task_options}}">
<input type="hidden" id="url-paper" value="{{$update_paper}}">
<input type="hidden" id="url-task" value="{{$update_task_table}}">
<input type="hidden" id="url-progress" value="{{$update_progress_table}}">
<input type="hidden" id="url-lesson" value="{{$update_lesson_table}}">
<input type="hidden" id="url-bonuses" value="{{$update_bonuse_table}}">
<input type="hidden" id="url-values" value="{{$update_values_ajax}}">
<input type="hidden" id="url-themes" value="{{$update_themes_ajax}}">
<input type="hidden" id="url-there-students" value="{{$update_there_student}}">
<input type="hidden" id="url-select-students" value="{{$update_select_student}}">

<input type="hidden" id="url-att-5" value="{{$update_att_5_student}}">
<input type="hidden" id="url-att-4" value="{{$update_att_4_student}}">
<input type="hidden" id="url-att-3" value="{{$update_att_3_student}}">
<input type="hidden" id="url-att-2" value="{{$update_att_2_student}}">

<input type="hidden" id="url-sub-1" value="{{$update_sub_1_student}}">
<input type="hidden" id="url-sub-2" value="{{$update_sub_2_student}}">

<div class="journal">

<b-tabs pills id="tabs-jurnal">


  @include('teacher.rs.journal.progress', ['students' => $students, 'rs' => $rs, 'sma' => $sma])

  <b-tab title="Посещаемость" style="display:none;" id="lesson-tab">
  <h3>Посещаемость <a id="dow" style="background: transparent;
font-size: 15px;padding: 0px 0px 0px 5px;
position: relative;
top: -2px;" onclick="javascript:fnExcelReport();"><i style="padding: 0px !important;
background: transparent;
color: #429D7E !important;" class="fas fa-download"></i></a> <span class="right-text" style="color: #ffc107;">{{$rs->total_lesson_score}} Б</span></h3>

  <div id="lesson-ajax">
    <div class="animate">
      <div class="animation">
        <div class="pre_one"></div>
        <div class="pre_two"></div>
        <div class="pre_three"></div>
      </div>
    </div>

  </div>
  <div class="clearfix"></div>
  <p><input id="count-lesson" placeholder="Количество столбцов" type="number" class="count-lesson"/><span id="add-more-lesson">Добавить</span></p>

  <div class="clearfix" style="margin-bottom:30px;"></div>
  </b-tab>


  @if($rs->rstasks->count() > 0)
  @include('teacher.rs.journal.tasks', ['students' => $students, 'rs' => $rs, 'sma' => $sma])
  @endif




  @if($rs->total_test > 0)
  <b-tab title="Тесты" style="display:none;" id="test">
    <h3>Тесты <span class="right-text" style="color: #ffc107;">{{$rs->total_test_score}} Б</span></h3>
    <div id="test-ajax">
      <div class="animate">
        <div class="animation">
          <div class="pre_one"></div>
          <div class="pre_two"></div>
          <div class="pre_three"></div>
        </div>
      </div>

    </div>
  </b-tab>
  @endif

  @if($rs->total_main_test > 0)
  <b-tab title="Итоговые тесты" style="display:none;" id="main_test">
    <h3>Итоговые тесты <span class="right-text" style="color: #ffc107;">{{$rs->total_main_test_score}} Б</span></h3>
    <div id="main-test-ajax">
      <div id="anim" class="animate">
        <div class="animation">
          <div class="pre_one"></div>
          <div class="pre_two"></div>
          <div class="pre_three"></div>
        </div>
      </div>

    </div>
  </b-tab>
  @endif



  @if($rs->bonuse > 0)
  <b-tab title="Бонусные баллы" style="display:none;" id="bonuse">
  <h3>Бонусные баллы</h3>
  <p><input id="name-column-bb" placeholder="Название столбца"><span id="add-column-bb">Добавить столбец</span></p>

  <div id="bonuse-table">
    <div class="animate">
      <div class="animation">
        <div class="pre_one"></div>
        <div class="pre_two"></div>
        <div class="pre_three"></div>
      </div>
    </div>

  </div>
  </b-tab>
  @endif

  @if($rs->ktp->count() > 0)
  @include('teacher.rs.journal.ktp', ['rs' => $rs])
  @endif

  <b-tab title="Журнал" style="display:none;" id="jurnal">
  <div id="paper-ajax">
    <div class="animate">
      <div class="animation">
        <div class="pre_one"></div>
        <div class="pre_two"></div>
        <div class="pre_three"></div>
      </div>
    </div>
    <p style="text-align:center;">Генерируем журнал, пожалуйста, подождите (⌒‿⌒) </p>

  </div>
  </b-tab>


  <b-tab title="Параметры работ" style="display:none;" id="options">
    <div id="task-option-ajax">
      <div class="animate">
        <div class="animation">
          <div class="pre_one"></div>
          <div class="pre_two"></div>
          <div class="pre_three"></div>
        </div>
      </div>


  </div>




  </b-tab>



</b-tabs>



@include('teacher.rs.journal.popup', ['students' => $students, 'rs' => $rs])

@if($rs->bonuse > 0)
<div id="bonuse-table">
  <b-btn v-b-toggle.collapsebonus class="m-1"><i style="color:#ffc107" class="fas fa-star"></i> Проставить бонусные баллы</b-btn>
  <b-collapse id="collapsebonus" style="display:none;">
    @include('teacher.rs.journal.bonus-form', ['students' => $students, 'rs' => $rs])
  </b-collapse>
</div>
@endif

</div>
@endsection

@section('js')
<script src="{{ asset('js/table-pos.js') }}" ></script>
<script src="{{ asset('js/save-works.js') }}" ></script>
<script src="{{ asset('js/bonuse-fun.js') }}" ></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.js" defer></script>
<script src="{{ asset('js/lang-summernote.js') }}" defer></script>
<script>
function fnExcelReport() {
 var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
 tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
 tab_text = tab_text + '<x:Name>Посещаемость</x:Name>';
 tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
 tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
 tab_text = tab_text + "<table border='1px'>";

//get table HTML code
 tab_text = tab_text + $('#lesson-ajax-table').html();
 tab_text = tab_text + '</table></body></html>';

 var data_type = 'data:application/vnd.ms-excel';

 var ua = window.navigator.userAgent;
 var msie = ua.indexOf("MSIE ");
 //For IE
 if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
      if (window.navigator.msSaveBlob) {
      var blob = new Blob([tab_text], {type: "application/csv;charset=utf-8;"});
      navigator.msSaveBlob(blob, 'Посещаемость.xls');
      }
 }
//for Chrome and Firefox
else {
 $('#dow').attr('href', data_type + ', ' + encodeURIComponent(tab_text));
 $('#dow').attr('download', 'Посещаемость.xls');
}


}
</script>
@endsection
