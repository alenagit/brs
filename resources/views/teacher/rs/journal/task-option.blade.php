<?
use \App\Http\Controllers\Teacher\RSController;
use \App\Http\Controllers\Student\CalculateController;
?>
@extends('layouts.teacher')
@section('title')


    <title>Параметры работ</title>
  @endsection
  @section('style')




  @endsection
@section('content')
<div class="cab-teacher">

  <div class="btn-task-opt">
    @foreach($rss as $brs)

    <a class="brs-task-opt
    @if(strpos(Request::url(), "$brs->id") !== false)
    active-task-opt
    @endif
    " href="{!! route('task.option', ['id' => $brs->id]) !!}">{{$brs->name}}<br /> <span style="color:#20c997">{{RSController::getGroupName($brs->id_group)}}</span></a>

    @endforeach
  </div>

    <h3>Параметры работ <br/><span style="font-size:18px;">{{$rs->name}}, <span style="color:#20c997">{{RSController::getGroupName($rs->id_group)}}</span></span></h3>

  <div class="flex-row">
    <div class="tasks-student" style="margin-top: 0px;">
      <div class="blur-block"></div>
      <div class="flex-title">Просмотр практических работ (вид студента)</div>
      <div class="content-task-stud">
        @include('teacher.rs.tasks-stud', ['rs' => $rs])
      </div>
    </div>
  </div>


<div class="options">




  <div class="flex-row" style="padding-bottom: 30px;">
    <div class="options" style="position: relative;">
      <div class="blur-block"></div>
      <div class="flex-title">Параметры работ  <button style="float:right;background: transparent;border: none;padding: 0px;" class="btn btn-success" id="save_task"><i class="fas fa-save"></i> Сохранить</button></div>
   <div class="flex-content">
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
                <select class="form-control" name="task" data-id="{{$rs->id}}" id="task{{$rs->id}}" data-select="task">
                  @foreach($rs->infotasks as $task)
                  @if($task->type == 'test')
                  <option data-id="{{$rs->id}}" value="{{$task->id}}" id="{{$task->id}}"
                    data-name="{{ $task->name }}" data-info="{{ $task->info }}" data-number="{{ $task->number }}"
                    data-total-score="{{$task->total_score}}" data-date-start="{{ $task->date_start }}" data-date-end="{{ $task->date_end }}"
                    data-pattern="{{$task->pattern}}" data-necessary="{{ $task->necessary }}" data-comment="{{ $task->comment }}">
                    Тест №{{ $task->number }}
                  </option>
                  @endif


                  @foreach($rs->rstasks as $rtask)
                  @if($task->type== "task" && $task->id_info_task == $rtask->id)
                  <option data-id="{{$rs->id}}" value="{{$task->id}}" id="{{$task->id}}"
                    data-name="{{ $task->name }}" data-info="{{ $task->info }}" data-number="{{ $task->number }}"
                    data-total-score="{{$task->total_score}}" data-date-start="{{ $task->date_start }}" data-date-end="{{ $task->date_end }}"
                    data-pattern="{{$task->pattern}}" data-necessary="{{ $task->necessary }}" data-comment="{{ $task->comment }}" data-score="{{CalculateController::scoreOneTask($rs->id, $task->id)}}">
                    {{ $rtask->name_task }} №{{ $task->number }}
                  </option>
                  @endif
                  @endforeach

                  @if($task->type == 'main_test')
                  <option data-id="{{$rs->id}}" value="{{$task->id}}" id="{{$task->id}}"
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
                  <input data-id="{{$rs->id}}" class="form-check-input" type="checkbox" id="necessary{{$rs->id}}" name="necessary"/>
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
                <input data-id="{{$rs->id}}" type="text" id="name{{$rs->id}}" name="name" class="form-control"/>
              </div>

              <div class="mb-3">
                <label for="total_score">Балл за выполнение</label>
                <input data-id="{{$rs->id}}" type="number" id="total_score{{$rs->id}}" name="total_score" placeholder="" class="form-control"/>
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
                <input data-id="{{$rs->id}}" id="date_start{{$rs->id}}" name="date_start" class="form-control" data-toggle="datepicker"/>
              </div>

              <div class="mb-3">
                <label for="date_end">Крайний срок сдачи</label>
                <input data-id="{{$rs->id}}" id="date_end{{$rs->id}}" name="date_end" class="form-control" data-toggle="datepicker"/>
              </div>

            </div>
          </div>

        </div>
      </div>
      <div class="row text-area-block" style="width: 100%">
        <div class="card-deck  mb-3">
          <div class="card mb-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
              <h5 class="my-0 font-weight-normal text-center">Описание работы</h5>
            </div>
              <div data-id="{{$rs->id}}" id="info{{$rs->id}}" name="info" class="summernote"></div>
          </div>
        </div>

        <div class="card-deck mb-3">
          <div class="card mb-4 shadow-sm" style="margin-left: 30px;">
            <div class="card-header bg-secondary text-white">
              <h5 class="my-0 font-weight-normal text-center">Критерии оценивания</h5>
            </div>
              <div data-id="{{$rs->id}}" id="pattern{{$rs->id}}" name="pattern" class="summernote"></div>
          </div>
        </div>
      </div>
      <button class="btn btn-success task-select-save" id="save_task"><i class="fas fa-save"></i> Сохранить</button>
      </div>

    </div>



    </div>




