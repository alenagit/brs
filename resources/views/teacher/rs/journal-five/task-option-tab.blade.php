<?
use \App\Http\Controllers\Teacher\RSController;
?>
<b-tab title="Параметры работ" style="display:none;" id="options">
<div class="options">


  <h3>Параметры работ</h3>

  <div class="row">

    <div class="card-deck  mb-3">
      <div class="card mb-6 shadow-sm">
        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center">Основные параметры</h5>
        </div>
        <div class="card-body">

          {{-- Здесь основные параметры --}}

          <div class="mb-3">
            <label for="name">Выберите работу</label><br />
            <select class="form-control" name="task" id="task">
              @foreach($rs->infotasks as $task)
              @if($task->type == 'test')
              <option value="{{$task->id}}" id="{{$task->id}}"
                data-name="{{ $task->name }}" data-info="{{ $task->info }}" data-number="{{ $task->number }}"
                data-total-score="{{$task->total_score}}" data-date-start="{{ $task->date_start }}" data-date-end="{{ $task->date_end }}"
                data-pattern="{{$task->pattern}}" data-necessary="{{ $task->necessary }}" data-comment="{{ $task->comment }}">
                Тест №{{ $task->number }}
              </option>
              @endif


              @foreach($rs->rstasks as $rtask)
              @if($task->type== "task" && $task->id_info_task == $rtask->id)
              <option value="{{$task->id}}" id="{{$task->id}}"
                data-name="{{ $task->name }}" data-info="{{ $task->info }}" data-number="{{ $task->number }}"
                data-total-score="{{$task->total_score}}" data-date-start="{{ $task->date_start }}" data-date-end="{{ $task->date_end }}"
                data-pattern="{{$task->pattern}}" data-necessary="{{ $task->necessary }}" data-comment="{{ $task->comment }}">
                {{ $rtask->name_task }} №{{ $task->number }}
              </option>
              @endif
              @endforeach

              @if($task->type == 'main_test')
              <option value="{{$task->id}}" id="{{$task->id}}"
                data-name="{{ $task->name }}" data-info="{{ $task->info }}" data-number="{{ $task->number }}"
                data-total-score="{{$task->total_score}}" data-date-start="{{ $task->date_start }}" data-date-end="{{ $task->date_end }}"
                data-pattern="{{$task->pattern}}" data-necessary="{{ $task->necessary }}" data-comment="{{ $task->comment }}">
                Итоговый тест №{{ $task->number }}
              </option>
              @endif
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="name">Название</label>
            <input type="text" id="name" name="name" class="form-control"/>
          </div>

          <div class="mb-3">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="necessary" name="necessary"/>
              <label class="form-check-label" for="necessary">Обязательная работа</label>
            </div>
          </div>

        </div>
      </div>

      <div class="card mb-6 shadow-sm">
        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center">Сроки</h5>
        </div>
        <div class="card-body">

          {{-- Здесь сроки приема/сдачи работ --}}

          <div class="mb-3">
            <label for="date_start">Начало приема работы</label>
            <input id="date_start" name="date_start" class="form-control" data-toggle="datepicker"/>
          </div>

          <div class="mb-3">
            <label for="date_end">Крайний срок сдачи</label>
            <input id="date_end" name="date_end" class="form-control" data-toggle="datepicker"/>
          </div>

        </div>
      </div>

    </div>
  </div>
  <div class="card-deck  mb-3">

      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center">Описание работы</h5>
        </div>
          <div id="info" name="info" class="summernote"></div>
      </div>


    <div class="card mb-6 shadow-sm">

        <div class="card-header bg-secondary text-white">
          <h5 class="my-0 font-weight-normal text-center">Критерии оценивания</h5>
        </div>
          <div id="pattern" name="pattern" class="summernote"></div>

    </div>
  </div>
</div>
<button class="btn btn-success" id="save_task">Сохранить</button>

</b-tab>
