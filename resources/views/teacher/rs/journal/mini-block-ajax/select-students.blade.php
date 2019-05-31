<?
use \App\Http\Controllers\Student\CalculateController;
use \App\User;
$there_students = CalculateController::studentThereToday($rs->id);
?>

<select class="form-control width-auto selest-stud" id="list-select" multiple>

  <option selected disabled>Студенты</option>
  @foreach($there_students as $t_student)
  <option value="{{$t_student}}">{{ User::find($t_student)->name}}</option>
  @endforeach

</select>
