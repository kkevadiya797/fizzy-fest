<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/
// Route::get('/test', function ()
// {
//     return view('test');
// });
// Move other http requests to the Vue App
// -------------------------------------------------

Route::get('/app/{vue?}', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('app');

Route::get('/play', function () {
    return view('app');
})->name('play');

Route::get('/{vue?}', function () {
    return view('appWeb');
})->where('vue', '[\/\w\.-]*')->name('home');

