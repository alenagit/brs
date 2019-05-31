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
    Проставление бонусных оценок
  </div>
<p>Списки студентов:</p>
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

  <div style="display:none;" id="values">
<div class="inline">
    <input type="number" style="display:none;" class="form-control" id="value_bonuse" placeholder="Количество баллов">
    <button type="button" style="display:none;" class="btn btn-success" id="save_value">+</button>
    </div>
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
    <button type="button" class="btn btn-success" id="save_theme">+</button>
    </div>
  </div>
  <p id="values-ajax" class="ps-values">
    <span class="btn-val" data-value="5">5</span>
    <span class="btn-val" data-value="4">4</span>
    <span class="btn-val" data-value="3">3</span>
    <span class="btn-val" data-value="2">2</span>
    <span class="btn-val" data-value="1">+</span>

     </p>
     <p id="error-value" class="ps-values">Выберите значение</p>


</div>

<div class="inline-100" style="position:relative;">
    <span id="round">1</span>
    <button type="button" class="btn btn-primary" id="getRand">Выбрать студента</button>
    <span id="name_student">Студент</span><span id="message"></span>
<div class="pad-left">
    <button type="button" class="btn btn-success" id="right">Выставить оценку</button>
</div>
</div>
</div>
</div>
<div class="col-6" style="margin-left:20px; max-width: calc(50% - 20px);">
<div class="bonus-form">
  <div class="header">
    Список опрашиваемых студентов
  </div>
<div class="row">
  <select class="form-control width-auto selest-stud" id="att-select">
    <option selected disabled>По аттестации</option>
    <option value="5">Аттестованные на 5</option>
    <option value="4">Аттестованные на 4</option>
    <option value="3">Аттестованные на 3</option>
    <option value="2">Аттестованные на 2</option>
  </select>

  <select class="form-control width-auto selest-stud" id="sub-select">
    <option selected disabled>По подгруппе</option>
    <option value="1">Подгруппа 1</option>
    <option value="2">Подгруппа 2</option>
  </select>

<div id="select-students">
  <select class="form-control width-auto selest-stud" id="list-select" multiple>

    <option selected disabled>Студенты</option>
    @foreach($there_students as $t_student)
    <option value="{{$t_student}}">{{ CalculateController::getFIO($t_student) }}</option>
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
