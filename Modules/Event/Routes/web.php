<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::prefix('event')->group(function() {
//     Route::get('/', 'EventController@index');
// });

Route::middleware(['auth'])->group(function () {
    
    Route::prefix('admin')->group(function () {
        Route::prefix('event')->group(function() {
            Route::get('/', 'EventController@index');
            Route::get('manage/{id}','EventController@getGuests');
            Route::post('import','EventController@import')->name('event.import');
            Route::get('sendMailInvite','EventController@sendMailInvite')->name('event.sendMailInvite');
        });
    });
});