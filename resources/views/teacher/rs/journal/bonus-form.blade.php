<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;
$there_students = CalculateController::studentThereToday($rs->id);
$one_there_students = CalculateController::studentSubgroup1($rs->id);
$two_there_students = CalculateController::studentSubgroup2($rs->id);

$att_five = CalculateController::studentAtt5($rs->id);
$att_four = CalculateController::studentAtt4($rs->id);
$att_three = CalculateController::studentAtt3($rs->id);
$att_two = CalculateController::studentAtt2($rs->id);

?>

<div class="row no-gutters">
  <div class="col-6">
    <div class="bonus-form">

  <div class="header">
    Проставление бонусных баллов
  </div>
<p><span data-toggle="tooltip" data-placement="top" data-html="true" title="По умолчанию рандом выбирает студентов, которые присутствуют на паре, но можно сделать выборку среди подгрупп и по аттестации">Списки студентов:</span></p>
  <div class="btn-group" data-toggle="buttons">
  <label class="btn btn-primary active" id="default-rad">
    <input type="radio" name="options" autocomplete="off" checked> По умолчанию
  </label>
  <label class="btn btn-primary" id="sub-rad">
    <input type="radio" name="options" autocomplete="off"> Подгуппы
  </label>
  <label class="btn btn-primary" id="att-rad">
    <input type="radio" name="options" autocomplete="off"> По оценке аттестации
  </label>
  <label class="btn btn-primary" id="list-rad">
    <input type="radio" name="options" autocomplete="off"> Выбрать в списке
  </label>
</div>

<hr class="up" />
<div class="inline">
  <div id="values">
    <p id="values-ajax">
      @foreach($rs->valuerands->sortBy('value'); as $value)
      @if($value->type == 'value')
      <span class="btn-val" id="{{$value->id}}" data-value="{{$value->value}}">
      <span class="del-val" data-id="{{$value->id}}">&nbsp;</span>
      {{$value->value}}
      </span>
      @endif
      @endforeach
       </p>
<div class="inline">
    <input type="number" class="form-control" id="value_bonuse" placeholder="Количество баллов">
    <button type="button" class="btn btn-success" id="save_value" data-toggle="tooltip" data-placement="top" data-html="true" title="Сохранить значение для многоразового использования">+</button>
    </div>
    <p id="error-value">Обязательно для заполнения</p>
  </div>

  <div id="themes">
    <p id="themes-ajax">
    @foreach($rs->valuerands as $theme)
    @if($theme->type == 'theme')
    <span class="btn-theme" id="{{$theme->id}}" data-theme="{{$theme->value}}">
      <span class="del-theme" data-id="{{$theme->id}}">&nbsp;</span>
      {{$theme->value}}
    </span>
    @endif
    @endforeach
  </p>
<div class="inline">
    <input type="text" class="form-control" id="theme_bonuse" placeholder="Тема вопроса">
    <button type="button" class="btn btn-success" id="save_theme"  data-toggle="tooltip" data-placement="top" data-html="true" title="Сохранить значение для многоразового использования">+</button>
    </div>
  </div>
</div>
<hr class="up" />

<div class="inline-100" style="position:relative;">
    <span id="round">1</span>
    <button type="button" class="btn btn-primary" id="getRand">Выбрать студента</button>
    <span id="name_student">Студент</span><span id="message"></span>
<div class="pad-left">
    <button type="button" class="btn btn-success" id="right">Ответил</button>
    <button type="button" class="btn btn-danger" id="wrong">Не ответил</button>
</div>
</div>
</div>
</div>
<div class="col-6">
<div class="bonus-form">
  <div class="header">
    Список опрашиваемых студентов
  </div>
<div class="row">
  <select class="form-control width-auto selest-stud" id="att-select">
    <option selected disabled>Выберите группу</option>
    <option value="5">Аттестованные на 5</option>
    <option value="4">Аттестованные на 4</option>
    <option value="3">Аттестованные на 3</option>
    <option value="2">Аттестованные на 2</option>
  </select>

  <select class="form-control width-auto selest-stud" id="sub-select">
    <option selected disabled>Выберите группу</option>
    <option value="1">Подгруппа 1</option>
    <option value="2">Подгруппа 2</option>
  </select>

<div id="select-students">
  <select class="form-control width-auto selest-stud" id="list-select" multiple data-toggle="tooltip" data-placement="top" data-html="true" title="С зажатой клавишой 'ctrl' можно выделить нескольких студентов">

    <option selected disabled>Выберите студентов</option>
    @foreach($there_students as $t_student)
    <option value="{{$t_student}}">{{CalculateController::getFIO($t_student)}}</option>
    @endforeach

  </select>
</div>

</div>
<div class="row list" id="default" style="display:block;">
  <div class="students">
    <div id="there-students">
      <p>Присутствующие на паре</p>
    <ol>
      @foreach($there_students as $t_student)
      <li class="stud{{$t_student}} stud">
        {{ CalculateController::getFIO($t_student) }}
      </li>
      @endforeach
    </ol>
    </div>
</div>
</div>
<div class="row list" id="subgroup1">
    <div id="subgroup-1-ajax">
      <p>Подгруппа №1</p>
    <ol>

      @foreach($one_there_students as $one)
      <li class="stud{{$one}} stud">
        {{ CalculateController::getFIO($one) }}
      </li>
      @endforeach
    </ol>
    </div>
</div>

<div class="row list" id="subgroup2">
    <div id="subgroup-2-ajax">
      <p>Подгруппа №2</p>
    <ol>
      @foreach($two_there_students as $two)
      <li class="stud{{$two}} stud">
        {{ CalculateController::getFIO($two) }}
      </li>
      @endforeach
    </ol>
    </div>
</div>

<div class="row list" id="att5">
    <div id="att-5-ajax">
      <p>Аттестованы на "5"</p>
    <ol>
      @foreach($att_five as $at_five)
      <li class="stud{{$at_five}} stud">
        {{ CalculateController::getFIO($at_five) }}
      </li>
      @endforeach
    </ol>
    </div>
</div>

<div class="row list" id="att4">
    <div id="att-4-ajax">
      <p>Аттестованы на "4"</p>
    <ol>
      @foreach($att_four as $at_four)
      <li class="stud{{$at_four}} stud">
        {{ CalculateController::getFIO($at_four) }}
      </li>
      @endforeach
    </ol>
    </div>
</div>

<div class="row list"  id="att3">
    <div id="att-3-ajax">
      <p>Аттестованы на "3"</p>
    <ol>
      @foreach($att_three as $at_three)
      <li class="stud{{$at_three}} stud">
        {{ CalculateController::getFIO($at_three) }}
      </li>
      @endforeach
    </ol>
    </div>
</div>

<div class="row list"  id="att2">
    <div id="att-2-ajax">
      <p>Аттестованы на "2"</p>
    <ol>
      @foreach($att_two as $at_two)
      <li class="stud{{$at_two}} stud">
        {{ CalculateController::getFIO($at_two) }}
      </li>
      @endforeach
    </ol>
    </div>
</div>
<p type="hidden" id="id_rs" data-rs="{{$rs->id}}"></p>
  </div>


</div>
</div>
</div>
