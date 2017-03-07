<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('guests', 'GuestsController');

Route::group(['prefix' => 'guests'], function () {
	Route::get('/{id}/destroy', 'GuestsController@destroy');
});

Route::resource('rooms', 'RoomsController');

Route::group(['prefix' => 'rooms'], function () {
	Route::get('/{id}/destroy', 'RoomsController@destroy');
});

Route::resource('expense-types', 'ExpenseTypesController');

Route::group(['prefix' => 'expense-types'], function () {
	Route::get('/{id}/destroy', 'ExpenseTypesController@destroy');
});

Route::resource('income-types', 'IncomeTypesController');

Route::group(['prefix' => 'income-types'], function () {
	Route::get('/{id}/destroy', 'IncomeTypesController@destroy');
});

Route::resource('incomes', 'IncomesController');

Route::group(['prefix' => 'incomes'], function () {
	Route::get('/{id}/destroy', 'IncomesController@destroy');
});

Route::resource('expenses', 'ExpensesController');

Route::group(['prefix' => 'expenses'], function () {
	Route::get('/{id}/destroy', 'ExpensesController@destroy');
});

//Route::resource('users', 'UsersController');

Route::group(['prefix' => 'users'], function () {
	Route::match(array('GET','POST'), '/profile', 'UsersController@profile');
});

Route::resource('settings', 'SettingsController');

Route::resource('rents', 'RentsController');

Route::group(['prefix' => 'rents'], function () {
	Route::match(array('GET','POST'), '/destroy', 'RentsController@destroy');
});