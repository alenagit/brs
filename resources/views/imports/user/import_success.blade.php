@extends('layouts.teacher')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Импорт студентов</div>

                    <div class="panel-body">
                        Таблица успешно сохранена
                    </div>
                    <a href="{{ route('teacher') }}">Обратно в кабинет</a>
                </div>
            </div>
        </div>
    </div>
@endsection
