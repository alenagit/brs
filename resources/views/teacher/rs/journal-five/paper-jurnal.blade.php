<?
use \App\Http\Controllers\Teacher\RSController;
use \App\InfoTask;
use \App\Http\Controllers\Student\CalculateController;
$dates_t = RSController::getDatesPaper($rs->id);

?>
<b-tab title="Журнал" style="display:none;" id="jurnal">

  <h3>Журнал</h3>
<div class="flex-table one-table">
  <table class="table left-table  table-bordered" style="overflow: auto;display: block;">
  <thead>
    <tr>

      <th><div>№</div></th>
      <th><div>ФИО</div></th>
      <th><div>Оценка</div></th>

@foreach($dates_t as $info => $date)

  <? $title = mb_strstr($info, '.',true);?>

      <th><div data-toggle="tooltip" title="

        @if(substr($info, -1) != "d")
        {{InfoTask::getName((int)$title) }}
        @endif

        ">

      {{substr($date, 2, 2).".".substr($date, 0, 2)}}

      @if(substr($info, -1) != "d")
      <span class="ness-group"></span>

        ({{InfoTask::getABName((int)$title) }})
      @endif

    </div></th>
@endforeach
    </tr>
  </thead>

  <tbody>
@foreach($students as $i => $student)
<? $task_marks = RSController::getMarksTaskPaperJurnal($rs->id, $student->id); ?>

    <tr>
      <td><div>{{ $i+1 }}</div></td>
      <td><div>{{ CalculateController::getFIO($student->id) }}</div></td>
      <td><div>{{ CalculateController::getAttMarkHard($rs->id, $student->id) }}</div></td>

      @foreach($dates_t as $info => $date)
            <td><div id="{{$info}}">
              @if(substr($info, -1) == "d")

              @foreach($rs->studentlessons as $i => $lesson)
              @if($lesson->id_date == mb_strstr($info, '.',true) && $lesson->id_student == $student->id)

              @if($lesson->value == NULL || $lesson->value == 0)
              Н
              @endif

              @endif
              @endforeach

              @endif

              @if(substr($info, -1) == "t")
              @if(isset($task_marks[mb_strstr($info, '.',true)] ) )
              {{$task_marks[mb_strstr($info, '.',true)]}}
              @endif
              @endif



            </div></td>
      @endforeach

    </tr>
@endforeach

  </tbody>
</table>
</div>
</b-tab>
