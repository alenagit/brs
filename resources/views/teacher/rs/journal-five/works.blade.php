<div class="form-row">
<div class="form-group col-md-4">
  <label>Показывать окно для ввода комментария:</label>
  <select v-model="selected_com" @change="cookies_comment" class="form-control">
    <option v-for="(op, key) in comment_op" >
      @{{ op }}
    </option>
  </select>
</div>
</div>

<br />
<?
if(isset($_COOKIE["total_columns"]))
{
  $pag = $dates->chunk($_COOKIE["total_columns"]);
}
else {
  $pag = $dates->chunk(10);
}
 ?>
  <h3>Работы</h3>
    <b-tabs pills >
<div class="flex-table tasks">

<table class="table left-table table-bordered">
  <thead>
    <tr>
      <th><div>№</div></th>
      <th><div>ФИО</div></th>
    </tr>
  </thead>
  <tbody>
  @foreach($students as $i => $student)
  <tr>
  <td><div>{{$i+1}}</div></td>
<td><div>{{$student->name}}</div></td>
</tr>
@endforeach
  </tbody>
</table>




@foreach($rs->rstasks as $kt => $type_task)

@if($kt== 0)
<b-tab title="{{$type_task->name_task}}" active>
  @else
  <b-tab title="{{$type_task->name_task}}" style="display:none;">
    @endif


        <table class="table right-table table-bordered ">
          <thead>
            <tr>
              <th><div>Всего баллов</div></th>

              @foreach($rs->infotasks as $task)
              @if($task->type == 'task' && $task->id_info_task == $type_task->id)
              <th data-name="{{$task->name}}" id="{{$task->id}}" data-info="{{$task->info}}" data-pattern="{{$task->pattern}}" data-toggle="modal-work-info">
                <div>
                  {{$task->rstask->name_task}} №{{$task->number}}
                </div>
              </th>
              <th><div>Оценка</div></th>
              @endif
              @endforeach

            </tr>
          </thead>
          <tbody>

            @foreach($students as $i => $student)
            <tr>

              <td><div></div></td>

              @foreach($rs->studentWorks as $i => $work)
              @if($student->id == $work->id_student && $work->type == 'task' && $type_task->id == $work->infotask->rstask->id)
              <td>
                <div contenteditable="true" id="{{$work->id}}" data-toggle="modal-work"  data-modal="modal-work-comment" data-comment="{{$work->comment}}" data-pattern="{{$work->infotask->pattern}}">
                  @if($work->value != NULL)
                  <span>{{$work->value}}</span>
                  @endif
                </div>
              </td>
              <td><div contenteditable="true">

              </div></td>
              @endif
              @endforeach

            </tr>
            @endforeach
          </tbody>

        </table>

    </b-tab>

  @endforeach

<b-tab title="Тесты" style="display:none;">
  <table class="table right-table table-bordered">
    <thead>
      <tr>
        <th><div>Всего баллов</div></th>

        @foreach($rs->infotasks as $task)
        @if($task->type == 'test')
        <th contenteditable="true">
          <div>
          Тест №{{$task->number}}
          </div>
        </th>
        <th >
          <div contenteditable="true" id="{{$task->id}}" data-toggle="modal-question">
            {{$task->total_question}}
          </div>
        </th>
        <th >
          <div>
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
        <td><div></div></td>

        @foreach($rs->studentWorks as $i => $work)
        @if($student->id == $work->id_student && $work->type == 'test')
        <td colspan="2">
          <div contenteditable="true" id="{{$work->id}}" data-toggle="modal-work" data-comment="{{$work->comment}}">
            @if($work->value != NULL)
            <span>{{$work->value}}</span>
            @endif
          </div>
        </td>
        <td><div contenteditable="true">

        </div></td>
        @endif
        @endforeach

      </tr>
      @endforeach
    </tbody>

  </table>

</b-tab>

<b-tab title="Итоговые тесты" style="display:none;">
  <table class="table right-table table-bordered">
    <thead>
      <tr>
        <th><div>Всего баллов</div></th>

        @foreach($rs->infotasks as $task)
        @if($task->type == 'main_test')
        <th contenteditable="true">
          <div>
          Итоговый тест №{{$task->number}}
          </div>
        </th>
        <th >
          <div contenteditable="true" id="{{$task->id}}" data-toggle="modal-question">
            {{$task->total_question}}
          </div>
        </th>
        <th >
          <div>
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
        <td><div></div></td>

        @foreach($rs->studentWorks as $i => $work)
        @if($student->id == $work->id_student && $work->type == 'main_test')
        <td colspan="2">
          <div contenteditable="true" id="{{$work->id}}" data-toggle="modal-work" data-comment="{{$work->comment}}">
            @if($work->value != NULL)
            <span>{{$work->value}}</span>
            @endif
          </div>
        </td>
        <td><div contenteditable="true">

        </div></td>
        @endif
        @endforeach

      </tr>
      @endforeach
    </tbody>


  </table>
  </b-tab>


</b-tabs>


</div>
<div class="clearfix"></div>
<div class="clearfix"></div>
<br/>
<div id="form-comment-info">
  <p class="head-comment">Информация <span id="close-info">x</span></p>

  <div id="pattern-work-div">
    <p><b><em>Название:</em></b></p>
  <p id="name_work"></p>
  </div>
  <div id="pattern-work-div">
    <p><b><em>Описание:</em></b></p>
  <p id="info_work"></p>
  </div>

  <p><a href="{!! route('task.option', ['id' => $rs->id]) !!}">Редактировать работы</a></p>


</div>

<div id="form-comment-work">
  <p class="head-comment">Комментарий <span id="close-comment">x</span></p>
  <div id="pattern-work-div">
    <p><b><em>Критерии:</em></b></p>
    <div id="pattern-work"></div>
  </div>
  <textarea id="comment" type="text" placeholder="Комментарий" class="form-control"></textarea>
  <p><button style="margin-top:10px;" class="btn btn-success btn-default" type="button" id="but-work">Сохранить</button></p>
</div>
