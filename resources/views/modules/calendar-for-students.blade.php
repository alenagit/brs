<?php
use \App\Http\Controllers\CalendarController;

$info = CalendarController::getStudentCalendarInfo($rs->id, $id_student);
$dates = $info['dates'];
$dates_names = $info['dates_names'];

  $month = array(
    'January' => 'Январь',
    'February' => 'Февраль',
    'April' => 'Апрель',
    'March' => 'Март',
    'May' => 'Май',
    'June' => 'Июнь',
    'July' => 'Июль',
    'August' => 'Август',
    'September' => 'Сентябрь',
    'October' => 'Октябрь',
    'November' => 'Ноябрь',
    'December' => 'Декабрь'
  );
use Carbon\Carbon;



$today = Carbon::today();

if($today->month <= 12 && $today->month >= 8)
{
  $endmonth = 12;
}
else {
  $endmonth = 6;
}
//echo '<h1 class="w3-text-teal"><center>' . $today->format('F Y') . '</center></h1>';

$tempDate = Carbon::createFromDate($today->year, $today->month, 1); //ищет начало текущего месяца

if($endmonth == 12)
{
  if($tempDate->month > 9)
  {
    $number_sub = $tempDate->month - 9;
  }
}

if($endmonth == 6)
{
  if($tempDate->month >= 1)
  {
    $number_sub = $tempDate->month - 1;
  }
}

echo '<b-tabs class="tabs-cl">';
$tempDate->subMonth($number_sub);
$first_month = $tempDate->month;
for($j = 1; $j <= 6; $j++) //!!!!!!!!!!!!!!!!!!!!!!тут надо подумать как крутить цикл чтобы не сбивалось при переходе на другой семетр
{

    $nowtime = $tempDate;


    if($today->format('F') == $nowtime->format('F'))
    {
      echo '<b-tab title="',$month[$nowtime->format('F')],'" active>';
    }
    else {
      echo '<b-tab title="',$month[$nowtime->format('F')],'">';
    }


  $nowmonth = $tempDate->month;


echo '<table border="1" class = "table left-table  table-bordered calendar">
<thead><tr class="w3-theme">

<th>Пн</th>
<th>Вт</th>
<th>Ср</th>
<th>Чт</th>
<th>Пт</th>
<th>Сб</th>
<th>Вс</th>
</tr></thead>';


$skip = $tempDate->day;


    for($i = 0; $i < $skip; $i++)
    {
        $tempDate->subDay();
    }

      $tempDate->subDay(($tempDate->dayOfWeek -1));



//loops through month
do
{
  echo '<tr>';
  //loops through each week
  for($i=0; $i < 7; $i++)
  {
    $dau = $tempDate->day;
    $mon = $tempDate->month;
    $flag = 0;

    foreach ($dates as $date => $value)
    {
      if( $dau == substr($date, 0, 2) && $mon == substr($date, -2))
      {
        echo '<td style="position:relative;" data-html="true" data-placement="bottom" data-toggle="popover" data-trigger="hover" data-content="',$dates_names[$date],'<hr />*<strong>Б</strong> - балл, <br />*<strong>п.о.</strong> - правильный ответ" title="Баллы за этот день"><span class="icon-bon">',round($value),'</span><span class="date">';
        $flag = 1;
      }

    }

    if($flag == 0) echo '<td><span class="date">';


    echo $dau;

    echo '</span></td>';

    $tempDate->addDay();
  }
  echo '</tr>';

}while($tempDate->month == $nowmonth);

echo '</table>';
echo  '</b-tab>';
}
echo  '</b-tabs>';
?>
