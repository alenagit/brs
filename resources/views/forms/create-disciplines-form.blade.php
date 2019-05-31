

  <div class="form-group">
      {!! Form::label('number', 'Номер дисциплины') !!}
      {!! Form::text('number', null, ['class' => 'form-control number-discipline']) !!}

      @if ($errors->has('number'))
      <span class="invalid-feedback" role="alert" style="display:block">
        <strong>{{ $errors->first('number') }}</strong>
      </span>
      @endif


  </div>


<div class="form-group">
    {!! Form::label('name', 'Название дисциплины') !!}
    {!! Form::text('name', null, ['class' => 'form-control name-discipline'], ['placeholder' => 'Например: Русский язык']) !!}

    @if ($errors->has('name'))
    <span class="invalid-feedback" role="alert" style="display:block">
      <strong>{{ $errors->first('name') }}</strong>
    </span>
    @endif


</div>

<div class="form-group">
    {!! Form::label('mdk', 'МДК') !!}
    {!! Form::text('mdk', null, ['class' => 'form-control mdk'], ['placeholder' => 'Например: Русский язык']) !!}

    @if ($errors->has('mdk'))
    <span class="invalid-feedback" role="alert" style="display:block">
      <strong>{{ $errors->first('mdk') }}</strong>
    </span>
    @endif


</div>


<button type="submit" class="btn btn-primary" onclick="createDiscipline($('.number-discipline').val(), $('.name-discipline').val(), $('.mdk').val())">
    Сохранить
</button>
