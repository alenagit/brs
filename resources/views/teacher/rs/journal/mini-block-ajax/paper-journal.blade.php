
<?
use \App\Http\Controllers\Teacher\RSController;
use \App\InfoTask;
use \App\Http\Controllers\Student\CalculateController;
$dates_t = RSController::getDatesPaperOPT($rs->id);


$marks = CalculateController::getMarksPaperStudent($rs->id, $dates_t);

?>

  <h3>Журнал</h3>
<div class="flex-table one-table">

  <table style="border-radius: 10px 0 0 10px !important;" class="table left-table table-bordered">
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
        <td><div data-hover="row_s{{$student->id}}" data-toggle="tooltip" data-html="true" title="{{$student->name}}">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</div></td>
    </tr>
    @endforeach
  </tbody>
</table>


  <table class="table right-table table-bordered" style="overflow: auto;display: block;">
  <thead>
    <tr>

      <th><div>Оценка</div></th>


@foreach($dates_t as $info => $date)

      <th><div

        @if(substr($info, -1) != "d")

        data-toggle="tooltip" title="{{InfoTask::getName((int)$date) }}"
        style="background: rgba(255,0,0,0.2);"

        @endif

        >

      {{substr($info, 2, 2).".".substr($info, 0, 2)}}


      @if(substr($info, -1) != "d")
      <span class="ness-group"></span>

        ({{InfoTask::getABName((int)$date) }})
      @endif

    </div></th>
@endforeach
    </tr>
  </thead>

  <tbody>
@foreach($students as $i => $student)
<? $task_marks = RSController::getMarksTaskPaperJurnal($rs->id, $student->id);
$mark = CalculateController::getAttMark($rs->id, $student->id);
$color_att = CalculateController::getColorMark($mark);
 ?>


    <tr>

      <td><div style="background: {{$color_att}}4);" data-hover="row_s{{$student->id}}">{{$mark}}</div></td>



      @foreach($dates_t as $info => $date)
            <td><div id="{{$info}}" data-hover="row_s{{$student->id}}"
              @if($marks[$student->id][$info] == "Н")
              style="background: rgba(180,0,180,0.25);"
              @endif

              @if($marks[$student->id][$info] != "Н" && $marks[$student->id][$info] != "" && $marks[$student->id][$info] != "-")
              style="background: {{CalculateController::getColorMark($marks[$student->id][$info])}}4);"
              @endif
              >

              {{$marks[$student->id][$info]}}


            </div></td>
      @endforeach

    </tr>
@endforeach

  </tbody>
</table>
</div>
