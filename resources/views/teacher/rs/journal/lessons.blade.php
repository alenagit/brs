<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;
?>

<b-tab title="Посещаемость" style="display:none;" id="lesson-tab">

  <h3>Посещаемость</h3>

<p><span id="add-column-lesson">Добавить столбец</span></p>
<p style="margin-top: 0px;"><i class="fas fa-walking" style="color: #20c997;"></i> За посещение пар:</p>
<div class="progress">
  <div class="progress-bar" role="progressbar" style="width: 100%; background: rgba(32, 201, 151, .5) !important;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">{{round(CalculateController::scoreOneLesson($rs->id), 1)}}</div>
</div>

<div id="lesson-ajax">
  <div class="flex-table twotable">
    <table class="table left-table  table-bordered">
      <thead>
        <tr>

          <th><div>№</div></th>
          <th><div>ФИО</div></th>
          <th><div>Всего баллов</div></th>

        </tr>
      </thead>
      <tbody>
        @foreach($students as $i => $student)
        <tr>
          <td><div data-hover="row_s{{$student->id}}">{{$i+1}}</div></td>
          <td><div data-hover="row_s{{$student->id}}" data-toggle="tooltip" data-html="true" title="{{$student->name}}">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</div></td>


          <td>
            <div
            id="les-{{$student->id}}" data-toggle="tooltip" data-hover="row_s{{$student->id}}"

            data-html="true" title="Посещаемость: {{CalculateController::donePercentLessons($rs->id, $student->id)}}%">

            {{$lesson_score_student[$student->id]}}

          </div>
        </td>

      </tr>
      @endforeach
    </tbody>
  </table>



  <table class="table right-table table-bordered">
    <thead>
      <tr>
        <th><div>№</div></th>
        <th><div>ФИО</div></th>
        <th><div>Всего баллов</div></th>

        @foreach($dates as $date)
        <th>
          <span class="del-lesson" data-id="{{$date->id}}">x</span>

          <div
          class="pointer" contenteditable="true" data-toggle="modal-date" id="date{{$date->id}}" data-id="{{$date->id}}" data-optional="{{$date->optional}}"

          data-comment="{{$date->comment}}" data-date="{{$date->date}}" data-color="{{$date->color}}" data-subgroup="{{$date->subgroup}}"

          data-type="{{$date->type}}" style="background:{{$date->color_rgba}};color: #fff;">





          @if($date->date == NULL)
          дата
          @else
          {{$date->date}}
          @if($date->subgroup != NULL)
          ({{$date->subgroup}})
          @endif

          @endif



        </div>
      </th>
      @endforeach
    </tr>
  </thead>
  <tbody>

    @foreach($students as $i => $student)
    <tr>
      <td><div data-hover="row_s{{$student->id}}">{{$i+1}}</div></td>
      <td><div data-hover="row_s{{$student->id}}" data-toggle="tooltip" data-html="true" title="{{$student->name}}">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</div></td>


      <td>
        <div
        id="les-{{$student->id}}"  data-hover="row_s{{$student->id}}">

        {{$lesson_score_student[$student->id]}}

      </div>
    </td>


      @foreach($dates as $date)


      @foreach($rs->studentlessons as $i => $lesson)
      @if($student->id == $lesson->id_student && $date->id == $lesson->id_date)
      <td>
        @if($date->subgroup == User::getSubgroup($lesson->id_student))
        <span class="ness-group"></span>
        @endif

        @if(($lesson->value == NULL || $lesson->value == 0) && $date->date != NULL)
        <span id="progul{{$lesson->id}}" class="progul"></span>
        @endif

        <div
        class="date_s{{$date->id}} pointer" contenteditable="true" id="les{{$lesson->id}}"

        data-hover="row_s{{$student->id}}" data-stud-id="{{$student->id}}" data-id="{{$lesson->id}}"

        data-toggle="modal" data-comment="{{$lesson->comment}}" style="background:{{$date->color_rgba}}">

        @if($lesson->value == NULL)
        <span>0</span>
        @else



        <span>{{$lesson->value}}</span>

        @endif
      </div>
    </td>
    @endif
    @endforeach

    @endforeach
  </tr>
  @endforeach
</tbody>
</table>


</div>


<div class="clearfix" style="margin-bottom:20px;"></div>
</div>
</b-tab>
