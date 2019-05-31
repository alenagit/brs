<?
use \App\Http\Controllers\Teacher\RSController;
use \App\Http\Controllers\Student\CalculateController;
$id_user = Auth::user()->id;
?>
@extends('layouts.student')
@section('title')
<title>Кабинет</title>
@endsection
@section('content')
<div class="cab-student">
  <div class="rss">
@foreach($rss as $rs)
@include('student.mini-block.progress', ['rs' => $rs])
@endforeach
 </div>
</div>
@endsection
