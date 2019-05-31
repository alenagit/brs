<?
use \App\Http\Controllers\Student\CalculateController;
$dates = CalculateController::getDatesHookClassroom(Auth::user()->id);
$all_lessson_info = CalculateController::getHookInfo($rss,$students,$dates);

?>
<div class="tabs-hooks">


<b-tabs pills>

@foreach($dates as $date)
  <b-tab title="{{$date}}">
<div class="hooks-room">
  <p>
    В данных таблицах находятся только прогульщики и опоздавшие.
  </p>
  <div class="flex-table one-table">
<table class="table left-table  table-bordered" style="overflow: auto;display: block;">
  <thead>
    <tr>
      <th rowspan="2"><div>№</div></th>

      @foreach($rss as $rs)
      @if(isset($all_lessson_info[$date][$rs->id]) && $all_lessson_info[$date][$rs->id] != [])

      <th colspan="{{count($all_lessson_info[$date][$rs->id])}}"><div>

        {{$rs->name}}<br />{{ CalculateController::getFIO($rs->id_teacher) }}

      </div></th>
      @endif

      @endforeach
    </tr>

    <tr>

    @foreach($rss as $rs)

    @foreach($all_lessson_info[$date][$rs->id] as $id_les => $lesson)
    <th><div>

      @if($lesson['info']['type'] == -1 || $lesson['info']['type'] == "")
      Лекция
      @endif

      @if(isset($lesson['info']['type']) && $lesson['info']['type'] != -1 && $lesson['info']['type'] != "")
      {{CalculateController::getNameRSTASK($lesson['info']['type'])}}
      @endif

      @if($lesson['info']['subgroup'] > 0)
      ({{$lesson['info']['subgroup']}} п/гр)
      @endif
      </div></th>
    @endforeach

    @endforeach
    </tr>

  </thead>

  <tbody>


  @for($r = 0; $r < $all_lessson_info[$date]['max']; $r++)
  <tr>
<td><div>{{$r+1}}</div></td>


@foreach($rss as $rs)

@foreach($all_lessson_info[$date][$rs->id] as $ids_les => $stud_lesson)

@if(isset($stud_lesson['students'][$r]))

<?
$row = $stud_lesson['students'][$r];
$row_end = $row;
$str = strpos($row, "/");
$row = substr($row, 0, $str);
$len = strlen($row_end);
$b = "";
if(($str - $len + 1) != 0)
{
  $b = substr($row_end, ($str - $len + 1));
}
?>

<td>
<div
@if(($b != "") && ((double)$b < 1) && ((double)$b != 0))
class="opz"
@endif
>
  {{ CalculateController::getFIO($row) }}

@if($b != "" && (double)$b < 1 && (double)$b != 0)
  (Опоздал на {{(1 - (double)$b) * 90}} мин)
@endif
</div></td>

@else
<td><div></div></td>
@endif

@endforeach

@endforeach



  </tr>

  @endfor



  </tbody>
</table>
</div>
</div>
</b-tab>
@endforeach
</b-tabs>
</div>
