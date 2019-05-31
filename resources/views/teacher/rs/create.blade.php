<?
use \App\Http\Controllers\Teacher\RSController;
?>
@extends('layouts.teacher')
@section('title')
    <title>Создание БРС</title>
  @endsection
@section('content')
<h4>Создание БРС</h4>
<hr />
<div class="create-rs">
<form method="post" >
{!! csrf_field() !!}

<input style="display:none;" type="text" name="id_teacher" value="{{Auth::user()->id}}" class="form-control"/>
<input style="display:none;" type="text" name="id_institution" value="{{Auth::user()->id_institution}}" class="form-control"/>




<label for="name">Название БРС</label>
<input type="text" id="name" name="name" class="form-control" placeholder="Например: Материаловедение "/>
@include('mini-blocks.span_errors',['name' => 'name'])
<br />

<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" id="type" name="type"/>
  <label class="form-check-label" for="type">Зачет</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" id="check_test" name="check_test" true-value="true"  false-value="false" v-model="ch_test"/>
  <label class="form-check-label" for="check_test">Тесты</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" id="check_main_test" name="check_main_test" true-value="true"  false-value="false" v-model="ch_main_test"/>
  <label class="form-check-label" for="check_main_test">Итоговые тесты</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" id="check_bb" name="check_bb"/>
  <label class="form-check-label" for="check_bb">Бонусные баллы</label>
</div>


<hr />
<div class="row" >
  <div class="col-12">
    <div class="card-deck mb-3 ">
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center ">Основные параметры</h5>
        </div>
        <div class="card-body custom-padd">

          {{-- Здесь основные параметры начинаются --}}

          <div class="mb-3">
            <label for="id_discipline">Дисциплина</label>
          <select id="id_discipline" name="id_discipline" class="form-control" value="Нет данных">
            @foreach($disciplines as $discipline)
            <option value="{{$discipline->id}}">{{$discipline->name}}</option>
            @endforeach
          </select>
          </div>


          <div class="mb-3">
            <label for="id_group">Группа студентов</label>
          <select id="id_group" name="id_group" class="form-control" value="Нет данных">
            @foreach($groups as $group)
            <option value="{{$group->id}}">
              {{RSController::getGroupName($group->id)}}
            </option>
            @endforeach
          </select>
          </div>

          <div class="mb-3">
            <label for="total_score">Количество баллов на дисциплину</label>
          <input type="number" id="total_score" name="total_score" class="form-control" v-model.number="total_score"/>
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
          <input type="number" id="total_lesson" name="total_lesson" class="form-control"/>
          @include('mini-blocks.span_errors',['name' => 'total_lesson'])
          </div>

          <div class="mb-3">
            <label for="total_lesson_half" data-toggle="tooltip" data-html="true" title="Поле заполняется только когда в группе две подгруппы и для них существуют раздельные пары. Данное количество пар будет увеличено вдвое, для кабинета преподавателя">Количество пар на одну подгруппу</label>
          <input type="number" id="total_lesson_half" name="total_lesson_half" class="form-control"/>
          </div>

          <div class="mb-3">
            <label for="total_lesson_score">Количество баллов за посещение</label>
          <input type="number" id="total_lesson_score" name="total_lesson_score" class="form-control" v-model.number="total_lesson_score"/>
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
<div class="row" v-if="ch_test === 'true' || ch_main_test === 'true'">
  <div class="col-12">
<div class="card-deck mb-3 ">
  <div class="card mb-4 shadow-sm" v-if="ch_test === 'true'">
    <div class="card-header bg-secondary text-white">
      <h5 class="my-0 font-weight-normal text-center">Тесты</h5>
    </div>
    <div class="card-body">

      {{-- Здесь тестовые параметры начинаются --}}

      <div class="mb-3">
        <label for="total_test">Количество тестов</label>
      <input type="number" id="total_test" name="total_test" class="form-control"/>
      </div>

      <div class="mb-3">
        <label for="total_test_score">Количество баллов за тесты</label>
      <input type="number" id="total_test_score" name="total_test_score" class="form-control" v-model.number="total_test_score"/>
      </div>


      {{-- Здесь тестовые параметры начинаются --}}

    </div>
  </div>
  <div class="card mb-4 shadow-sm" v-if="ch_main_test === 'true'">
    <div class="card-header bg-secondary text-white">
      <h5 class="my-0 font-weight-normal text-center ">Итоговые тесты</h5>
    </div>
    <div class="card-body custom-padd">

      {{-- Здесь итог тестовые параметры начинаются --}}

      <div class="mb-3">
        <label for="total_main_test">Количество итоговых тестов</label>
      <input type="number" id="total_main_test" name="total_main_test" class="form-control"/>
      </div>

      <div class="mb-3">
        <label for="total_main_test_score">Количество баллов за итоговые тесты</label>
      <input type="number" id="total_main_test_score" name="total_main_test_score" class="form-control" v-model.number="total_main_test_score"/>
      </div>


      {{-- Здесь итог тестовые параметры начинаются --}}

    </div>
  </div>
</div>
</div>
</div>

<div class="row">
  <div class="col-12">
<div class="card-deck mb-3">
<task-component></task-component>
</div>
</div>
</div>
<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Создать</button>
</form>
</div>
@endsection
