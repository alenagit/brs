@extends('layouts.teacher')
@section('title')
    <title>Импорт КТП</title>
  @endsection
@section('content')

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <h3 class="panel-heading">Импорт КТП завершен</h3>

                    <div class="panel-body success">
                        <i class="fas fa-thumbs-up"></i> Таблица успешно сохранена
                    </div>
                    <a class="back-imp" href="{{ route('teacher') }}"><i class="fas fa-arrow-circle-left"></i> Обратно в кабинет</a>
                </div>
            </div>

@endsection
