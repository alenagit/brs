<?
use \App\Http\Controllers\Student\CalculateController;

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

<div class="flex-table">
<table style="overflow: auto;" class="table left-table  table-bordered one-table">
<thead>
  <tr>
    <th><div>№</div></th>
    <th><div>ФИО</div></th>
    <th><div>Оценка</div></th>
    <th><div data-toggle="tooltip" data-html="true" title="{{$att_score}} баллов - 100% <br />(на данный момент)">Аттестация</div></th>
    <th><div data-toggle="tooltip" title="{{$rs->total_score}} баллов - 100%">Всего баллов</div></th>
    <th><div data-toggle="tooltip" title="{{$rs->total_lesson_score}} баллов - 100%">Посещаемость</div></th>

    @foreach($rs->rstasks as $task)
    <th><div data-toggle="tooltip" title="{{$task->total_task_score}} баллов - 100%">{{$task->name_task}}</div></th>
    @endforeach

    @if($rs->total_test > 0)
    <th><div data-toggle="tooltip" title="{{$rs->total_test_score}} баллов - 100%">Тесты</div></th>
    @endif

    @if($rs->total_main_test > 0)
    <th><div data-toggle="tooltip" title="{{$rs->total_main_test_score}} баллов - 100%">Итоговые тесты</div></th>
    @endif

    @if($rs->bonuse > 0)
    <th><div>Бонусные баллы</div></th>
    @endif

  </tr>
</thead>


<tbody>
  @foreach($students as $i => $student)
  <? $percent_att = CalculateController::getAttPercentOPT($rs->id, $student->id, $att_score, $all_score_student[$student->id]);
  $mark = CalculateController::getmarkStudentOPT($rs->id, $student->id, $all_score_student[$student->id]);
  $att = CalculateController::getAttMarkOPT($rs->id, $student->id, $percent_att);
  $color_mark = CalculateController::getColorMark($mark);
  $color_att = CalculateController::getColorMark($att);
  ?>
  <tr>
    <td><div data-hover="row_s{{$student->id}}">{{$i+1}}</div></td>
    <td><div data-hover="row_s{{$student->id}}" data-toggle="tooltip" data-html="true" title="{{$student->name}}">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</div></td>

    <td><div style="background: {{$color_mark}}4);" data-hover="row_s{{$student->id}}" id="u-mark{{$student->id}}" data-toggle="tooltip" data-html="true" title="{{CalculateController::getPercentStudentOPT($rs->id, $student->id, $all_score_student[$student->id])}}%">{{$mark}}</div></td>

    <td><div style="background: {{$color_att}}4);" data-hover="row_s{{$student->id}}" id="u-att{{$student->id}}" data-toggle="tooltip" data-html="true" title="{{$percent_att}}% ">{{$att}}</div></td>

    <td><div data-hover="row_s{{$student->id}}" id="u-score{{$student->id}}">{{$all_score_student[$student->id]}}</div></td>

    <td><div data-hover="row_s{{$student->id}}" id="u-less{{$student->id}}">{{$lesson_score_student[$student->id]}}</div></td>

    @foreach($rs->rstasks as $task)
    <td><div data-hover="row_s{{$student->id}}" id="u-less{{$student->id}}{{$task->id}}">{{$rstasks[$task->id][$student->id]}}</div></td>
    @endforeach

    @if($rs->total_test > 0)
    <td><div data-hover="row_s{{$student->id}}" id="u-test{{$student->id}}">{{$test_score_student[$student->id]}}</div></td>
    @endif

    @if($rs->total_main_test > 0)
    <td><div data-hover="row_s{{$student->id}}" id="u-main-test{{$student->id}}">{{$main_test_score_student[$student->id]}}</div></td>
    @endif

    @if($rs->bonuse > 0)
    <td><div data-hover="row_s{{$student->id}}" id="u-bb{{$student->id}}">{{$bonuse_score_student[$student->id]}}</div></td>
    @endif




  </tr>
  @endforeach
</tbody>


</table>
</div>
