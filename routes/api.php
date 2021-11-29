<?php

Route::post('login', 'Auth\LoginController@store');
Route::post('register', 'Auth\RegisterController@register');
Route::post('forgotpassword', 'Auth\ForgetPasswordController@sendMail');
Route::get('forgotpassword/{email}', 'Auth\ForgetPasswordController@show')->name('forgotpassword');
Route::post('forgotpassword/store', 'Auth\ForgetPasswordController@store')->name('forgotpassword.store');
Route::get('emailvarification/{email}', 'Auth\RegisterController@emailVarifiy')->name('emailVarification');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('getUser', 'UserController@getUser');
    Route::get('detail', 'UserController@detail');
    Route::post('changeDetail', 'UserController@changeDetail');
    Route::post('changePassword', 'UserController@changePassword');
    Route::post('deleteAccount', 'UserController@deleteAccount');
    Route::get('getAllUser', 'UserController@userIndex');
    Route::post('user/delete', 'UserController@userDelete');
    Route::post('user/activate', 'UserController@userActivate');
    Route::post('user/verifiy', 'UserController@userVerifiy');
    Route::get('user/{id}', 'UserController@userDetail');

    Route::get('fizzy', 'FizzyController@index');
    Route::get('fizzy/type', 'FizzyController@type');
    Route::get('fizzy/{token}', 'FizzyController@show');
    Route::post('fizzy/store', 'FizzyController@store');
    Route::post('fizzy/delete', 'FizzyController@delete');
    Route::post('fizzy/edit', 'FizzyController@edit');
    Route::post('fizzy/status/edit', 'FizzyController@statusEdit');
    Route::post('fizzy/img/delete', 'FizzyController@imgDelete');

    Route::get('discover', 'FizzyController@discover');
    Route::get('discover/{token}', 'FizzyController@discoverShow');

    Route::get('report', 'ReportController@index');
    Route::get('report/{id}', 'ReportController@show');
    Route::post('report/delete', 'ReportController@delete');

    Route::post('fizzy/question/store', 'QuestionController@store');
    Route::post('fizzy/question/update', 'QuestionController@update');
    Route::post('fizzy/question/show', 'QuestionController@show');
    Route::post('fizzy/question/delete/{id}', 'QuestionController@destroy');

    Route::get('dashboard', 'DashboardController@index');
    Route::get('dashboard/fizzy', 'DashboardController@fizzy');
    Route::get('dashboard/report', 'DashboardController@report');

    Route::get('dashboard/global', 'AdminDashboardController@index');
    Route::get('dashboard/popularFizzies', 'FizzyController@popularFizzies');
    
    Route::post('theme/store', 'ThemeController@store');
    Route::post('theme/delete/{id}', 'ThemeController@delete');
});

Route::get('fizzy/test/{key}', 'Test\GameController@getFizzyDetail');
Route::get('theme/{page?}', 'ThemeController@getTheme');

Route::get('/generatePin/{fizzyKey}', 'GameController@generatePin');
Route::get('/checkPin/send/{pin}', 'GameController@checkPin');
Route::post('setName/send', 'GameController@setName');
Route::post('/startGame', 'GameController@startGame');
Route::get('/getParticipate/{gameId}', 'GameController@get');
Route::post('/send/question', 'GameController@sendQuestion');
Route::post('/send/answer', 'GameController@sendAnswer');
Route::post('/allResult', 'GameController@allResult');
Route::post('/timeout', 'GameController@timeout');
Route::post('/changeStatus', 'GameController@changeStatus');
Route::post('/getRank', 'GameController@getRank');
