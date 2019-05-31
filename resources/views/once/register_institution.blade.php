@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Регистрация Учебного заведения</div>

        <div class="card-body">
          {!! Form::open(['url' => 'reg']) !!}

          <div class="form-group row">
            {!! Form::label('name', 'Название учебного заведения', ['class' => 'col-sm-4 col-form-label text-md-right']) !!}

            <div class="col-md-6">
              {!! Form::text('name', null, ['class' => 'form-control']) !!}

              @if ($errors->has('name'))
              <span class="invalid-feedback" role="alert" style="display:block">
                <strong>{{ $errors->first('name') }}</strong>
              </span>
              @endif
            </div>

          </div>

          <div class="form-group row">
            {!! Form::label('type', 'Тип учебного заведения', ['class' => 'col-sm-4 col-form-label text-md-right']) !!}
            <div class="col-md-6">

              {!! Form::select('type', [
              'Колледж' => 'Колледж',
              'Техникум' => 'Техникум',
              'Университет' => 'Университет',
              'Институт' => 'Институт'
              ], null, ['class' => 'form-control'], ['placeholder' => 'Выберите'])  !!}

              @if ($errors->has('type'))
              <span class="invalid-feedback" role="alert" style="display:block">
                <strong>{{ $errors->first('type') }}</strong>
              </span>
              @endif
            </div>
            
          </div>
          {{ Form::submit('Сохранить', ['class' => 'btn btn-primary']) }}

          {!! Form::close() !!}

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
