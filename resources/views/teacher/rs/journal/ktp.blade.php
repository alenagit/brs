<?
use \App\Http\Controllers\Teacher\RSController;
use \App\Http\Controllers\Student\CalculateController;
$date = RSController::getDates($rs->id);
$dates_ktps = CalculateController::getDateWhereKtp($rs->id);
$hour = 0;
$count_cont = 0;
$i = 0;
?>
<b-tab title="КТП" style="display:none;" id="ktp">

  <h3>КТП</h3>
<div >
  <table style="overflow: auto;" class="ktp-tbl">
  <thead>
    <tr>

      <th>№</th>
      <th class="date-ktp">Дата</th>
      <th>Пара</th>
      <th>Тип</th>

      <th class="theme-ktp">Тема</th>



    </tr>
  </thead>

  <tbody>

    @foreach($dates_ktps as $para => $ktp)
    <?
    $count_ktp = CalculateController::getCountPneTypeKTP($rs->id, $ktp->id_ktp, $ktp->date);
    $ktp_info = CalculateController::getInfoKtp($ktp->id_ktp);
    $hour += $count_ktp*2;
    $i++;
    ?>

    @if(isset($ktp_info->type))
    <tr>

      <td>{{$i}}</td>
      <td class="date-ktp">{{$ktp->date}} @if($count_ktp > 1) ({{$count_ktp}}) @endif</td>

      <td>{{$count_ktp*2}} / {{$hour}}</td>

      <td>{{$ktp_info->type}}</td>

      <td style="text-align: left;" class="pointer theme-ktp" contenteditable="true" id="{{$ktp_info->id}}" data-toggle="save-ktp">{{$ktp_info->name}}</td>
    </tr>
    @endif






    @endforeach

  </tbody>
</table>
</div>
</b-tab>
