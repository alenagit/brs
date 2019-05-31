<?
use \App\Http\Controllers\Teacher\RSController;
use \App\Http\Controllers\Student\CalculateController;
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

$lessons = array();
foreach($students as $i => $student)
{
  $lessons += [$student->id => CalculateController::sumLessonStudent($rs->id, $student->id)];
}

$tests = array();
if($rs->total_test > 0)
{
    foreach($students as $i => $student)
    {
      $tests += [$student->id => CalculateController::markTestStudent($rs->id, $student->id)];
    }
}

$main_tests = array();
if($rs->total_test > 0)
{
    foreach($students as $i => $student)
    {
      $main_tests += [$student->id => CalculateController::markMainTestStudent($rs->id, $student->id)];
    }
}

$rstasks = array();
//баллы за самостоятельные работы
if($rs->rstasks->count() > 0)
{
  foreach ($rs->rstasks as $task_type)
  {
    ${'task_score_student' . $task_type->id} = array();
  }

  foreach ($rs->rstasks as $task_type)
  {
    foreach($students as $i => $student)
    {
      ${'task_score_student' . $task_type->id} += [$student->id => CalculateController::markTaskOneTypeStudent($rs->id, $student->id, $task_type->id)];
    }

    $rstasks += [$task_type->id => ${'task_score_student' . $task_type->id}];
  }
}


?>

@extends('layouts.teacher')
@section('title')


<title>Журнал</title>
@endsection
@section('content')
<input type="hidden" id="url-test" value="{{$update_test_table}}">
<input type="hidden" id="url-main-test" value="{{$update_main_test_table}}">

<input type="hidden" id="url-task-options" value="{{$update_task_options}}">
<input type="hidden" id="url-paper" value="{{$update_paper}}">

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


    @include('teacher.rs.journal-five.progress', ['students' => $students, 'rs' => $rs, 'lessons' => $lessons, 'tests' => $tests, 'main_tests' => $main_tests, 'rstasks' => $rstasks])


    <b-tab title="Посещаемость" style="display:none;" id="lesson-tab">
    <h3>Посещаемость</h3>
    <p><span id="add-column-lesson">Добавить столбец</span></p>
    <div id="lesson-ajax">
      <div id="anim" class="animate">
        <div class="animation">
          <div class="pre_one"></div>
          <div class="pre_two"></div>
          <div class="pre_three"></div>
        </div>
      </div>
      

    </div>
    </b-tab>


    @if($rs->rstasks->count() > 0)
    @include('teacher.rs.journal-five.tasks', ['students' => $students, 'rs' => $rs, 'rstasks' => $rstasks])
    @endif




    @if($rs->total_test > 0)
    <b-tab title="Тесты" style="display:none;" id="test">
      <h3>Тесты</h3>
      <div id="test-ajax">
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

    @if($rs->total_main_test > 0)
    <b-tab title="Итоговые тесты" style="display:none;" id="main_test">
      <h3>Итоговые тесты</h3>
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

    @if($rs->ktp->count() > 0)
    @include('teacher.rs.journal.ktp', ['rs' => $rs])
    @endif



    <b-tab title="Параметры работ" style="display:none;" id="options">
      <div id="task-option-ajax">
        <div id="anim" class="animate">
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

@endsection
