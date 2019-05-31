<?
use \App\Http\Controllers\Student\CalculateController;

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
      ${'task_score_student' . $task_type->id} += [$student->id => CalculateController::markTaskOneTypeStudent($rs->id, $student->id, $task_type->id)];
    }

    $rstasks += [$task_type->id => ${'task_score_student' . $task_type->id}];
  }
}


?>

<div class="flex-table">
<table class="table left-table  table-bordered">
<thead>
  <tr>
    <th><div>№</div></th>
    <th><div>ФИО</div></th>
    <th><div>Итоговая оценка</div></th>

    <th><div>Посещаемость</div></th>

    @foreach($rs->rstasks as $task)
    <th><div>{{$task->name_task}}</div></th>
    @endforeach

    @if($rs->total_test > 0)
    <th><div>Тесты</div></th>
    @endif

    @if($rs->total_main_test > 0)
    <th><div>Итоговые тесты</div></th>
    @endif

  </tr>
</thead>


<tbody>
  @foreach($students as $i => $student)
  <? $for_mark = array();
  $mark = '-';
  if(isset($tests[$student->id]))
  {
    array_push($for_mark, $tests[$student->id]);
  }

  if(isset($main_tests[$student->id]))
  {
    array_push($for_mark, $main_tests[$student->id]);
  }

  foreach($rs->rstasks as $task)
  {
    array_push($for_mark, $rstasks[$task->id][$student->id]);
  }

  if(count($for_mark) > 0)
  {
    $mark = array_sum($for_mark) / count($for_mark);
  }

  ?>
  <tr>
    <td><div data-hover="row_s{{$student->id}}">{{$i+1}}</div></td>
    <td><div data-hover="row_s{{$student->id}}">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</div></td>

    <td><div data-hover="row_s{{$student->id}}" id="u-mark{{$student->id}}">{{round($mark)}}</div></td>

    <td><div data-hover="row_s{{$student->id}}" id="u-less{{$student->id}}">{{round($lessons[$student->id])}}</div></td>

    @foreach($rs->rstasks as $task)
    <td><div data-hover="row_s{{$student->id}}" id="u-less{{$student->id}}{{$task->id}}">{{round($rstasks[$task->id][$student->id])}}</div></td>
    @endforeach

    @if($rs->total_test > 0)
    <td><div data-hover="row_s{{$student->id}}" id="u-test{{$student->id}}">{{round($tests[$student->id])}}</div></td>
    @endif

    @if($rs->total_main_test > 0)
    <td><div data-hover="row_s{{$student->id}}" id="u-main-test{{$student->id}}">{{round($main_tests[$student->id])}} </div></td>
    @endif


  </tr>
  @endforeach
</tbody>


</table>
</div>
