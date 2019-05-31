<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;
$two_there_students = CalculateController::studentSubgroup2($rs->id);
?>

<p>Подгруппа №2</p>
<ol>
@foreach($two_there_students as $two)
<li class="stud{{$two}} stud">
  {{ CalculateController::getFIO($two) }}
</li>
@endforeach
</ol>
