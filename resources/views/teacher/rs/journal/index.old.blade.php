<?
use \App\Http\Controllers\Teacher\RSController;
use \App\Http\Controllers\Student\CalculateController;

$update_paper = '/teacher/paper/'.$rs->id;
$update_task_options = '/teacher/task-options/'.$rs->id;
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


$all_score_student = array();
foreach($students as $i => $student) { $all_score_student += [$student->id => 0]; }


$score_student_without_bb = array();
foreach($students as $i => $student) { $score_student_without_bb += [$student->id => 0]; }



$lesson_score_student = CalculateController::getArrScoreLesson($rs->id, $students);

foreach($students as $i => $student)
{
  $all_score_student[$student->id] = $all_score_student[$student->id] + $lesson_score_student[$student->id];
}



//баллы за самостоятельные работы
if($rs->rstasks->count() > 0)
{
  foreach ($rs->rstasks as $task_type)
  {
    ${'task_score_student' . $task_type->id} = array();
  }
  $rstasks = array();
  foreach ($rs->rstasks as $task_type)
  {
    foreach($students as $i => $student)
    {
      ${'task_score_student' . $task_type->id} += [$student->id => CalculateController::scoreTaskOneTypeStudent($rs->id, $student->id, $task_type->id)];

      $all_score_student[$student->id] = $all_score_student[$student->id] + ${'task_score_student' . $task_type->id}[$student->id];
    }

    $rstasks += [$task_type->id => ${'task_score_student' . $task_type->id}];
  }
}

$test_score_student = array();
//баллы за тесты
if($rs->total_test > 0)
{
  foreach($students as $i => $student)
  {
    $test_score_student += [$student->id => CalculateController::scoreTestStudent($rs->id, $student->id)];

    $all_score_student[$student->id] = $all_score_student[$student->id] + $test_score_student[$student->id];
  }
}

$main_test_score_student = array();
//баллы за итоговые тесты
if($rs->total_main_test > 0)
{

  foreach($students as $i => $student)
  {
    $main_test_score_student += [$student->id => CalculateController::scoreMainTestStudent($rs->id, $student->id)];

    $all_score_student[$student->id] = $all_score_student[$student->id] + $main_test_score_student[$student->id];
  }

}

$bonuse_score_student = array();
//баллы за бонусные
if($rs->bonuse > 0)
{
  foreach($students as $i => $student)
  {
    $bonuse_score_student += [$student->id => CalculateController::scoreBBStudent($rs->id, $student->id)];

    $all_score_student[$student->id] = $all_score_student[$student->id] + $bonuse_score_student[$student->id];

    $score_student_without_bb[$student->id] = $all_score_student[$student->id] - $bonuse_score_student[$student->id];
  }
}

$att_score = CalculateController::getAttScore($rs->id);


?>

@extends('layouts.teacher')
@section('title')


<title>Журнал</title>
@endsection
@section('content')
<h4 class="name-rs">{{$rs->name}}<br /> <span class="name-group">{{RSController::getGroupName($rs->id_group)}}</span></h4>
<input type="hidden" id="url-task-options" value="{{$update_task_options}}">
<input type="hidden" id="att-score" value="{{$att_score}}">
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

@if($rs->rstasks->count() > 0)
@include('teacher.rs.journal.progress', ['students' => $students, 'rs' => $rs, 'all_score_student' => $all_score_student, 'score_student_without_bb' => $score_student_without_bb, 'att_score' => $att_score, 'lesson_score_student' => $lesson_score_student, 'rstasks' => $rstasks, 'test_score_student' => $test_score_student, 'main_test_score_student' => $main_test_score_student, 'bonuse_score_student' => $bonuse_score_student])
@else
@include('teacher.rs.journal.progress', ['students' => $students, 'rs' => $rs, 'all_score_student' => $all_score_student, 'score_student_without_bb' => $score_student_without_bb, 'att_score' => $att_score, 'lesson_score_student' => $lesson_score_student, 'test_score_student' => $test_score_student, 'main_test_score_student' => $main_test_score_student, 'bonuse_score_student' => $bonuse_score_student])
@endif

<b-tab title="Посещаемость" style="display:none;" id="lesson-tab">
<h3>Посещаемость</h3>
<p><span id="add-column-lesson">Добавить столбец</span></p>
<div id="lesson-ajax">

</div>
</b-tab>


@if($rs->rstasks->count() > 0)
@include('teacher.rs.journal.tasks', ['students' => $students, 'rs' => $rs, 'rstasks' => $rstasks])
@endif

@if($rs->total_test > 0 && $rs->total_main_test > 0)

@include('teacher.rs.journal.tests', ['students' => $students, 'rs' => $rs, 'test_score_student' => $test_score_student, 'main_test_score_student' => $main_test_score_student])
@else

@if($rs->total_test > 0)
@include('teacher.rs.journal.tests', ['students' => $students, 'rs' => $rs, 'test_score_student' => $test_score_student])
@endif

@if($rs->total_main_test > 0)
@include('teacher.rs.journal.tests', ['students' => $students, 'rs' => $rs, 'main_test_score_student' => $main_test_score_student])
@endif

@endif

@if($rs->bonuse > 0)
@include('teacher.rs.journal.bonuses', ['students' => $students, 'rs' => $rs, 'bonuse_score_student' => $bonuse_score_student])
@endif

@if($rs->ktp->count() > 0)
@include('teacher.rs.journal.ktp', ['rs' => $rs])
@endif

<b-tab title="Журнал" style="display:none;" id="jurnal">
<div id="paper-ajax">

</div>
</b-tab>

@if($rs->total_test > 0 || $rs->total_main_test > 0 || $rs->rstasks->count() > 0)
<b-tab title="Параметры работ" style="display:none;" id="options">
  <div id="task-option-ajax">
</div>
</b-tab>
@endif

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
@endsection
