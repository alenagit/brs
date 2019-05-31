<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;

$lesson_score_student = CalculateController::getArrScoreLesson($rs->id, $students);
$stud_lessons = $rs->studentlessons;
$data = CalculateController::getLessonArrs($rs->id, $students);
$done_lesson = CalculateController::doneLessonsTeacher($rs->id);
?>
<div class="less-jur">
<div class="progress">
  <div class="progress-bar bg-white prog-cnt green" role="progressbar" data-toggle="tooltip" data-html="true" title="Балл за пару: {{round(CalculateController::scoreOneLesson($rs->id), 1)}}" style="width: {{CalculateController::getPercentDoneLessonTEACH($rs->id)}}%; border-radius:10px;" aria-valuenow="{{$done_lesson}}" aria-valuemin="0" aria-valuemax="100">@if($done_lesson > 0) {{$done_lesson}} @endif</div>
  <span class="progress-total" data-toggle="tooltip" data-html="true" title="У подгруппы всего {{$rs->total_lesson + $rs->lesson_subgroup}} пар">{{$rs->total_lesson + ($rs->lesson_subgroup * 2)}}</span>
</div>
</div>
<div id="div-lesson-table" class="flex-table twotable" style="margin-bottom: 15px;">
  <table id="main-less" class="table left-table  table-bordered">
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
          id="les-{{$student->id}}"  data-hover="row_s{{$student->id}}">

          {{$lesson_score_student[$student->id]}}

        </div>
      </td>

    </tr>
    @endforeach
  </tbody>
</table>



<table id="lesson-ajax-table" class="table right-table table-bordered" >
  <thead>
    <tr>
      <th style="display:none;"><div>№</div></th>
      <th style="display:none;"><div>ФИО</div></th>
      <th style="display:none;"><div>Всего баллов</div></th>

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

    <td style="display:none;"><div data-hover="row_s{{$student->id}}">{{$i+1}}</div></td>
    <td style="display:none;"><div data-hover="row_s{{$student->id}}" data-toggle="tooltip" data-html="true" title="{{$student->name}}">{{$student->surname." ".mb_substr($student->name, 0, 1).".".mb_substr($student->patronymic, 0, 1)."."}}</div></td>


    <td style="display:none;">
      <div
      id="les-{{$student->id}}"  ss:Type="Number" data-hover="row_s{{$student->id}}">

      {{$lesson_score_student[$student->id]}}

    </div>
  </td>

    @foreach($dates as $date)
    <td>

      @if($date->subgroup == User::getSubgroup($student->id))
      <span class="ness-group"></span>
      @endif

      <? $progul = 0; $subg = 0;
      if($date->date != NULL)
      {
        if($date->subgroup > 0)
        {
          if($date->subgroup == $student->subgroup)
          {
            $subg = 1;
            if($data['less'. $student->id]['value'][$date->id] == NULL || $data['less'. $student->id]['value'][$date->id] == 0)
            {
              $progul = 1;
            }
          }
          else{$subg = 2;}
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

   @if($subg == 2)
      class="notthissub date_s{{$date->id}} pointer"
   @endif

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
