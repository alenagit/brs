<?
use \App\Http\Controllers\Student\CalculateController;
?>
@if($rs->total_test > 0)
<b-tab title="Тесты" style="display:none;" id="test">
  <h3>Тесты</h3>
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
  <td><div data-hover="row_s{{$student->id}}">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</div></td>
  </tr>
  @endforeach
   </tbody>
  </table>

  <table class="table right-table table-bordered">
    <thead>
      <tr>
        <th><div>Всего баллов</div></th>

        @foreach($rs->infotasks as $task)
        @if($task->type == 'test')
        <th style="background:{{$task->color_rgba}}" data-name="{{$task->name}}" data-id="{{$task->id}}"  id="task{{$task->id}}" data-info="{{$task->info}}"
          data-pattern="{{$task->pattern}}" data-toggle="modal-work-info">
          <div class="row_s{{$student->id}}">
            @if($task->necessary == 1)
            <span class="ness" data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Обязательно для сдачи</b>"></span>
            @endif
          Тест №{{$task->number}}
          </div>
        </th>
        <th >
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
          {{$test_score_student[$student->id]}}

        </div></td>

        @foreach($rs->studentWorks as $i => $work)
        @if($student->id == $work->id_student && $work->type == 'test')
        <td colspan="2">
          <div data-hover="row_s{{$student->id}}" style="background:{{$work->infotask->color_rgba}}" class="task_s{{$work->infotask->id}}" contenteditable="true" data-type="{{$work->type}}" data-id-student="{{$student->id}}"  data-id="{{$work->id}}" id="work{{$work->id}}" data-toggle="modal-work" data-comment="{{$work->comment}}">
            @if($work->value != NULL)
            <span>{{$work->value}}</span>
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

</b-tab>
@endif

@if($rs->total_main_test > 0)
<b-tab title="Итоговые тесты" style="display:none;" id="main_test">
  <h3>Итоговые тесты</h3>
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
  <td><div data-hover="row_s{{$student->id}}">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</div></td>
  </tr>
  @endforeach
   </tbody>
  </table>

  <table class="table right-table table-bordered">
    <thead>
      <tr>
        <th><div>Всего баллов</div></th>

        @foreach($rs->infotasks as $task)
        @if($task->type == 'main_test')
        <th style="background:{{$task->color_rgba}}" data-name="{{$task->name}}" data-id="{{$task->id}}" id="task{{$task->id}}" data-info="{{$task->info}}" data-pattern="{{$task->pattern}}" data-toggle="modal-work-info">
          <div>
            @if($task->necessary == 1)
            <span class="ness" data-toggle="tooltip" data-html="true" data-placement="top" title="<b>Обязательно для сдачи</b>"></span>
            @endif

          Итоговый тест №{{$task->number}}
          </div>
        </th>
        <th >
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
        <td><div data-hover="row_s{{$student->id}}" id="maintest{{$student->id}}">
       {{$main_test_score_student[$student->id]}}

        </div></td>

        @foreach($rs->studentWorks as $i => $work)
        @if($student->id == $work->id_student && $work->type == 'main_test')
        <td colspan="2">
          <div data-hover="row_s{{$student->id}}" style="background:{{$work->infotask->color_rgba}}" class="task_s{{$work->infotask->id}}" contenteditable="true"  data-type="{{$work->type}}" data-id-student="{{$student->id}}" data-id="{{$work->id}}" id="work{{$work->id}}" data-toggle="modal-work" data-comment="{{$work->comment}}">
            @if($work->value != NULL)
            <span>{{$work->value}}</span>
            @endif
          </div>
        </td>
        <td><div data-hover="row_s{{$student->id}}" id="maintest{{$work->id}}" contenteditable="true" style="background:{{$work->infotask->color_rgba}}" class="task_s{{$work->infotask->id}}">
          {{CalculateController::markTest($rs->id, $work)}}

        </div></td>
        @endif
        @endforeach

      </tr>
      @endforeach
    </tbody>


  </table>
  </div>
  </b-tab>
  @endif
