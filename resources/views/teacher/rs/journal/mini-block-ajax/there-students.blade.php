<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;
$there_students = CalculateController::studentThereToday($rs->id);
?>

<p>Присутствующие на паре</p>
<ol>
@foreach($there_students as $t_student)
<li class="stud{{$t_student}} stud">
  {{ CalculateController::getFIO($t_student) }}
</li>
@endforeach
</ol>
