<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;
$att_five = CalculateController::studentAtt5($rs->id);
?>

<p>Аттестованы на "5"</p>
<ol>
@foreach($att_five as $at_five)
<li class="stud{{$at_five}} stud">
  {{ CalculateController::getFIO($at_five) }}
</li>
@endforeach
</ol>
