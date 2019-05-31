<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;
$att_four = CalculateController::studentAtt4($rs->id);
?>

<p>Аттестованы на "4"</p>
<ol>
@foreach($att_four as $at_four)
<li class="stud{{$at_four}} stud">
  {{ CalculateController::getFIO($at_four) }}
</li>
@endforeach
</ol>
