<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;
?>

<b-tab title="Посещаемость" style="display:none;" id="lesson-tab">

  <?
  if(isset($_COOKIE["total_columns"]))
  {
    $pag = $dates->chunk($_COOKIE["total_columns"]);
  }
  else {
    $pag = $dates->chunk(10);
  }
  ?>
  <h3>Посещаемость</h3>

<p><span id="add-column-lesson">Добавить столбец</span></p>
<div id="lesson-ajax">
  <div class="flex-table twotable">
    <table class="table left-table  table-bordered">
      <thead>
        <tr>

          <th><div>№</div></th>
          <th><div>ФИО</div></th>
          <th><div>Посещено пар</div></th>

        </tr>
      </thead>
      <tbody>
        @foreach($students as $i => $student)
        <tr>
          <td><div data-hover="row_s{{$student->id}}">{{$i+1}}</div></td>
          <td><div data-hover="row_s{{$student->id}}">{{ CalculateController::getFIO($student->id) }}</div></td>


          <td>
            <div
            id="les-{{$student->id}}" data-toggle="tooltip" data-hover="row_s{{$student->id}}" data-html="true">

            {{CalculateController::sumLessonStudent($rs->id, $student->id)}}

          </div>
        </td>

      </tr>
      @endforeach
    </tbody>
  </table>



  <table class="table right-table table-bordered">
    <thead>
      <tr>

        @foreach($dates as $date)
        <th>
          <span class="del-lesson" data-id="{{$date->id}}">x</span>

          <div
          class="pointer" contenteditable="true" data-toggle="modal-date" id="date{{$date->id}}" data-id="{{$date->id}}" data-optional="{{$date->optional}}"

          data-comment="{{$date->comment}}" data-date="{{$date->date}}" data-color="{{$date->color}}" data-subgroup="{{$date->subgroup}}"

          data-type="{{$date->type}}" style="background:{{$date->color_rgba}};">





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


      @foreach($dates as $date)


      @foreach($rs->studentlessons as $i => $lesson)
      @if($student->id == $lesson->id_student && $date->id == $lesson->id_date)

      <td>
        @if($date->subgroup == User::getSubgroup($lesson->id_student))
        <span class="ness-group"></span>
        @endif

        @if(($lesson->value == NULL || $lesson->value == 0) && $date->date != NULL)
        <span class="progul"></span>
        @endif

        <div
        class="date_s{{$date->id}} pointer" contenteditable="true" id="les{{$lesson->id}}" @if($date->date != NULL) data-date-has="1" @endif

        data-hover="row_s{{$student->id}}" data-stud-id="{{$student->id}}" data-id="{{$lesson->id}}"

        data-toggle="modal" data-comment="{{$lesson->comment}}" style="background:{{$date->color_rgba}}">

        @if($lesson->value != NULL)



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
