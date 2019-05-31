@extends('layouts.teacher')
@section('title')
    <title>Импорт КТП</title>
  @endsection
@section('content')

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <h3 class="panel-heading">Проверка полей</h3>
                    <p>Здесь отображаются только первые 2 строки, для проверки, что столбцы и содержимое на нужных местах.</p>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('import_process_ktp') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="csv_data_file_id" value="{{ $csv_data_file->id }}" />

                            <table class="table table-hover table-dark" style="border-radius: 10px;overflow: hidden;">
                              <thead>


                                @if (isset($csv_header_fields))
                                <tr>
                                    @foreach ($csv_header_fields as $csv_header_field)
                                        <th>{{ $csv_header_field }}</th>
                                    @endforeach
                                </tr>
                                @endif
                                </thead>
                                @foreach ($csv_data as $row)
                                    <tr>
                                    @foreach ($row as $key => $value)
                                        <td>{{ $value }}</td>
                                    @endforeach
                                    </tr>
                                @endforeach
                                <tr>
                                    @foreach ($csv_data[0] as $key => $value)
                                        <td>
                                            <select name="fields[{{ $key }}]">
                                                @foreach (config('app.db_fields_ktp') as $db_field_ktp)
                                                    <option value="{{ (\Request::has('header')) ? $db_field_ktp : $loop->index }}"
                                                        @if ($key === $db_field_ktp) selected @endif>{{ $db_field_ktp }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @endforeach
                                </tr>
                            </table>

                            <button type="submit" class="btn btn-primary">
                                Сохранить
                            </button>
                        </form>
                    </div>
                </div>
            </div>

@endsection
