<?
use \App\Http\Controllers\Student\CalculateController;
$mass_dates = CalculateController::getMassDateBonuse($rs->id);
?>

<div id="bonuse-table">
  <div class="flex-table twotable">
  <table class="table left-table  table-bordered">
   <thead>
     <tr>
       <th><div class="rowspan2">№</div></th>
       <th><div class="rowspan2">ФИО</div></th>
     </tr>
   </thead>
   <tbody>
   @foreach($students as $i => $student)
   <tr>
   <td><div data-hover="row_s{{$student->id}}">{{$i+1}}</div></td>
  <td><div data-hover="row_s{{$student->id}}">{{ CalculateController::getFIO($student->id) }}</div></td>
  </tr>
  @endforeach
   </tbody>
  </table>

  <table class="table right-table table-bordered">
    <thead>
      <tr>
        <th rowspan="2"><div class="rowspan2">Всего баллов</div></th>

        @foreach($mass_dates as $mass_date => $cols)
        <th colspan="{{$cols}}"><div>
          {{$mass_date}}
        </div></th>
        @endforeach
        </tr>

        <tr>

        @foreach($mass_dates as $mass_date => $cols)

        @foreach($rs->datebonuse as $i => $date)
        @if($mass_date == $date->date)

        <th><span class="del-col-bb" data-id="{{$date->id}}">x</span><div id="bb-col{{$date->id}}" data-toggle="bb-info" data-id="{{$date->id}}"
          data-comment="{{$date->comment}}" data-name="{{$date->name}}" data-color="{{$date->color}}" class="pointer" style="background:{{$date->color_rgba}};">
          @if(!empty($date->name))
          {{$date->name}}
          @else
          {{$date->round}}
          @endif
        </div></th>
        @endif

        @endforeach
        @endforeach

        </tr>


    </thead>

    <tbody>

      @foreach($students as $i => $student)
            <tr>
              <td><div data-hover="row_s{{$student->id}}" id="sumbb{{$student->id}}"> {{ CalculateController::scoreBBStudent($rs->id, $student->id) }} </div></td>
              @foreach($rs->datebonuse as $i => $date)
              <? $has = 0;?>

              @foreach($rs->studentBonuses as $i => $bonuse)

              @if($student->id == $bonuse->id_student && $bonuse->id_date_bonuses == $date->id)
              <? $has = 1;?>
              <td><div
                data-hover="row_s{{$student->id}}" style="background:{{$date->color_rgba}};" class="pointer bb{{$date->id}}"
                contenteditable="true" id="bb{{$bonuse->id}}" data-comment="{{$bonuse->comment}}" data-toggle="bb-edit"
                data-id="{{$bonuse->id}}" data-student="{{$bonuse->id_student}}">
                @if($bonuse->value == 1)
                +
                @else
                {{$bonuse->value}}
                @endif

              </div></td>
              @endif

              @endforeach
              @if($has == 0)
              <td><div data-hover="row_s{{$student->id}}" style="background:{{$date->color_rgba}};" contenteditable="true"><span class="red-cyr"></span></div></td>
              @endif

              @endforeach



            </tr>
        @endforeach
    </tbody>

  </table>
  </div>
</div>
