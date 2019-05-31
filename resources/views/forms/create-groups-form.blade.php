

<div class="form-group">
    {!! Form::label('year_adms', 'Год поступления') !!}
    {!! Form::number('year_adms', 2018, ['class' => 'form-control']) !!}

    @if ($errors->has('year_adms'))
    <span class="invalid-feedback" role="alert" style="display:block">
      <strong>{{ $errors->first('year_adms') }}</strong>
    </span>
    @endif


</div>

<div class="form-group">
  {!! Form::label('id_specialty', 'Специальность') !!}

  <select  id="id_specialty" class="form-control{{ $errors->has('id_specialty') ? ' is-invalid' : '' }}" name="id_specialty" value="{{ old('id_specialty') }}" required autofocus>
    @foreach($specialties as $specialty)
    <option value = "{{ $specialty->id }}">{{ $specialty->name }}</option>
    @endforeach
  </select>


    @if ($errors->has('id_specialty'))
    <span class="invalid-feedback" role="alert" style="display:block">
      <strong>{{ $errors->first('id_specialty') }}</strong>
    </span>
    @endif

</div>


<button type="submit" class="btn btn-primary" onclick="createGroup($('#id_specialty').val(), $('#year_adms').val())">
    Сохранить
</button>
