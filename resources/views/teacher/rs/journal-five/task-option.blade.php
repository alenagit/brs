<?
use \App\Http\Controllers\Teacher\RSController;
?>
@extends('layouts.teacher')
@section('title')


    <title>Журнал</title>
  @endsection
  @section('style')




  @endsection
@section('content')
<div class="options">


  <h3>Параметры работ</h3>

  <div class="row">

    <div class="card-deck  mb-3">
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center">Основные параметры</h5>
        </div>
        <div class="card-body">

          {{-- Здесь основные параметры --}}

          <div class="mb-3">
            <label for="name">Выберите работу</label><br />
            <select class="form-control" name="task" id="task">
              @foreach($rs->infotasks as $task)
              @if($task->type == 'test')
              <option value="{{$task->id}}" id="{{$task->id}}"
                data-name="{{ $task->name }}" data-info="{{ $task->info }}" data-number="{{ $task->number }}"
                data-total-score="{{$task->total_score}}" data-date-start="{{ $task->date_start }}" data-date-end="{{ $task->date_end }}"
                data-pattern="{{$task->pattern}}" data-necessary="{{ $task->necessary }}" data-comment="{{ $task->comment }}">
                Тест №{{ $task->number }}
              </option>
              @endif


              @foreach($rs->rstasks as $rtask)
              @if($task->type== "task" && $task->id_info_task == $rtask->id)
              <option value="{{$task->id}}" id="{{$task->id}}"
                data-name="{{ $task->name }}" data-info="{{ $task->info }}" data-number="{{ $task->number }}"
                data-total-score="{{$task->total_score}}" data-date-start="{{ $task->date_start }}" data-date-end="{{ $task->date_end }}"
                data-pattern="{{$task->pattern}}" data-necessary="{{ $task->necessary }}" data-comment="{{ $task->comment }}">
                {{ $rtask->name_task }} №{{ $task->number }}
              </option>
              @endif
              @endforeach

              @if($task->type == 'main_test')
              <option value="{{$task->id}}" id="{{$task->id}}"
                data-name="{{ $task->name }}" data-info="{{ $task->info }}" data-number="{{ $task->number }}"
                data-total-score="{{$task->total_score}}" data-date-start="{{ $task->date_start }}" data-date-end="{{ $task->date_end }}"
                data-pattern="{{$task->pattern}}" data-necessary="{{ $task->necessary }}" data-comment="{{ $task->comment }}">
                Итоговый тест №{{ $task->number }}
              </option>
              @endif
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="necessary" name="necessary"/>
              <label class="form-check-label" for="necessary">Обязательная работа</label>
            </div>
          </div>

        </div>
      </div>


      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center">Основные параметры</h5>
        </div>
        <div class="card-body">

          {{-- Здесь основные параметры --}}

          <div class="mb-3">
            <label for="name">Название</label>
            <input type="text" id="name" name="name" class="form-control"/>
          </div>


        </div>
      </div>

      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center">Сроки</h5>
        </div>
        <div class="card-body">

          {{-- Здесь сроки приема/сдачи работ --}}

          <div class="mb-3">
            <label for="date_start">Начало приема работы</label>
            <input id="date_start" name="date_start" class="form-control" data-toggle="datepicker"/>
          </div>

          <div class="mb-3">
            <label for="date_end">Крайний срок сдачи</label>
            <input id="date_end" name="date_end" class="form-control" data-toggle="datepicker"/>
          </div>

        </div>
      </div>

    </div>
  </div>
  <div class="row text-area-block">
    <div class="card-deck  mb-3">
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center">Описание работы</h5>
        </div>
          <div id="info" name="info" class="summernote"></div>
      </div>
    </div>

    <div class="card-deck mb-3">
      <div class="card mb-4 shadow-sm" style="margin-left: 30px;">
        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center">Критерии оценивания</h5>
        </div>
          <div id="pattern" name="pattern" class="summernote"></div>
      </div>
    </div>
  </div>
</div>
<button class="btn btn-success" id="save_task">Сохранить</button>

@endsection
@section('js')

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.js" defer></script>
<script src="{{ asset('js/lang-summernote.js') }}" defer></script>
<script>


$(document).ready(function() {

  //первая инициализация и заполнение формы
  var id = '';
  var name = '';
  var info = '';
  var total_score = '';
  var date_end = '';
  var date_start = '';
  var pattern = '';
  var necessary = '';
  var first = $('#task option:first');

  name = first.attr("data-name");
  info = first.attr("data-info");
  total_score = first.attr("data-total-score");
  date_end = first.attr("data-date-end");
  date_start = first.attr("data-date-start");
  pattern = first.attr("data-pattern");
  necessary = first.attr("data-necessary");


  $('#name').val(name);
  $('#total_score').val(total_score);
  $('#date_end').val(date_end);
  $('#date_start').val(date_start);
  $('#info').html(info);
  $('#pattern').html(pattern);

  if(necessary == 1){
    $("#necessary").attr("checked","checked");
  }

    $('#pattern').summernote({
      tabsize: 2,
      height: 100,
      lang: 'ru-RU'
    });

    $('#info').summernote({
      tabsize: 2,
      height: 100,
      lang: 'ru-RU'
    });

    $('#info').html(info);
    $('#pattern').html(pattern);


  //-------------------------------------------------------------------


  $('[data-toggle="datepicker"]').datepicker({
  format: 'dd.mm.yyyy',
  language: 'ru-RU'
  });




  //обновление данных внутри полей, после выбор другой работы
  $( "#task" ).change(function() {

    id = $("#task").val();
    name = $('#' + id).attr("data-name");
    info = $('#' + id).attr("data-info");
    total_score = $('#' + id).attr("data-total-score");
    date_end = $('#' + id).attr("data-date-end");
    date_start = $('#' + id).attr("data-date-start");
    pattern = $('#' + id).attr("data-pattern");
    necessary = $('#' + id).attr("data-necessary");


    $('#name').val(name);
    $('#total_score').val(total_score);
    $('#date_end').val(date_end);
    $('#date_start').val(date_start);


    if(necessary == 1){ $("#necessary").attr("checked","checked"); }
    else { $("#necessary").removeAttr("checked");}

    $('#pattern').summernote('destroy');
    $('#info').summernote('destroy');

    $('#info').html(info);
    $('#pattern').html(pattern);

      $('#pattern').summernote({
        tabsize: 2,
        height: 100,
        lang: 'ru-RU'
      });

    $('#info').summernote({
        tabsize: 2,
        height: 100,
        lang: 'ru-RU'
      });

  });


  //Сохранение изменений
  $( "#save_task" ).on('click', function(){

    var patternData = $('#pattern').summernote('code');
    var infoData = $('#info').summernote('code');
    id = $("#task").val();

    axios({
      method: 'post',
      url: '/api/save-task-info',
      data: {
        id: $("#task").val(),
        name: $('#name').val(),
        total_score: $('#total_score').val(),
        date_start: $('#date_start').val(),
        date_end: $('#date_end').val(),
        info: infoData,
        pattern: patternData,
        necessary: $("#necessary").val()

      }
    })
    .then(function (response) {

      $('#' + id).attr("data-name", $('#name').val());
      $('#' + id).attr("data-info", infoData);
      $('#' + id).attr("data-total-score", $('#total_score').val());
      $('#' + id).attr("data-date-end", $('#date_end').val());
      $('#' + id).attr("data-date-start", $('#date_start').val());
      $('#' + id).attr("data-pattern", patternData);
      if($("#necessary").val() == "on") $('#' + id).attr("data-necessary", 1);

    });
  })




});
</script>

@endsection
