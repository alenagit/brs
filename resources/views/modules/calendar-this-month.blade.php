<?php
use Carbon\Carbon;

  $today = Carbon::today();

  //echo '<h1 class="w3-text-teal"><center>' . $today->format('F Y') . '</center></h1>';

  $tempDate = Carbon::createFromDate($today->year, $today->month, 1); //ищет начало текущего месяца



  echo '<table border="1" class = "w3-table w3-boarder w3-striped null-style">
  <thead><tr class="w3-theme">

  <th>Пн</th>
  <th>Вт</th>
  <th>Ср</th>
  <th>Чт</th>
  <th>Пт</th>
  <th>Сб</th>
  <th>Вс</th>
  </tr></thead>';

  $skip = $tempDate->dayOfWeek; //выводит текуший день


  for($i = 0; $i < $skip; $i++)
  {
    $tempDate->subDay(); //уходит к началу месяца
  }
  $tempDate->addDay();

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
          echo '<td style="position:relative;" v-b-tooltip.hover title="',$value,'"><span class="icon-bon"></span><span class="date">';
          $flag = 1;
        }

      }

      if($flag == 0) echo '<td><span class="date">';


      echo $dau;

      echo '</span></td>';

      $tempDate->addDay();
    }
    echo '</tr>';

  }while($tempDate->month == $today->month);

  echo '</table>';



  ?>
