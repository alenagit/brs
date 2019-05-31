<?
use \App\Http\Controllers\Teacher\RSController;
use \App\Http\Controllers\Student\CalculateController;
$id_user = $student->id;
$one_lesson = CalculateController::scoreOneLesson($rs->id);
?>
<div class="progress-stud">

  <p class="name-stud">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}} <span class="mark-pos"><span data-toggle="tooltip" data-html="true" title="Оценка" class="cr-mark">{{$sma[$id_user]['mark']}}</span> / <span data-toggle="tooltip" data-html="true" title="Аттестация" class="cr-att">{{$sma[$id_user]['att']}}</span></span></p>

  <div class="progress global">
    <div class="progress-bar bg-white" role="progressbar" style="color: #fff; width: {{$sma[$id_user]['persent']}}%;
      @if($sma[$id_user]['mark'] == 5)
      background: rgba(32, 201, 151, .5) !important;
      @endif
      @if($sma[$id_user]['mark'] == 4)
      background: rgba(23, 162, 184, 0.5)!important;
      @endif
      @if($sma[$id_user]['mark'] == 3)
      background: rgba(253, 126, 20, .5)!important;
      @endif
      @if($sma[$id_user]['mark'] == 2)
      background: rgba(235, 96, 110, .5)!important;
      @endif
      " aria-valuemin="0" aria-valuemax="100">{{$sma[$id_user]['score']}}</div>
    <span class="progress-total">{{$rs->total_score}}</span>
  </div>

  <p><span data-toggle="tooltip" data-html="true" title="Балл за одно посещение: {{$one_lesson}}"><i class="fas fa-walking"></i> Посещаемость</span></p>
  <div class="progress pos">
    <? $progul =  CalculateController::getHookySubgroupStudent($rs->id, $id_user); ?>
    <div class="progress-bar done bg-white" role="progressbar" style="width: {{CalculateController::getPercentLessonStudent($rs->id, $id_user)}}%; @if($progul['progul'] == 0) border-radius: 10px !important; @endif" aria-valuemin="0" aria-valuemax="100">@if(CalculateController::getTotalLessonStudent($rs->id, $id_user) > 0) {{CalculateController::getTotalLessonStudent($rs->id,$id_user)}} @endif</div>
    @if($progul['progul'] > 0)
    <div class="progress-bar dolg bg-white" role="progressbar" data-toggle="tooltip" data-html="true" title="Прогулов: {{$progul['progul']}}" style="width: {{$progul['percent']}}%" aria-valuemin="0" aria-valuemax="100"></div>
    @endif
    <span class="progress-total">{{$rs->total_lesson}}</span>
  </div>

  @if($rs->rstasks->count() > 0)
  @foreach($rs->rstasks as $task)

  <? $total = $task->total_task; $percent = (1 / $total) * 100; $arr_done = CalculateController::getInfoTaskStudent($rs->id, $id_user, $task->id); ?>
  <p><i class="fas fa-paste"></i> {{$task->name_task}}</p>
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

    @if($arr_done[$itask->number] == 1)
      data-toggle="tooltip" data-html="true" title="Данная работа уже принимается для сдачи"
    @endif

    @if($arr_done[$itask->number] < 0)
      data-toggle="tooltip" data-html="true" title="Крайний срок сдачи уже прошел"
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
