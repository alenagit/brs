
<?
use \App\Http\Controllers\Student\CalculateController;
$data = CalculateController::statusKTP($rs->id);
$i = 1;

?>

<div id="date-comment">
  <p class="head-comment">Комментарий <span id="close">x</span></p>
  <p class="colors"><span id="red"></span><span id="blue"></span><span id="green"></span><span id="yellow"></span></span><span id="violet"></span></p>
  <p><input type="color" id="bg-info-date" value="#ffffff"><span id="reset-color-lesson" class="btn"><i class="fas fa-eraser"></i> Сброс</span></p>
  <hr class="minihr" />


<p><input id="comment-date" type="text" placeholder="Комментарий" class="form-control"></p>


<p><select id="task-id" class="form-control">
  <option value="-1">Лекция</option>
@foreach($rs->rstasks as $task)
  <option value="{{$task->id}}">{{$task->name_task}}</option>
@endforeach
</select></p>

<p><select id="subgroup" class="form-control">
  <option value="0">Весь поток</option>
  <option value="1">Подгруппа 1</option>
  <option value="2">Подгруппа 2</option>
</select></p>

<p><select id="ktp-select" class="form-control">
  <option value="-1">Выберите тему урока</option>
@foreach($data['ktps'] as $h_k => $ktp)
  <option id="{{$ktp->id}}option" value="{{$ktp->id}}" data-number="{{$i}}" data-hour-ost="{{$data['done_hour'][$ktp->id]}}" data-all-hour="{{$ktp->hour}}" data-name="{{$ktp->name}}, {{$ktp->type}}">{{$i}}.
    @if($data['done_hour'][$ktp->id] == 0)
    ✓
    @else
    Осталось {{$data['done_hour'][$ktp->id]}} из {{$ktp->hour}}.
    @endif
    {{$ktp->name}}, {{$ktp->type}}</option>
  <?$i++;?>
@endforeach
</select></p>

<hr />

<div class="mb-3">
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" id="optional-date" name="optional-date"/>
    <label class="form-check-label" for="optional-date">Необязательная пара</label>
  </div>
</div>

<hr />
<p><button class="btn btn-success btn-default" type="button" id="save-date"><i class="fas fa-save"></i> Сохранить</button></p>

</div>

<div id="form-comment-info">
  <p class="head-comment">Информация <span id="close-info">x</span></p>
    <p class="colors"><span id="red"></span><span id="blue"></span><span id="green"></span><span id="yellow"></span></span><span id="violet"></span></p>
  <p><input type="color" id="bg-info-work" value="#ffffff"> <span id="save-color-work" class="btn"><i class="fas fa-save"></i> Сохранить</span><span id="reset-color-work" class="btn"><i class="fas fa-eraser"></i> Сброс</span></p>
  <hr class="minihr" />
  <div id="name-work-div">
    <p>от: <span id="date_start"></span></p>
    <p>по: <span id="date_end"></span></p>
  </div>
  <hr class="minihr" />
  <div id="name-work-div">
    <p><b><em>Название:</em></b></p>
    <p id="name_work"></p>
  </div>
  <div id="pattern-work-div">
    <p><b><em>Описание:</em></b></p>
    <p id="info_work"></p>
  </div>
</div>



<div id="form-comment-work">
  <p class="head-comment">Комментарий <span id="close-comment">x</span></p>
  <div id="pattern-work-div">
    <p><b><em>Критерии:</em></b></p>
    <div id="pattern-work"></div>
  </div>
  <textarea id="comment" type="text" placeholder="Комментарий" class="form-control"></textarea>
  <p><button style="margin-top:10px;" class="btn btn-success btn-default" type="button" id="but-work"><i class="fas fa-save"></i> Сохранить</button></p>
</div>

<div id="form-comment-bb">
  <p class="head-comment">Комментарий <span id="close-comment-bb">x</span></p>
  <p><select id="operation" class="form-control">
    <option value="null" selected>Выберите операцию</option>
    <option value="0">+</option>
    <option value="1">-</option>
    <option value="2">*</option>
    <option value="3">/</option>
  </select></p>
  <p><input id="plus-pp" type="number" placeholder="Значение" class="form-control"></p>
  <textarea id="comment-bb" type="text" placeholder="Комментарий" class="form-control"></textarea>
  <p><button style="margin-top:10px;" class="btn btn-success btn-default" type="button" id="but-comment-bb"><i class="fas fa-save"></i> Сохранить</button></p>
</div>

<div id="form-info-bb">
  <p class="head-comment">Комментарий <span id="close-info-bb">x</span></p>
  <p class="colors"><span id="red"></span><span id="blue"></span><span id="green"></span><span id="yellow"></span></span><span id="violet"></span></p>
  <p><input type="color" id="bg-info-bb" value="#ffffff"> <span id="reset-bg-bb" style="padding-left: 10px;"><i class="fas fa-eraser"></i> Сбросить</span></p>
  <hr class="minihr" />
  <p><input id="name-info-bb" type="text" placeholder="Имя столбца" class="form-control"></p>
  <textarea id="comment-info-bb" type="text" placeholder="Комментарий" class="form-control"></textarea>
  <p><button style="margin-top:10px;" class="btn btn-success btn-default" type="button" id="but-info-bb"><i class="fas fa-save"></i> Сохранить</button></p>
</div>
