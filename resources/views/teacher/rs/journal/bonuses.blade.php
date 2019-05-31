<?
use \App\Http\Controllers\Student\CalculateController;
$mass_dates = CalculateController::getMassDateBonuse($rs->id);
?>

<b-tab title="Бонусные баллы" style="display:none;" id="bonuse">
  <h3>Бонусные баллы</h3>

  <p><input id="name-column-bb" placeholder="Название столбца"><span id="add-column-bb">Добавить столбец</span></p>

<div id="bonuse-table">

</div>
</b-tab>
