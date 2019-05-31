<?
use \App\Http\Controllers\Student\CalculateController;
?>

@foreach($rs->rstasks as $kt => $type_task)



<b-tab title="{{$type_task->name_task}}" style="display:none;" icon="ti-user">


  <h3>{{$type_task->name_task}}</h3>
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
              <th><div>Общая оценка</div></th>

              @foreach($rs->infotasks as $task)
              @if($task->id_info_task == $type_task->id)
              <th class="pointer" style="background:{{$task->color_rgba}}" data-name="{{$task->name}}" data-id="{{$task->id}}" data-color="{{$task->color}}"
                id="task{{$task->id}}" data-info="{{$task->info}}" data-pattern="{{$task->pattern}}" data-toggle="modal-work-info" data-date-start="{{$task->date_start}}" data-date-end="{{$task->date_end}}">
                <div>
                  @if($task->necessary == 1)
                  <span class="ness" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Обязательно для сдачи</b>"></span>
                  @endif
                  {{$type_task->name_task}} №{{$task->number}} (оценка)
                </div>
              </th>

              @endif
              @endforeach

            </tr>
          </thead>
          <tbody>

            @foreach($students as $i => $student)
            <tr>

              <td><div data-hover="row_s{{$student->id}}{{$type_task->id}}" id="total_task_score{{$student->id}}{{$type_task->id}}">
                {{round($rstasks[$type_task->id][$student->id])}}

              </div></td>

              @foreach($rs->infotasks as $task)

              @if($task->id_info_task == $type_task->id)

              <? $work = CalculateController::getTaskStudentFive($rs->id, $student->id, $task->id);  ?>

              <td>
                <div data-hover="row_s{{$student->id}}{{$type_task->id}}" style="background:{{$task->color_rgba}}"
                  class="task_s{{$task->id}} pointer" contenteditable="true" data-type="{{$work->type}}" data-task-type="{{$type_task->id}}"
                  data-id-student="{{$student->id}}" data-id="{{$work->id}}" id="work{{$work->id}}" data-toggle="modal-work"
                  data-modal="modal-work-comment" data-comment="{{$work->comment}}" data-pattern="{{$task->pattern}}">

                  @if($work->value != NULL)
                  <span>{{$work->value}}</span>
                  @endif

                </div>
              </td>


              @endif
              @endforeach

            </tr>
            @endforeach
          </tbody>

        </table>
</div></div>
    </b-tab>

  @endforeach