</div>
@endsection
@section('js')

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.js" defer></script>
<script src="{{ asset('js/lang-summernote.js') }}" defer></script>
<script>


$(document).ready(function() {
  var id = '';
  var name = '';
  var info = '';
  var total_score = '';
  var date_end = '';
  var date_start = '';
  var pattern = '';
  var necessary = '';
  var sid_rs = 0;
  var sid_task = 0;
  var score = 0;


  $(document).on('click', '[data-select="task"]', function(){

    //обновление данных внутри полей, после выбор другой работы


      id = $(this).val();
      console.log(id);
      sid_rs = $(this).attr("data-id");
      name = $('#' + id).attr("data-name");
      info = $('#' + id).attr("data-info");
      total_score = $('#' + id).attr("data-total-score");
      date_end = $('#' + id).attr("data-date-end");
      date_start = $('#' + id).attr("data-date-start");
      pattern = $('#' + id).attr("data-pattern");
      necessary = $('#' + id).attr("data-necessary");
      score = $('#' + id).attr("data-score");


      $('#date_end' + sid_rs).datepicker({
      format: 'dd.mm.yyyy',
      language: 'ru-RU'
      });

      $('#date_start' + sid_rs).datepicker({
      format: 'dd.mm.yyyy',
      language: 'ru-RU'
      });


      $('#name' + sid_rs).val(name);
      $('#total_score' + sid_rs).val(total_score);
      $('#date_end' + sid_rs).val(date_end);
      $('#date_start' + sid_rs).val(date_start);
      $('#total_score' + sid_rs).attr('placeholder', score);




      if(necessary == 1){ $("#necessary" + sid_rs).attr("checked","checked"); }
      else { $("#necessary" + sid_rs).removeAttr("checked");}

      $('#pattern' + sid_rs).summernote('destroy');
      $('#info' + sid_rs).summernote('destroy');

      $('#info' + sid_rs).html(info);
      $('#pattern' + sid_rs).html(pattern);

        $('#pattern' + sid_rs).summernote({
          tabsize: 2,
          height: 200,
          lang: 'ru-RU'
        });

      $('#info' + sid_rs).summernote({
          tabsize: 2,
          height: 200,
          lang: 'ru-RU'
        });

    });

    $(document).on('click', '#save_task', function(){

      var patternData = $('#pattern' + sid_rs).summernote('code');
      var infoData = $('#info' + sid_rs).summernote('code');
      sid_task = $("#task" + sid_rs).val()

      var task_date_start = $('#date_start' + sid_rs).val();
      var task_date_end = $('#date_end' + sid_rs).val();


      axios({
        method: 'post',
        url: '/api/save-task-info',
        data: {
          id: $("#task" + sid_rs).val(),
          name: $('#name' + sid_rs).val(),
          total_score: $('#total_score' + sid_rs).val(),
          date_start: $('#date_start' + sid_rs).val(),
          date_end: $('#date_end' + sid_rs).val(),
          info: infoData,
          pattern: patternData,
          necessary: $("#necessary" + sid_rs).prop("checked")

        }
      })
      .then(function (response) {

        $('#stud_task_start' + sid_task).text($('#date_start' + sid_rs).val());
        $('#stud_task_end' + sid_task).text($('#date_end' + sid_rs).val());
        $('#stud_task_name' + sid_task).text($('#name' + sid_rs).val());
        $('#stud_task_total_score' + sid_task).text($('#total_score' + sid_rs).val());
        $('#stud_task_info' + sid_task).html(infoData);
        $('#stud_task_pattern' + sid_task).html(patternData);



        $('#' + sid_task).attr("data-name", $('#name' + sid_rs).val());
        $('#' + sid_task).attr("data-info", infoData);
        $('#' + sid_task).attr("data-total-score", $('#total_score' + sid_rs).val());
        $('#' + sid_task).attr("data-date-end", $('#date_end' + sid_rs).val());
        $('#' + sid_task).attr("data-date-start", $('#date_start' + sid_rs).val());
        $('#' + sid_task).attr("data-pattern", patternData);
        if($("#necessary" + sid_rs).val() == "on") $('#' + sid_task).attr("data-necessary", 1);

        $('.save-show').addClass('showw');
        setTimeout(function () {
            $('.save-show').removeClass('showw');
        }, 1000);

      });
    });

});
</script>

@endsection
