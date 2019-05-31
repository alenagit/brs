<?
use \App\Http\Controllers\Teacher\AccountController;
?>
@extends('layouts.student')

@section('title')
    <title>Изменение личных данных</title>
  @endsection
@section('content')

<div class="mini-form">
<h1>Изменение личных данных</h1>
<form method="POST" enctype="multipart/form-data">
  @csrf
  <input name="id" value="{{$user->id}}" type="hidden">
  <input name="old_email" value="{{$user->email}}" type="hidden">
  <input name="old_login" value="{{$user->login}}" type="hidden">

  <label for="surname" class="col-form-label">Фамилия</label>
  <input id="surname" autocomple class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{$user->surname}}" required>

  <label for="name" class="col-form-label">Имя</label>
  <input id="name" autocomple class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{$user->name}}" required>

  <label for="patronymic" class="col-form-label">Отчество</label>
  <input id="patronymic" autocomple class="form-control{{ $errors->has('patronymic') ? ' is-invalid' : '' }}" name="patronymic" value="{{$user->patronymic}}" required>


  <label for="email" class="col-form-label">E-mail</label>
  <input id="email" autocomple class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{$user->email}}" autofocus required>

  @if ($errors->has('email'))
  <span class="invalid-feedback" role="alert">
    <strong>{{ $errors->first('email') }}</strong>
  </span>
  @endif

  <label for="login" class="col-form-label">Логин</label>
  <input id="login" class="form-control{{ $errors->has('login') ? ' is-invalid' : '' }}" name="login" value="{{$user->login}}" required>

  @if ($errors->has('login'))
  <span class="invalid-feedback" role="alert">
    <strong>{{ $errors->first('login') }}</strong>
  </span>
  @endif

  <label for="password" class="col-form-label">Новый пароль</label>
  <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

  @if ($errors->has('password'))
  <span class="invalid-feedback" role="alert">
    <strong>{{ $errors->first('password') }}</strong>
  </span>
  @endif

<hr />

  <div class="file-upload">
      <p><label><span>Изменить аватар</span></label></p>
      <input type="file" name="ava" id="ava" accept="image/*,image/jpeg">
  </div>

  @if ($errors->has('ava'))
  <span class="invalid-feedback" role="alert">
    <strong>{{ $errors->first('ava') }}</strong>
  </span>
  @endif

<hr />

@if ($errors->any())
            @foreach ($errors->all() as $error)
                <p class="error">{{ $error }}</p>
            @endforeach
@endif

  <button type="submit" class="btn btn-primary">
  Сохранить
  </button>

</form>
</div>
  @endsection
