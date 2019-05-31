<?
use \App\Http\Controllers\Student\CalculateController;
$studentWorks = $rs->studentWorks->where('type', 'task');
$today = date('Ymd');
?>

@foreach($rs->rstasks as $kt => $type_task)


<b-tab title="{{$type_task->name_task}}" style="display:none;" rel="{{$type_task->name_task}}">
<h3 style="color: #ffc107;">{{$type_task->name_task}} <span class="right-text" style="color: #ffc107;">{{$type_task->total_task_score}} Б</span></h3>
<div class="less-jur">

  <div class="progress">
    <? $one_task_percent = 100 /$type_task->total_task;  ?>

    @foreach($rs->infotasks as $itask)
    @if($itask->type == "task" && $itask->id_info_task == $type_task->id && $itask->date_end != NULL &&  $itask->date_start != NULL)

    <?$date_end = substr($itask->date_end, -4).substr($itask->date_end, 3, 2).substr($itask->date_end, 0, 2);
      $date_start = substr($itask->date_start, -4).substr($itask->date_start, 3, 2).substr($itask->date_start, 0, 2);
      $today = date("Ymd");?>

    <div class="progress-bar bg-white prog-cnt
    @if($date_end >= $today && $date_start <= $today)
    yellow
    @endif
    @if($date_end <= $today)
    green
    @endif
    " role="progressbar" style="width:{{$one_task_percent}}%;" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-html="true" title="{{CalculateController::scoreOneTask($rs->id, $itask->id)}} баллов">{{$itask->number}}</div>

    @endif
    @endforeach
  </div>
  </div>


  <div id="tasks-ajax">
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
   <td><div data-hover="row_s{{$student->id}}{{$type_task->id}}">{{$i+1}}</div></td>
  <td><div data-hover="row_s{{$student->id}}{{$type_task->id}}">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</div></td>
  </tr>
  @endforeach
   </tbody>
  </table>

  <table class="table right-table table-bordered ">
          <thead>
            <tr>
              <th><div>Всего баллов</div></th>

              @foreach($rs->infotasks as $task)
              @if($task->type == 'task' && $task->id_info_task == $type_task->id)
              <th class="pointer" style="background:{{$task->color_rgba}}" data-name="{{$task->name}}" data-id="{{$task->id}}" data-color="{{$task->color}}"
                id="task{{$task->id}}" data-info="{{$task->info}}" data-pattern="{{$task->pattern}}" data-toggle="modal-work-info" data-date-start="{{$task->date_start}}" data-date-end="{{$task->date_end}}">
                <?
                  $color_head = "";
                  $date_end = $task->date_end;
                   $date_start = $task->date_start;
                   $int_end = substr($date_end, -4).substr($date_end, 3, 2).substr($date_end, 0, 2);
                   $int_start = substr($date_start, -4).substr($date_start, 3, 2).substr($date_start, 0, 2);
                   if((int)$int_end < (int)$today && (int)$int_end != "")
                   {
                     $color_head = "#880000";
                   }
                   else
                   {
                     if((int)$int_start < (int)$today && (int)$int_start != "")
                     {
                       $color_head = "#127a00";
                     }

                   }
                ?>

                <div style="background: {{$color_head}};">

                  @if($task->necessary == 1)
                  <span class="ness" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Обязательно для сдачи</b>"></span>
                  @endif
                  №{{$task->number}} (%)
                </div>
              </th>
              <th style="background:{{$task->color_rgba}}" class="task_s{{$task->id}}"><div style="background: {{$color_head}};">Оценка</div></th>
              @endif
              @endforeach

            </tr>
          </thead>

          <tbody>

            @foreach($students as $i => $student)
            <tr>

              <td><div data-hover="row_s{{$student->id}}{{$type_task->id}}" id="total_task_score{{$student->id}}{{$type_task->id}}">

                {{$sma[$student->id][$type_task->id]}}

              </div></td>

              @foreach($studentWorks as $i => $work)
              @if($student->id == $work->id_student && $type_task->id == $work->infotask->rstask->id)
              <td>
                <div data-hover="row_s{{$student->id}}{{$type_task->id}}"
                  <?
                  $date_end = $work->infotask->date_end;
                   $int_end = substr($date_end, -4).substr($date_end, 3, 2).substr($date_end, 0, 2);
                  ?>

                    @if(((int)$int_end < (int)$today && (int)$int_end != "") && ($work->value == NULL || $work->value < 0))
                    style="background: rgba(0,0,0,0.8);"
                    @else
                    style="background:{{$work->infotask->color_rgba}}"
                    @endif

                    class="task_s{{$work->infotask->id}} pointer" contenteditable="true" data-type="{{$work->type}}" data-task-type="{{$type_task->id}}" data-id-student="{{$student->id}}" data-id="{{$work->id}}" id="work{{$work->id}}" data-toggle="modal-work"
                  data-modal="modal-work-comment" data-comment="{{$work->comment}}" data-pattern="{{$work->infotask->pattern}}">
                  @if($work->value != NULL)
                  <span>{{$work->value}}</span>
                  @endif
                </div>
              </td>
              <td><div  data-hover="row_s{{$student->id}}{{$type_task->id}}" id="mark{{$work->id}}" contenteditable="true"
                @if(((int)$int_end < (int)$today && (int)$int_end != "") && ($work->value == NULL || $work->value < 0))
                style="background: rgba(0,0,0,0.8);"
                @else
                style="background:{{$work->infotask->color_rgba}}"
                @endif

                class="task_s{{$work->infotask->id}}">
                @if($work->value != 0)
                {{CalculateController::getMark($rs->id, $work->value)}}
                @else
                -
                @endif

              </div></td>
              @endif
              @endforeach

            </tr>
            @endforeach
          </tbody>

        </table>
</div></div>
    </b-tab>

  @endforeach
