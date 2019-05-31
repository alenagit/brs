<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;
$one_there_students = CalculateController::studentSubgroup1($rs->id);
?>

<p>Подгруппа №1</p>
<ol>

@foreach($one_there_students as $one)
<li class="stud{{$one}} stud">
  {{ CalculateController::getFIO($one) }}
</li>
@endforeach
</ol>
