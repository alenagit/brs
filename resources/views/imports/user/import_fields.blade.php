@extends('layouts.teacher')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Импорт студентов</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('import_process_user') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="csv_data_file_id" value="{{ $csv_data_file->id }}" />

                            <table class="table">
                                @if (isset($csv_header_fields))
                                <tr>
                                    @foreach ($csv_header_fields as $csv_header_field)
                                        <th>{{ $csv_header_field }}</th>
                                    @endforeach
                                </tr>
                                @endif
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
                                                @foreach (config('app.db_fields_user') as $db_field_user)
                                                    <option value="{{ (\Request::has('header')) ? $db_field_user : $loop->index }}"
                                                        @if ($key === $db_field_user) selected @endif>{{ $db_field_user }}</option>
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
        </div>
    </div>
@endsection