<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;
$att_three = CalculateController::studentAtt3($rs->id);
?>

<p>Аттестованы на "3"</p>
<ol>
@foreach($att_three as $at_three)
<li class="stud{{$at_three}} stud">
  {{ CalculateController::getFIO($at_three) }}
</li>
@endforeach
</ol>
