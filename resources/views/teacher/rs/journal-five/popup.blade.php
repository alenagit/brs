
<div id="date-comment">
  <p class="head-comment">Комментарий <span id="close">x</span></p>
  <p><input type="color" id="bg-info-date" value="#ffffff"></p>


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
<hr />

<div class="mb-3">
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" id="optional-date" name="optional-date"/>
    <label class="form-check-label" for="optional-date">Необязательная пара</label>
  </div>
</div>

<hr />
<p><button class="btn btn-success btn-default" type="button" id="save-date">Сохранить</button></p>

</div>

<div id="form-comment-info">
  <p class="head-comment">Информация <span id="close-info">x</span></p>
  <p><input type="color" id="bg-info-work" value="#ffffff"> <span id="save-color-work" class="btn">Сохранить</span><span id="reset-color-work" class="btn">Сброс</span></p>
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

<div id="form-comment-bb">
  <p class="head-comment">Комментарий <span id="close-comment-bb">x</span></p>
  <textarea id="comment-bb" type="text" placeholder="Комментарий" class="form-control"></textarea>
  <p><button style="margin-top:10px;" class="btn btn-success btn-default" type="button" id="but-comment-bb">Сохранить</button></p>
</div>

<div id="form-info-bb">
  <p class="head-comment">Комментарий <span id="close-info-bb">x</span></p>
  <p><input type="color" id="bg-info-bb" value="#ffffff"> <span id="reset-bg-bb">Сбросить</span></p>
  <p><input id="name-info-bb" type="text" placeholder="Имя столбца" class="form-control"></p>
  <textarea id="comment-info-bb" type="text" placeholder="Комментарий" class="form-control"></textarea>
  <p><button style="margin-top:10px;" class="btn btn-success btn-default" type="button" id="but-info-bb">Сохранить</button></p>
</div>
