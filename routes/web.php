<?php

/*
Route::group(['middleware' => ['guest']], function () {

});



Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function() {
});
*/
Route::get('/', function () {
  if (Auth::check())
  {
    return redirect('/home');
  }
  return view('welcome');
});

Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
	Artisan::call('route:clear');
    return "Кэш очищен.";
})->name('clear');

Auth::routes();

Route::get('/reg', function() { return View::make('once.register_institution'); })->name('reg-inst');
Route::post('/reg', 'InstitutionController@create');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/home', 'HomeController@index')->name('home');


    Route::group(['middleware' => ['admin']], function () {
        Route::get('/institution-parameters', 'InstitutionController@index')->name('inst-param');

    });

    Route::group(['middleware' => ['student'], 'prefix' => 'student'], function () {
        Route::get('/', 'Student\AccountController@index')->name('student');
        Route::get('/discipline/{id}', 'Student\AccountController@indexDiscipline')->name('discipline');

        Route::get('/disdd/{id}', 'Student\AccountController@indexDisciplineDD')->name('disciplinedd');

        Route::get('/update-user-data', 'Teacher\AccountController@updateUserData')->name('update.userdata.stud');
        Route::post('/update-user-data', 'Teacher\AccountController@updateUserDataRequest');
    });

    Route::group(['middleware' => ['teacher'], 'prefix' => 'teacher'], function () {

        Route::get('/faq', 'Teacher\AccountController@faq')->name('faq');
        Route::get('/top/{id}', 'Teacher\RSController@top')->name('top');
        Route::get('/classroom', 'Teacher\AccountController@classroom')->name('classroom');
        Route::get('/common-top', 'Teacher\AccountController@commonTop')->name('common.top');

        Route::get('/gen-pass', 'Teacher\AccountController@genPass')->name('gen.pass');
        Route::post('/gen-pass', 'Teacher\AccountController@genPassRequest');

        Route::get('/', 'Teacher\AccountController@index')->name('teacher');
        Route::get('/journal/{id}', 'Teacher\RSController@index')->name('journal');
        Route::get('/task-option/{id}', 'Teacher\RSController@taskOption')->name('task.option');

        Route::get('/update-user-data', 'Teacher\AccountController@updateUserData')->name('update.userdata');
        Route::post('/update-user-data', 'Teacher\AccountController@updateUserDataRequest');


        Route::get('/create-rs', 'Teacher\RSController@create')->name('rs.create');
        Route::post('/create-rs', 'Teacher\RSController@save');

        Route::get('/edit-rs/{id}', 'Teacher\RSController@editIndex')->name('rs.edit');
        Route::post('/edit-rs/{id}', 'Teacher\RSController@editRS');

        Route::get('/edit-five-rs/{id}', 'Teacher\RSFiveController@editIndex')->name('rs.editfive');
        Route::post('/edit-five-rs/{id}', 'Teacher\RSFiveController@editRS');


        Route::get('/journal-five/{id}', 'Teacher\RSFiveController@index')->name('journal.five');
        Route::get('/create-rs-five', 'Teacher\RSFiveController@create')->name('rs.create.five');
        Route::post('/create-rs-five', 'Teacher\RSFiveController@save');

        //ajax

        Route::get('/paper/{id}', 'AjaxController@getPaper');
        Route::get('/bonuse-table/{id}', 'AjaxController@getBonuseTable');
        Route::get('/task-options/{id}', 'AjaxController@getTaskOption');
        Route::get('/lesson-table/{id}', 'AjaxController@getLessonTable');
        Route::get('/progress-table/{id}', 'AjaxController@getProgressTable');
        Route::get('/task-table/{id}', 'AjaxController@getTasksTable');

        Route::get('/tests/{id}', 'AjaxController@getTest');
        Route::get('/main-tests/{id}', 'AjaxController@getMainTest');

        Route::get('/values-ajax/{id}', 'AjaxController@getValuesAjax');
        Route::get('/themes-ajax/{id}', 'AjaxController@getThemesAjax');
        Route::get('/there-students-ajax/{id}', 'AjaxController@getThereStudentsAjax');
        Route::get('/select-students-ajax/{id}', 'AjaxController@getSelectStudentsAjax');
        Route::get('/select-students-ajax/{id}', 'AjaxController@getSelectStudentsAjax');

        Route::get('/sub-1-students-ajax/{id}', 'AjaxController@getSub1StudentsAjax');
        Route::get('/sub-2-students-ajax/{id}', 'AjaxController@getSub2StudentsAjax');

        Route::get('/att-5-students-ajax/{id}', 'AjaxController@getAtt5StudentsAjax');
        Route::get('/att-4-students-ajax/{id}', 'AjaxController@getAtt4StudentsAjax');
        Route::get('/att-3-students-ajax/{id}', 'AjaxController@getAtt3StudentsAjax');
        Route::get('/att-2-students-ajax/{id}', 'AjaxController@getAtt2StudentsAjax');


    });

    Route::group(['middleware' => ['personal'], 'prefix' => 'personal'], function () {

      Route::get('/', 'Personal\AccountController@index')->name('personal');
    });

    Route::get('/import_ktp', 'ImportController@getImportKTP')->name('import_ktp');
    Route::post('/import_parse_ktp', 'ImportController@parseImportKTP')->name('import_parse_ktp');
    Route::post('/import_process_ktp', 'ImportController@processImportKTP')->name('import_process_ktp');

    Route::get('/import_user', 'ImportUserController@getImportUser')->name('import_user');
    Route::post('/import_parse_user', 'ImportUserController@parseImportUser')->name('import_parse_user');
    Route::post('/import_process_user', 'ImportUserController@processImportUser')->name('import_process_user');

});
