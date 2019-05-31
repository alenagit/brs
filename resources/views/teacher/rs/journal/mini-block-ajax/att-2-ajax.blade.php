<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;
$att_two = CalculateController::studentAtt2($rs->id);
?>

<p>Аттестованы на "2"</p>
<ol>
@foreach($att_two as $at_two)
<li class="stud{{$at_two}} stud">
  {{ CalculateController::getFIO($at_two) }}
</li>
@endforeach
</ol>
