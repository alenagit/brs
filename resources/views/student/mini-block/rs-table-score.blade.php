<?
use \App\Http\Controllers\Student\CalculateController;
?>
<table class="info-table table">
  <thead>
    <tr>
      <th>Активность</th>
      <th>Балл</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td>Посещение (1 пара)</td>
      <td>{{round(CalculateController::scoreOneLesson($rs->id))}}</td>
    </tr>

    <tr class="total-score">
      <td>За посещение всех пар</td>
      <td>{{$rs->total_lesson_score}}</td>
    </tr>


    @foreach($rs->rstasks as $r_task)

    @foreach($rs->infotasks as $info_task)

    @if($r_task->id == $info_task->id_info_task)
    <tr>
      <td>{{$r_task->name_task}} №{{$info_task->number}}:</td>
      <td>
        @if($info_task->total_score != NULL)
        {{$info_task->total_score}}
        @else
        {{round(CalculateController::scoreOneTask($rs->id, $info_task->id))}}
        @endif
      </td>
    </tr>
    @endif

    @endforeach

    <tr class="total-score">
      <td>Всего за "{{$r_task->name_task}}"</td>
      <td>{{$r_task->total_task_score}}</td>
    </tr>

    @endforeach


    @if($rs->total_test > 0)


    @foreach($rs->infotasks as $task)
    @if($task->type == 'test')
<tr>
    <td>Тест №{{$task->number}}</td>
    <td>
      @if($task->total_score != NULL)
        {{$task->total_score}}
        @else
          {{round(CalculateController::scoreOneTest($rs->id, $task->id))}}
      @endif
    </td>
</tr>
    @endif
    @endforeach

    <tr class="total-score">
      <td>Всего за тесты</td>
      <td>{{$rs->total_test_score}}</td>
    </tr>


    @endif

    @if($rs->total_main_test > 0)

    @foreach($rs->infotasks as $task)
    @if($task->type == 'main_test')
<tr>
    <td>Итоговый тест №{{$task->number}}</td>
    <td>
      @if($task->total_score != NULL)
        {{$task->total_score}}
        @else
         {{round(CalculateController::scoreOneTest($rs->id, $task->id))}}
      @endif
    </td>
  </tr>
    @endif
    @endforeach

    <tr class="total-score">
      <td>Всего за итоговые тесты</td>
      <td>{{$rs->total_main_test_score}}</td>
    </tr>


    @endif


  </tbody>
</table>
