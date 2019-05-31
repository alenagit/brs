<?
use \App\Http\Controllers\Teacher\RSController;
$date = RSController::getDates($rs->id);

?>
<b-tab title="КТП" style="display:none;" id="ktp">

  <h3>КТП</h3>
<div class="flex-table twotable one-table">
  <table style="overflow: auto;" class="table left-table  table-bordered">
  <thead>
    <tr>

      <th><div>Дата</div></th>
      <th><div>Тема</div></th>
      <th><div>Пара</div></th>
      <th><div>Тип</div></th>
    </tr>
  </thead>

  <tbody>
    @foreach($rs->ktp as $para => $ktp)
    <tr>

      <td><div>
        @if(isset($date[$para]))
        {{$date[$para]}}
        @endif
      </div></td>
      <td><div class="pointer" contenteditable="true" id="{{$ktp->id}}" data-toggle="save-ktp">{{$ktp->name}}</div></td>
      <td><div>2 / {{($para * 2 )+2}}</div></td>
      <td><div>{{$ktp->type}}</div></td>
    </tr>
    @endforeach

  </tbody>
</table>
</div>
</b-tab>
