<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;

$lesson_score_student = CalculateController::getArrScoreLesson($rs->id, $students);
$stud_lessons = $rs->studentlessons;
$data = CalculateController::getLessonArrs($rs->id, $students);

?>
<div class="flex-table twotable">
  <table class="table left-table  table-bordered">
    <thead>
      <tr>

        <th><div>№</div></th>
        <th><div>ФИО</div></th>
        <th><div>Посещено пар</div></th>
        <th><div>Прогулы</div></th>

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

          {{CalculateController::getHaveLessonStudent($rs->id, $student->id)}}



        </div>
      </td>

      <td>
        <div
        id="prog-{{$student->id}}"  data-hover="row_s{{$student->id}}">

        {{CalculateController::getHookySubgroupStudent($rs->id, $student->id)['progul']}}



      </div>
    </td>

    </tr>
    @endforeach
  </tbody>
</table>



<table id="lesson-ajax-table" class="table right-table table-bordered">
  <thead>
    <tr>

      @foreach($dates as $date)
      <th>
        <span class="del-lesson" data-id="{{$date->id}}">x</span>

        <div
        class="pointer" contenteditable="true" data-toggle="modal-date" id="date{{$date->id}}" data-id="{{$date->id}}" data-optional="{{$date->optional}}"

        data-comment="{{$date->comment}}" data-date="{{$date->date}}" data-color="{{$date->color}}" data-subgroup="{{$date->subgroup}}" data-id-ktp="{{$date->id_ktp}}"

        data-type="{{$date->type}}" style="background:{{$date->color_rgba}}">





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
    <td>

      @if($date->subgroup == User::getSubgroup($student->id))
      <span class="ness-group"></span>
      @endif

      <? $progul = 0;
      if($date->date != NULL)
      {
        if($date->subgroup > 0)
        {
          if($date->subgroup == $student->subgroup)
          {
            if($data['less'. $student->id]['value'][$date->id] == NULL || $data['less'. $student->id]['value'][$date->id] == 0)
            {
              $progul = 1;
            }
          }
        }
        else
        {
          if($data['less'. $student->id]['value'][$date->id] == NULL || $data['less'. $student->id]['value'][$date->id] == 0)
          {
            $progul = 1;
          }
        }

      }
      ?>



      <div

      @if($progul == 1)
   class="progul date_s{{$date->id}} pointer"
   @else
   class="date_s{{$date->id}} pointer"
      @endif


       contenteditable="true" id="les{{$data['less'. $student->id]['id'][$date->id]}}"

      data-hover="row_s{{$student->id}}" data-stud-id="{{$student->id}}" data-id="{{$data['less'. $student->id]['id'][$date->id]}}"

      data-toggle="modal" style="background:{{$date->color_rgba}}">

      @if($data['less'. $student->id]['value'][$date->id] == NULL)
      <span>0</span>
      @else


      <span>{{$data['less'. $student->id]['value'][$date->id]}}</span>

      @endif
    </div>
  </td>



  @endforeach
</tr>
@endforeach
</tbody>
</table>


</div>
<div class="clearfix" style="margin-bottom:20px;"></div>
