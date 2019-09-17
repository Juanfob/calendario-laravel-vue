<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([ 'prefix' => 'events', 'middleware' => ['auth']], function () {
	Route::get('/{year}', 'CalendarEventController@eventsCountForYear');
	Route::get('/{month}/{year}', 'CalendarEventController@eventsForMonth');
	Route::post('/', 'CalendarEventController@store');
});
