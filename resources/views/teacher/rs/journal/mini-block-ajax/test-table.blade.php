<?
use \App\Http\Controllers\Student\CalculateController;

$test_score_student = array();
//баллы за тесты
if($rs->total_test > 0)
{
  foreach($students as $i => $student)
  {
    $test_score_student += [$student->id => CalculateController::scoreTestStudent($rs->id, $student->id)];
  }
}

?>
@if($rs->total_test > 0)
<div class="less-jur">

<div class="progress">
  <? $one_test_percent = 100 /$rs->total_test;  ?>

  @foreach($rs->infotasks as $test)
  @if($test->type == "test" && $test->date_end != NULL && $test->date_start != NULL )

  <?$date_end = substr($test->date_end, -4).substr($test->date_end, 3, 2).substr($test->date_end, 0, 2);
    $date_start = substr($test->date_start, -4).substr($test->date_start, 3, 2).substr($test->date_start, 0, 2);
    $today = date("Ymd");?>


  @if($test->date_start != NULL && CalculateController::moreTodayDate($test->date_start))
  <div class="progress-bar bg-white prog-cnt
  @if($date_end >= $today && $date_start <= $today)
  yellow
  @endif
  @if($date_end <= $today)
  green
  @endif
  " role="progressbar" style="width:{{$one_test_percent}}%;" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-html="true" title="{{CalculateController::scoreOneTest($rs->id, $test->id)}} баллов">{{$test->number}}</div>
  @endif
  @endif
  @endforeach
</div>
</div>
@endif

<div class="flex-table twotable">
<table class="table left-table  table-bordered">
 <thead>
   <tr>
     <th><div>№</div></th>
     <th><div>ФИО</div></th>
   </tr>
 </thead>
 <tbody>
 @foreach($students as $i => $student)
 <tr>
 <td><div data-hover="row_s{{$student->id}}">{{$i+1}}</div></td>
<td><div data-hover="row_s{{$student->id}}">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</div></td>
</tr>
@endforeach
 </tbody>
</table>

<table class="table right-table table-bordered">
  <thead>
    <tr>
      <th><div>Всего баллов</div></th>

      @foreach($rs->infotasks as $task)
      @if($task->type == 'test')
      <th style="background:{{$task->color_rgba}}" data-name="{{$task->name}}" data-id="{{$task->id}}"  id="task{{$task->id}}" data-info="{{$task->info}}"
        data-pattern="{{$task->pattern}}" data-toggle="modal-work-info" data-date-start="{{$task->date_start}}" data-date-end="{{$task->date_end}}">
        <div class="row_s{{$student->id}}">
          @if($task->necessary == 1)
          <span class="ness" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Обязательно для сдачи</b>"></span>
          @endif
        Тест №{{$task->number}}
        </div>
      </th>
      <th data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Количество вопросов в тесте</b>">
        <div contenteditable="true" data-id="{{$task->id}}" id="task{{$task->id}}" data-toggle="modal-question" style="background:{{$task->color_rgba}}" class="task_s{{$task->id}}">
          {{$task->total_question}}
        </div>
      </th>
      <th >
        <div style="background:{{$task->color_rgba}}" class="task_s{{$task->id}}">
          Оценка
        </div>
      </th>
      @endif
      @endforeach

    </tr>
  </thead>

  <tbody>

    @foreach($students as $i => $student)
    <tr>
      <td><div data-hover="row_s{{$student->id}}" id="test{{$student->id}}">
        {{$test_score_student[$student->id]}}

      </div></td>

      @foreach($rs->studentWorks as $i => $work)
      @if($student->id == $work->id_student && $work->type == 'test')
      <td colspan="2">
        <div data-hover="row_s{{$student->id}}"

          <?
          $date_end = $work->infotask->date_end;
          $int_end = substr($date_end, -4).substr($date_end, 3, 2).substr($date_end, 0, 2);
          ?>

            @if(((int)$int_end < (int)$today && (int)$int_end != "") && ($work->value == NULL || $work->value < 0))
            style="background: rgba(0,0,0,0.8);"
            @else
            style="background:{{$work->infotask->color_rgba}}"
            @endif


          class="task_s{{$work->infotask->id}}" contenteditable="true" data-type="{{$work->type}}"
          data-id-student="{{$student->id}}"  data-id="{{$work->id}}" id="work{{$work->id}}" data-toggle="modal-work" data-comment="{{$work->comment}}">
          @if($work->value != NULL)
          {{$work->value}}
          @else
          0

          @endif
        </div>
      </td>
      <td><div data-hover="row_s{{$student->id}}" id="test{{$work->id}}" contenteditable="true"
        @if(((int)$int_end < (int)$today && (int)$int_end != "") && ($work->value == NULL || $work->value < 0))
        style="background: rgba(0,0,0,0.8);"
        @else
        style="background:{{$work->infotask->color_rgba}}"
        @endif
         class="task_s{{$work->infotask->id}}">

        {{CalculateController::markTest($rs->id, $work)}}

      </div></td>
      @endif
      @endforeach

    </tr>
    @endforeach
  </tbody>

</table>
</div>
