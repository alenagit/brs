

<div class="form-group">
    {!! Form::label('number', 'Номер специальности') !!}
    {!! Form::text('number', null, ['class' => 'form-control number-specialty'], ['id' => 'number-specialty']) !!}

    @if ($errors->has('number'))
    <span class="invalid-feedback" role="alert" style="display:block">
      <strong>{{ $errors->first('number') }}</strong>
    </span>
    @endif

</div>

<div class="form-group">
  {!! Form::label('name', 'Название специальности') !!}
  {!! Form::text('name', null, ['class' => 'form-control name-specialty'], ['id' => 'name-specialty']) !!}

    @if ($errors->has('name'))
    <span class="invalid-feedback" role="alert" style="display:block">
      <strong>{{ $errors->first('name') }}</strong>
    </span>
    @endif

</div>

<button type="submit" class="btn btn-primary" onclick="createSpecialty($('.number-specialty').val(), $('.name-specialty').val())">
    Сохранить
</button>
