<?
use \App\Http\Controllers\FunctionController;
$mass_create = array(); ?>
@extends('layouts.teacher')

@section('content')

@section('css')
  <script src="{{ asset('js/jquery-2.0.3.min.js') }}" type="text/javascript"></script>
@endsection

<h3>Все ранее созданные напоминания</h3>
<div id="app4">

  <b-tabs pills>
    <br />
    @foreach($reminders as $reminder)

    @if(!isset($mass_create[$reminder->theme]) || (isset($mass_create[$reminder->theme]) && $mass_create[$reminder->theme] != $reminder->date))

      <b-tab title="{{$reminder->theme}}">
        <? $mass_create += [$reminder->theme => $reminder->date]; ?>
        <p class="head-task-must">
          <span class="left-task-must">{{$reminder->theme}}</span>

          <span class="right-task-must">{{$reminder->date}}</span>
        </p>

        <div class="body-task-must">
        {!! $reminder->info !!}
        <br />
        <br />
        @foreach($reminders as $reminder_name)
        @if($reminder->theme == $reminder_name->theme && $reminder->date == $reminder_name->date)
        <span class="tag-name">{{FunctionController::getNameTeacher($reminder_name->id_whom)}}</span>
        @endif
        @endforeach
        </div>

        </b-tab>
        @endif


        @endforeach
      </b-tabs>
  
  </div>

@section('js')


<script src="//unpkg.com/babel-polyfill@latest/dist/polyfill.min.js"></script>
<script src="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js"></script>

<script>
$(".spoiler-trigger").click(function() {
		$(this).parent().next().collapse('toggle');
	});


var app4 = new Vue({
el: '#app4'
})
</script>
@endsection
@endsection
