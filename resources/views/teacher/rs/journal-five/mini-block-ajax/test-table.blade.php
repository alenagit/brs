<?
use \App\Http\Controllers\Student\CalculateController;
?>
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
<td><div data-hover="row_s{{$student->id}}">{{ CalculateController::getFIO($student->id) }}</div></td>
</tr>
@endforeach
 </tbody>
</table>

<table class="table right-table table-bordered">
  <thead>
    <tr>
      <th><div>Оценка</div></th>

      @foreach($rs->infotasks as $task)
      @if($task->type == 'test')
      <th style="background:{{$task->color_rgba}}" data-name="{{$task->name}}" data-id="{{$task->id}}"  id="task{{$task->id}}" data-info="{{$task->info}}"
        data-pattern="{{$task->pattern}}" data-toggle="modal-work-info" data-date-start="{{$task->date_start}}" data-date-end="{{$task->date_end}}">
        <div >
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
        {{round(CalculateController::markTestStudent($rs->id, $student->id))}}

      </div></td>

      @foreach($rs->studentWorks as $i => $work)
      @if($student->id == $work->id_student && $work->type == 'test')
      <td colspan="2">
        <div data-hover="row_s{{$student->id}}" style="background:{{$work->infotask->color_rgba}}" class="task_s{{$work->infotask->id}}" contenteditable="true" data-type="{{$work->type}}" data-id-student="{{$student->id}}"  data-id="{{$work->id}}" id="work{{$work->id}}" data-toggle="modal-work" data-comment="{{$work->comment}}">
          @if($work->value != NULL)
          {{$work->value}}
          @else
          0
          @endif
        </div>
      </td>
      <td><div data-hover="row_s{{$student->id}}" id="test{{$work->id}}" contenteditable="true" style="background:{{$work->infotask->color_rgba}}" class="task_s{{$work->infotask->id}}">
        {{CalculateController::markTest($rs->id, $work)}}

      </div></td>
      @endif
      @endforeach

    </tr>
    @endforeach
  </tbody>

</table>
</div>
