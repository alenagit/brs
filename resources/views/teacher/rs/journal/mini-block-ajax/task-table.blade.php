<?
use \App\Http\Controllers\Student\CalculateController;
?>

@foreach($rs->rstasks as $kt => $type_task)

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
  <td><div data-hover="row_s{{$student->id}}{{$type_task->id}}">{{ CalculateController::getFIO($student->id) }}</div></td>
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
                id="task{{$task->id}}" data-info="{{$task->info}}" data-pattern="{{$task->pattern}}" data-toggle="modal-work-info">
                <div>
                  @if($task->necessary == 1)
                  <span class="ness" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Обязательно для сдачи</b>"></span>
                  @endif
                  №{{$task->number}}
                </div>
              </th>
              <th style="background:{{$task->color_rgba}}" class="task_s{{$task->id}}"><div>Оценка</div></th>
              @endif
              @endforeach

            </tr>
          </thead>
          <tbody>

            @foreach($students as $i => $student)
            <tr>

              <td><div data-hover="row_s{{$student->id}}{{$type_task->id}}" id="total_task_score{{$student->id}}{{$type_task->id}}">
                {{CalculateController::scoreTaskOneTypeStudent($rs->id, $student->id, $type_task->id)}}

              </div></td>

              @foreach($rs->studentWorks as $i => $work)
              @if($student->id == $work->id_student && $work->type == 'task' && $type_task->id == $work->infotask->rstask->id)
              <td>
                <div data-hover="row_s{{$student->id}}{{$type_task->id}}" style="background:{{$work->infotask->color_rgba}}" class="task_s{{$work->infotask->id}} pointer" contenteditable="true" data-type="{{$work->type}}" data-task-type="{{$type_task->id}}" data-id-student="{{$student->id}}" data-id="{{$work->id}}" id="work{{$work->id}}" data-toggle="modal-work"
                  data-modal="modal-work-comment" data-comment="{{$work->comment}}" data-pattern="{{$work->infotask->pattern}}">
                  @if($work->value != NULL)
                  <span>{{$work->value}}</span>
                  @endif
                </div>
              </td>
              <td><div  data-hover="row_s{{$student->id}}{{$type_task->id}}" id="mark{{$work->id}}" contenteditable="true" style="background:{{$work->infotask->color_rgba}}" class="task_s{{$work->infotask->id}}">
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
</div>


  @endforeach
