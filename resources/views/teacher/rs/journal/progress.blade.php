<?
use \App\Http\Controllers\Student\CalculateController;
?>

<b-tab title="Успеваемость" data-html="true" active id="update-progress">
    <h3>Успеваемость</h3>
<div id="progress-ajax">

    <div class="flex-table">
  <table style="overflow: auto;" class="table left-table  table-bordered one-table">
    <thead>
      <tr>
        <th><div data-animate="animated">№</div></th>
        <th><div data-animate="animated">ФИО</div></th>
        <th><div data-animate="animated">Оценка</div></th>
        <th><div data-animate="animated" data-toggle="tooltip" data-html="true" title="{{$sma['mast_att']}} баллов - 100% <br />(на данный момент)">Аттестация</div></th>
        <th><div data-animate="animated" data-toggle="tooltip" title="{{$rs->total_score}} баллов - 100%">Всего баллов</div></th>
        <th><div data-animate="animated" data-toggle="tooltip" title="{{$rs->total_lesson_score}} баллов - 100%">Посещаемость</div></th>

        @foreach($rs->rstasks as $task)
        <th><div data-animate="animated" data-toggle="tooltip" title="{{$task->total_task_score}} баллов - 100%">{{$task->name_task}}</div></th>
        @endforeach

        @if($rs->total_test > 0)
        <th><div data-animate="animated" data-toggle="tooltip" title="{{$rs->total_test_score}} баллов - 100%">Тесты</div></th>
        @endif

        @if($rs->total_main_test > 0)
        <th><div data-animate="animated" data-toggle="tooltip" title="{{$rs->total_main_test_score}} баллов - 100%">Итоговые тесты</div></th>
        @endif

        @if($rs->bonuse > 0)
        <th><div data-animate="animated">Бонусные баллы</div></th>
        @endif

      </tr>
    </thead>


    <tbody>
      @foreach($students as $i => $student)
      <?
      $color_mark = CalculateController::getColorMark($sma[$student->id]['mark']);
      $color_att = CalculateController::getColorMark($sma[$student->id]['att']);
      ?>
      <tr>
        <td><div data-hover="row_s{{$student->id}}">{{$i+1}}</div></td>
        <td><div data-hover="row_s{{$student->id}}" data-toggle="tooltip" data-html="true" title="{{$student->name}}">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</div></td>

        <td><div style="background: {{$color_mark}}4);" data-hover="row_s{{$student->id}}" id="u-mark{{$student->id}}" data-toggle="tooltip" data-html="true" title="{{round($sma[$student->id]['persent'],1)}}%">{{$sma[$student->id]['mark']}}</div></td>

        <td><div style="background: {{$color_att}}4);" data-hover="row_s{{$student->id}}" id="u-att{{$student->id}}" data-toggle="tooltip" data-html="true" title="{{round($sma[$student->id]['persent_att'],1)}}% ">{{$sma[$student->id]['att']}}</div></td>

        <td><div data-hover="row_s{{$student->id}}" id="u-score{{$student->id}}">{{round($sma[$student->id]['score'],1)}}</div></td>

        <td><div data-hover="row_s{{$student->id}}" id="u-less{{$student->id}}">{{round($sma[$student->id]['lesson'],1)}}</div></td>

        @foreach($rs->rstasks as $task)
        <td><div data-hover="row_s{{$student->id}}" id="u-less{{$student->id}}{{$task->id}}">{{round($sma[$student->id][$task->id],1)}}</div></td>
        @endforeach

        @if($rs->total_test > 0)
        <td><div data-hover="row_s{{$student->id}}" id="u-test{{$student->id}}">{{round($sma[$student->id]['test'],1)}}</div></td>
        @endif

        @if($rs->total_main_test > 0)
        <td><div data-hover="row_s{{$student->id}}" id="u-main-test{{$student->id}}">{{round($sma[$student->id]['main_test'],1)}}</div></td>
        @endif

        @if($rs->bonuse > 0)
        <td><div data-hover="row_s{{$student->id}}" id="u-bb{{$student->id}}">{{round($sma[$student->id]['bonuse'],1)}}</div></td>
        @endif




      </tr>
      @endforeach
    </tbody>


  </table>
</div>
</div>

</b-tab>
