<?
use \App\Http\Controllers\Teacher\RSController;
?>
@extends('layouts.teacher')
@section('title')
    <title>Редактирование БРС</title>
  @endsection
@section('content')
<h4>Редактирование БРС</h4>
<h5>{{$rs->name}}, {{RSController::getGroupName($rs->id_group)}}</h5>
<hr />
<div class="create-rs">


<form method="post" >
{!! csrf_field() !!}
<input style="display:none;" type="text" name="id_rs" value="{{$rs->id}}" class="form-control"/>
<input style="display:none;" type="text" name="id_teacher" value="{{Auth::user()->id}}" class="form-control"/>
<input style="display:none;" type="text" name="id_institution" value="{{Auth::user()->id_institution}}" class="form-control"/>


<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" id="type" name="type" @if($rs->type == 1) checked="checked" @endif />
  <label class="form-check-label" for="type">Зачет</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" id="check_bb" name="check_bb" @if($rs->bonuse == 1) checked="checked" @endif />
  <label class="form-check-label" for="check_bb">Бонусные баллы</label>
</div>


<hr />
<div class="row" >
  <div class="col-7">
    <div class="card-deck mb-3 ">
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center ">Основные параметры</h5>
        </div>
        <div class="card-body custom-padd">

          {{-- Здесь основные параметры начинаются --}}



          <div class="mb-3">
            <label for="name">Название БРС</label>
          <input type="text" id="name" name="name" class="form-control" placeholder="Например: Материаловедение " value="{{$rs->name}}"/>
          @include('mini-blocks.span_errors',['name' => 'name'])
          </div>

          <div class="mb-3">
            <label for="total_score">Количество баллов на дисциплину</label>
          <input type="number" id="total_score" name="total_score" class="form-control" value="{{$rs->total_score}}"/>
          @include('mini-blocks.span_errors',['name' => 'total_score'])
          </div>


        {{-- Здесь основные параметры заканчиваются --}}

      </div>
      </div>
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center">Аудиторные занятия</h5>
        </div>
        <div class="card-body">

          {{-- Здесь ауд. параметры начинаются --}}

          <div class="mb-3">
            <label for="total_lesson">Количество лекций</label>
          <input type="number" id="total_lesson" name="total_lesson" class="form-control" value="{{$rs->total_lesson}}"/>
          @include('mini-blocks.span_errors',['name' => 'total_lesson'])
          </div>

          <div class="mb-3">
            <label for="total_lesson_half">Количество пар по подгруппам</label>
          <input type="number" id="total_lesson_half" name="total_lesson_half" class="form-control"  value="{{$rs->lesson_subgroup}}"/>
          </div>

          <div class="mb-3">
            <label for="total_lesson_score">Количество баллов за посещение</label>
          <input type="number" id="total_lesson_score" name="total_lesson_score" class="form-control" value="{{$rs->total_lesson_score}}"/>
          @include('mini-blocks.span_errors',['name' => 'total_lesson_score'])
          </div>



          {{-- Здесь ауд. параметры начинаются --}}

        </div>
      </div>
    </div>
  </div>
  <div class="col-5">

  </div>
</div>
<div class="row">
  <div class="col-7">
<div class="card-deck mb-3 ">
  @if($rs->total_test > 0)
  <div class="card mb-4 shadow-sm" id="block-test">
    <div class="card-header bg-secondary text-white">
      <h5 class="my-0 font-weight-normal text-center">Тесты <span class="del-rs" id="del-test">x</span></h5>
    </div>
    <div class="card-body">

      {{-- Здесь тестовые параметры начинаются --}}

      <div class="mb-3">
        <label for="total_test">Количество тестов</label>
      <input type="number" id="total_test" name="total_test" class="form-control" value="{{$rs->total_test}}"/>
      </div>

      <div class="mb-3">
        <label for="total_test_score">Количество баллов за тесты</label>
      <input type="number" id="total_test_score" name="total_test_score" class="form-control" value="{{$rs->total_test_score}}"/>
      </div>


      {{-- Здесь тестовые параметры начинаются --}}

    </div>
  </div>
  @endif

  @if($rs->total_main_test > 0)
  <div class="card mb-4 shadow-sm" id="block-main-test">
    <div class="card-header bg-secondary text-white">
      <h5 class="my-0 font-weight-normal text-center ">Итоговые тесты <span class="del-rs" id="del-main-test">x</span></h5>
    </div>
    <div class="card-body custom-padd">

      {{-- Здесь итог тестовые параметры начинаются --}}

      <div class="mb-3">
        <label for="total_main_test">Количество итоговых тестов</label>
      <input type="number" id="total_main_test" name="total_main_test" class="form-control" value="{{$rs->total_main_test}}"/>
      </div>

      <div class="mb-3">
        <label for="total_main_test_score">Количество баллов за итоговые тесты</label>
      <input type="number" id="total_main_test_score" name="total_main_test_score" class="form-control" value="{{$rs->total_main_test_score}}"/>
      </div>


      {{-- Здесь итог тестовые параметры начинаются --}}

    </div>
  </div>
  @endif
</div>
</div>
</div>

<div class="row">
  <div class="col-7">
<div class="card-deck mb-3 ">
  @foreach($rs->rstasks as $task)
  <div class="card mb-4 shadow-sm" id="block-task{{$task->id}}">
    <div class="card-header bg-secondary text-white">
      <h5 class="my-0 font-weight-normal text-center">Параметры работы <span class="del-rs" id="del-task" data-id="{{$task->id}}">x</span></h5>
    </div>
    <div class="card-body">

      <div class="mb-3">
        <label for="name-task-{{$task->id}}">Название работы</label>
      <input type="text" id="name-task-{{$task->id}}" name="name-task-{{$task->id}}" class="form-control" value="{{$task->name_task}}"/>
      </div>

      <div class="mb-3">
        <label for="total-task-{{$task->id}}">Количество работ</label>
      <input type="number" id="total-task-{{$task->id}}" name="total-task-{{$task->id}}" class="form-control" value="{{$task->total_task}}"/>
      </div>

      <div class="mb-3">
        <label for="score-task-{{$task->id}}">Количество баллов за все работы</label>
      <input type="number" id="score-task-{{$task->id}}" name="score-task-{{$task->id}}" class="form-control" value="{{$task->total_task_score}}"/>
      </div>


    </div>
  </div>
  @endforeach

</div>
</div>
</div>



<div class="row">
  <div class="col-7">
<div class="card-deck mb-3">
<task-component></task-component>
</div>
</div>
</div>
<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Сохранить</button>
</form>
</div>
@endsection
@section('js')
<script>
$(document).ready(function () {

  $(document).on('click', '#del-task', function(){
    var id = $(this).attr('data-id');
    $('#block-task' + id).css('display','none');
    $('#total-task-' + id).val(0);
  });

  $(document).on('click', '#del-main-test', function(){
    var id = $(this).attr('data-id');
    $('#block-main-test').css('display','none');
    $('#total_main_test').val(0);
  });

  $(document).on('click', '#del-test', function(){
    var id = $(this).attr('data-id');
    $('#block-test').css('display','none');
    $('#total_test').val(0);
  });


});

</script>
@endsection
