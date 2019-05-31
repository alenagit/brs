<?
use \App\Http\Controllers\Teacher\RSController;
?>
@extends('layouts.teacher')
@section('title')
    <title>Импорт КТП</title>
  @endsection
@section('content')


            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <h3 class="panel-heading">Импорт КТП</h3>


                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('import_parse_ktp') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="import form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                                <label for="csv_file" class="control-label">Выберите файл для импорта</label>


                                    <input id="csv_file" type="file" name="csv_file" required>

                                    @if ($errors->has('csv_file'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('csv_file') }}</strong>
                                    </span>
                                    @endif

                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="header" checked> Файл содержит названия столбцов
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-upload"></i> Загрузить свой КТП
                                    </button>
                                </div>
                            </div>
                        </form>
                        <hr />

                        <div class="info-ktp-import">

                        <p><a class="dow-ktp" href="/public/files/ktp.xlsx" download><i class="fas fa-download"></i> Скачать пример (excel)</a></p>
                        <p>Значение id_rs выбирайте внизу в соответсвии нужной БРС. ( id_rs заполняется на каждой строке! )</p>
                        <ul>
                        @foreach($rs as $r)
                        <li>{{$r->name}}, {{RSController::getGroupName($r->id_group)}}, <span class="impt-imp">id_rs = {{$r->id}}</span></li>
                        @endforeach
                      </ul>
                      </div>
                    </div>
                </div>
            </div>


@endsection
