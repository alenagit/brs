<?
use \App\Http\Controllers\Teacher\RSController;
$update_progress_table = '/teacher/progress-table/'.$rs->id;
$update_bonuse_table = '/teacher/bonuse-table/'.$rs->id;
$update_lesson_table = '/teacher/lesson-table/'.$rs->id;
$update_task_table = '/teacher/task-table/'.$rs->id;

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
<h4 class="name-rs">{{$rs->name}}<br /> <span class="name-group">{{RSController::getGroupName($rs->id_group)}}</span></h4>
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
<input type="hidden" id="type_rs" value="1"/>
<input type="hidden" id="url-sub-1" value="{{$update_sub_1_student}}">
<input type="hidden" id="url-sub-2" value="{{$update_sub_2_student}}">

<div class="journal">


<b-tabs pills id="tabs-jurnal">

@include('teacher.rs.journal-five.progress', ['students' => $students, 'rs' => $rs])

@include('teacher.rs.journal-five.lessons', ['students' => $students, 'rs' => $rs])

@if($rs->rstasks->count() > 0)
@include('teacher.rs.journal-five.tasks', ['students' => $students, 'rs' => $rs])
@endif

@if($rs->total_test > 0 || $rs->total_main_test > 0)
@include('teacher.rs.journal-five.tests', ['students' => $students, 'rs' => $rs])
@endif

@if($rs->bonuse > 0)
@include('teacher.rs.journal-five.bonuses', ['students' => $students, 'rs' => $rs])
@endif

@if($rs->ktp->count() > 0)
@include('teacher.rs.journal-five.ktp', ['rs' => $rs])
@endif

@include('teacher.rs.journal-five.paper-jurnal', ['students' => $students, 'rs' => $rs])

@if($rs->total_test > 0 || $rs->total_main_test > 0 || $rs->rstasks->count() > 0)
@include('teacher.rs.journal-five.task-option-tab', ['students' => $students, 'rs' => $rs])
@endif
</b-tabs>

@include('teacher.rs.journal-five.popup', ['students' => $students, 'rs' => $rs])

@if($rs->bonuse > 0)
 <div id="bonuse-table">
 <b-btn v-b-toggle.collapsebonus class="m-1"><i style="color:#ffc107" class="fas fa-star"></i> Проставить бонусные оценки</b-btn>
 <b-collapse id="collapsebonus" style="display:none;">

      @include('teacher.rs.journal-five.bonus-form', ['students' => $students, 'rs' => $rs])

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


$(document).ready(function() {

  //первая инициализация и заполнение формы
  var id = '';
  var name = '';
  var info = '';
  var total_score = '';
  var date_end = '';
  var date_start = '';
  var pattern = '';
  var necessary = '';
  var first = $('#task option:first');

  name = first.attr("data-name");
  info = first.attr("data-info");
  total_score = first.attr("data-total-score");
  date_end = first.attr("data-date-end");
  date_start = first.attr("data-date-start");
  pattern = first.attr("data-pattern");
  necessary = first.attr("data-necessary");


  $('#name').val(name);
  $('#total_score').val(total_score);
  $('#date_end').val(date_end);
  $('#date_start').val(date_start);
  $('#info').html(info);
  $('#pattern').html(pattern);

  if(necessary == 1){
    $("#necessary").attr("checked","checked");
  }

    $('#pattern').summernote({
      tabsize: 2,
      height: 100,
      lang: 'ru-RU'
    });

    $('#info').summernote({
      tabsize: 2,
      height: 100,
      lang: 'ru-RU'
    });

    $('#info').html(info);
    $('#pattern').html(pattern);


  //-------------------------------------------------------------------


  $('[data-toggle="datepicker"]').datepicker({
  format: 'dd.mm.yyyy',
  language: 'ru-RU'
  });




  //обновление данных внутри полей, после выбор другой работы
  $( "#task" ).change(function() {

    id = $("#task").val();
    name = $('#' + id).attr("data-name");
    info = $('#' + id).attr("data-info");
    total_score = $('#' + id).attr("data-total-score");
    date_end = $('#' + id).attr("data-date-end");
    date_start = $('#' + id).attr("data-date-start");
    pattern = $('#' + id).attr("data-pattern");
    necessary = $('#' + id).attr("data-necessary");


    $('#name').val(name);
    $('#total_score').val(total_score);
    $('#date_end').val(date_end);
    $('#date_start').val(date_start);


    if(necessary == 1){ $("#necessary").attr("checked","checked"); }
    else { $("#necessary").removeAttr("checked");}

    $('#pattern').summernote('destroy');
    $('#info').summernote('destroy');

    $('#info').html(info);
    $('#pattern').html(pattern);

      $('#pattern').summernote({
        tabsize: 2,
        height: 100,
        lang: 'ru-RU'
      });

    $('#info').summernote({
        tabsize: 2,
        height: 100,
        lang: 'ru-RU'
      });

  });

  $('[data-toggle="save-ktp"]').on('blur', function(){

    var ktp_id = $(this).attr('id');
    var text_id = $(this).text();

    axios({
      method: 'post',
      url: '/api/edit-ktp',
      data: {
        id: ktp_id,
        name: text_id
      }
      })
      .then(function (response) {
        console.log('norm');
      });

  });


  //Сохранение изменений
  $( "#save_task" ).on('click', function(){

    var patternData = $('#pattern').summernote('code');
    var infoData = $('#info').summernote('code');
    id = $("#task").val();

    axios({
      method: 'post',
      url: '/api/save-task-info',
      data: {
        id: $("#task").val(),
        name: $('#name').val(),
        total_score: $('#total_score').val(),
        date_start: $('#date_start').val(),
        date_end: $('#date_end').val(),
        info: infoData,
        pattern: patternData,
        necessary: $("#necessary").val()

      }
    })
    .then(function (response) {

      $('#' + id).attr("data-name", $('#name').val());
      $('#' + id).attr("data-info", infoData);
      $('#' + id).attr("data-total-score", $('#total_score').val());
      $('#' + id).attr("data-date-end", $('#date_end').val());
      $('#' + id).attr("data-date-start", $('#date_start').val());
      $('#' + id).attr("data-pattern", patternData);
      if($("#necessary").val() == "on") $('#' + id).attr("data-necessary", 1);

    });
  })




});
</script>
@endsection
