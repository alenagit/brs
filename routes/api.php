<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('/axios-group-create', 'InstitutionController@createGroup');
Route::post('/axios-specialty-create', 'InstitutionController@createSpecialty');
Route::post('/axios-discipline-create', 'InstitutionController@createDiscipline');

Route::post('/axios-fresh-after-group', 'InstitutionController@freshAfterGroup');
Route::get('/chart-data', 'Teacher\RSController@chartData')->name('rs.chartdata');
Route::post('/del-rs', 'Teacher\RSController@deleteRs');
Route::post('/del-ktp', 'Teacher\RSController@deleteKTP');



Route::post('/add-comment', 'Teacher\RSController@addComment')->name('comment.add');
Route::post('/save-lesson', 'Teacher\RSController@saveLesson')->name('lesson.save');

Route::post('/del-lesson', 'Teacher\RSController@delLesson');
Route::post('/add-lesson', 'Teacher\RSController@addLesson');
Route::post('/add-more-lesson', 'Teacher\RSController@addMoreLesson');

Route::post('/save-mem', 'Student\CalculateController@saveMem');

Route::post('/del-stud', 'Teacher\RSController@delStudent');

Route::post('/save-date', 'Teacher\RSController@saveDate')->name('date.save');
Route::post('/save-color-date', 'Teacher\RSController@saveColorDate');
Route::post('/save-color-work', 'Teacher\RSController@saveColorWork');
Route::post('/save-date-info', 'Teacher\RSController@saveDateInfo')->name('dateinfo.save');

Route::post('/save-task-info', 'Teacher\RSController@saveTaskInfo')->name('taskinfo.save');
Route::post('/add-comment-task', 'Teacher\RSController@addCommentTask')->name('task.comment.add');
Route::post('/save-task', 'Teacher\RSController@saveTask')->name('task.save');
Route::post('/save-test', 'Teacher\RSController@saveTest')->name('test.save');
Route::post('/save-main-test', 'Teacher\RSController@saveMainTest')->name('maintest.save');
Route::post('/save-total-question', 'Teacher\RSController@saveQuestion')->name('question.save');

Route::post('/edit-ktp', 'Teacher\RSController@editKTP');

Route::post('/isp', 'Teacher\AccountController@isp');

Route::post('/get-rand', 'Teacher\BonuseController@getRand');
Route::post('/right', 'Teacher\BonuseController@right');
Route::post('/save-value', 'Teacher\BonuseController@saveValue');
Route::post('/del-value', 'Teacher\BonuseController@delValue');
Route::post('/save-theme', 'Teacher\BonuseController@saveTheme');
Route::post('/update-bb', 'Teacher\BonuseController@updateBB');
Route::post('/add-column-bb', 'Teacher\BonuseController@addColumnBB');
Route::post('/del-column-bb', 'Teacher\BonuseController@delColumnBB');

Route::post('/add-comment-bb', 'Teacher\BonuseController@addCommentBB');
Route::post('/add-info-bb', 'Teacher\BonuseController@addInfoBB');
Route::post('/add-reminder', 'Teacher\AccountController@addReminder');

Route::post('/seen-rem', 'Teacher\AccountController@seenReminder');
Route::post('/done-rem', 'Teacher\AccountController@doneReminder');
