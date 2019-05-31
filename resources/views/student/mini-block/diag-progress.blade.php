<?
use \App\Http\Controllers\Teacher\RSController;
use \App\Http\Controllers\Student\CalculateController;
$id_user = Auth::user()->id;
$one_lesson = CalculateController::scoreOneLesson($rs->id);
?>
<div class="progress-stud">
<?
$total_lesson = $rs->total_lesson + $rs->lesson_subgroup;
$progul =  CalculateController::getHookySubgroupStudent($rs->id, $id_user);
$total_lesson_st = CalculateController::getTotalLessonStudent($rs->id, $id_user);
$ost = $total_lesson - $progul['progul'] - $total_lesson_st;
 ?>
  <p><span data-toggle="tooltip" data-html="true" title="Балл за одно посещение: {{round($one_lesson)}}"><i class="fas fa-walking"></i> Посещаемость, осталось: {{round($ost)}}</span></p>
  <div class="progress pos">

    <div class="progress-bar done bg-white" role="progressbar" style="width: {{CalculateController::getPercentLessonStudent($rs->id, $id_user)}}%; @if($progul['progul'] == 0) border-radius: 10px !important; @endif" aria-valuemin="0" aria-valuemax="100">@if($total_lesson_st > 0) {{$total_lesson_st}} @endif</div>
    @if($progul['progul'] > 0)
    <div class="progress-bar dolg bg-white" role="progressbar" data-toggle="tooltip" data-html="true" title="Прогулов: {{$progul['progul']}}" style="width: {{$progul['percent']}}%" aria-valuemin="0" aria-valuemax="100"></div>
    @endif
    <span class="progress-total">{{$total_lesson}}</span>
  </div>

  @if($rs->rstasks->count() > 0)
  @foreach($rs->rstasks as $task)

  <? $total = $task->total_task; $percent = (1 / $total) * 100; $arr_done = CalculateController::getInfoTaskStudent($rs->id, $id_user, $task->id); ?>
  <p><i class="fas fa-paste"></i> {{$task->name_task}} </p>
  <div class="progress">

    @foreach($task->tasks as $itask)
    @if($arr_done[$itask->number] != NULL)


    <div class="
      @if($arr_done[$itask->number] == 2)
        done
      @endif

      @if($arr_done[$itask->number] == 1)
        mast
      @endif

      @if($arr_done[$itask->number] < 0)
        dolg
      @endif
    progress-bar bg-white"

    @if($arr_done[$itask->number] == 2)
      data-toggle="tooltip" data-html="true" title="Красава, сдал работу"
    @endif

    @if($arr_done[$itask->number] == 1)
      data-toggle="tooltip" data-html="true" title="Надо сдать"
    @endif

    @if($arr_done[$itask->number] < 0)
      data-toggle="tooltip" data-html="true" title="Надо сдать (Краний срок сдачи уже прошел)"
    @endif

    role="progressbar" style="width: {{$percent}}%" aria-valuemin="0" aria-valuemax="100">{{$itask->number}}</div>
    @endif
    @endforeach

  </div>
  @endforeach
  @endif


  @if($rs->total_test > 0)
  <p><i class="fas fa-tasks itog"></i> Тесты </p>
  <div class="progress">

    <? $total_test = $rs->total_test; $percent_test = (1 / $total_test) * 100; $arr_done_test = CalculateController::getInfoTestStudent($rs->id, $id_user) ?>
    @foreach($rs->infotasks as $test)
    @if($test->type == "test")
    @if($arr_done_test[$test->number] != NULL)
    <div class="
    @if($arr_done_test[$test->number] == 2)
      done
    @endif

    @if($arr_done_test[$test->number] == 1)
      mast
    @endif

    @if($arr_done_test[$test->number] < 0)
      dolg
    @endif
    progress-bar bg-white" role="progressbar" style="width:{{$percent_test}}%" aria-valuemin="0" aria-valuemax="100">{{$test->number}}</div>
    @endif
    @endif
    @endforeach

  </div>

  @endif

  @if($rs->total_main_test > 0)
  <p><i class="fas fa-tasks itog"></i> Итоговые тесты</p>
  <div class="progress">

    <? $total_test = $rs->total_main_test; $percent_test = (1 / $total_test) * 100; $arr_done_main_test = CalculateController::getInfoMainTestStudent($rs->id, $id_user) ?>
    @foreach($rs->infotasks as $test)
    @if($test->type == "main_test")
    @if($arr_done_main_test[$test->number] != NULL)
    <div class="
    @if($arr_done_main_test[$test->number] == 2)
      done
    @endif

    @if($arr_done_main_test[$test->number] == 1)
      mast
    @endif

    @if($arr_done_main_test[$test->number] < 0)
      dolg
    @endif
    progress-bar bg-white" role="progressbar" style="width:{{$percent_test}}%" aria-valuemin="0" aria-valuemax="100">{{$test->number}}</div>
    @endif
    @endif
    @endforeach

  </div>
  @endif
  </div>
