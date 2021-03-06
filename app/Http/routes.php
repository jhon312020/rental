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
	if (isset(\Auth::User()->id)) {
		return \Redirect::to('home');
	} else {
		return \Redirect::to('login');
	}
	//return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('guests', 'GuestsController');

Route::group(['prefix' => 'guests'], function () {
	Route::get('/{id}/destroy', 'GuestsController@destroy');
	Route::post('/get-guest-by-key', 'GuestsController@getGuestByKey');
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
	Route::match(array('GET','POST'), '/change-password', 'UsersController@changePassword');
});

Route::resource('settings', 'SettingsController');

Route::group(['prefix' => 'rents'], function () {
	Route::get('/{id}/destroy', 'RentsController@destroy');
	Route::get('/get-rent-monthly', 'RentsController@rentMonthlyReport');
	Route::get('/get-bill-monthly', 'RentsController@billMonthlyReport');
	Route::get('/{room_id}/rent-edit', 'RentsController@editRentByRoomId');
	Route::get('/{room_id}/room-destroy', 'RentsController@destroyByRoom');
	Route::get('/list-update', 'RentsController@listUpdate');
	Route::get('/settlement', 'RentsController@settlement');
	Route::get('/settled-rent', 'RentsController@settledRents');
});

Route::group(['prefix' => 'reports'], function () {
	Route::get('/rooms', 'ReportsController@rooms');
	Route::get('/rents', 'ReportsController@rentMonthlyReport');
	Route::get('/incomes', 'ReportsController@incomeReport');
	Route::get('/expenses', 'ReportsController@expenseReport');
	Route::get('/electricity', 'ReportsController@electricityBillReport');
	Route::get('/{guest_id}/guest-income', 'ReportsController@guestIncomeReport');
});

Route::group(['prefix' => 'message'], function () {
	Route::match(array('GET','POST'), '/index', 'MessageController@index');
});

Route::resource('rents', 'RentsController');

Route::resource('notes', 'NotesController');

Route::group(['prefix' => 'notes'], function () {
	Route::get('/{id}/destroy', 'NotesController@destroy');
});

Route::group(['prefix' => 'ajax'], function () {
	Route::post('/create-new-bill', 'AjaxController@createNewBill');
	Route::post('/create-new-rent-income', 'AjaxController@createNewRentIncome');
	Route::post('/get-guest-details-for-room', 'AjaxController@getGuestDetailsForRoom');
	Route::post('/update-electricity-bill-by-key', 'AjaxController@updateElectricityBillByKey');
	Route::post('/delete-electricity-bill-by-ids', 'AjaxController@deleteBillsByIds');
	Route::post('/update-room-rent-by-key', 'AjaxController@updateRoomRentByKey');
	Route::post('/delete-rent-incomes-by-ids', 'AjaxController@deleteRentIncomesByIds');
	Route::post('/update-rent', 'AjaxController@updateRent');
	Route::post('/move-to-active-electric-bills', 'AjaxController@moveToActiveElectricBills');
	Route::post('/move-to-active-rents', 'AjaxController@moveToActiveRents');
	Route::post('/add-new-rent-by-room', 'AjaxController@addNewRentByRoom');
	Route::post('/room-report', 'AjaxController@roomReport');
	Route::post('/rent-report', 'AjaxController@rentReport');
	Route::post('/income-report', 'AjaxController@incomeReport');
	Route::post('/income-report-month', 'AjaxController@incomeReportMonth');
	Route::post('/income-report-year', 'AjaxController@incomeReportYear');
	Route::post('/income-report-between-date', 'AjaxController@incomeReportBetweenDate');
	Route::post('/expense-report-month', 'AjaxController@expenseReportMonth');
	Route::post('/expense-report-year', 'AjaxController@expenseReportYear');
	Route::post('/expense-report-between-date', 'AjaxController@expenseReportBetweenDate');
	Route::post('/get-electricity-bill-report-month', 'AjaxController@getElectricityBillReportMonth');
	Route::post('/get-electricity-bill-report-year', 'AjaxController@getElectricityBillReportYear');
	Route::post('/get-electricity-bill-report-between-month', 'AjaxController@getElectricityBillReportBetweenMonth');
	Route::post('/get-guest-details-by-type', 'AjaxController@getGuestDetailsByType');
	Route::post('/get-guest-by-id', 'AjaxController@getGuestById');
	Route::post('/add-new-rent-by-room-and-guest', 'AjaxController@addNewRentByRoomAndGuest');
	Route::post('/get-bill-by-room-no', 'AjaxController@getBillByRoomNo');
	Route::post('/get-rent-by-room-no', 'AjaxController@getRentByRoomNo');
	Route::post('/create-income-type', 'AjaxController@createIncomeType');
	Route::post('/create-expense-type', 'AjaxController@createExpenseType');
	Route::post('/create-new-income', 'AjaxController@createNewIncome');
	Route::post('/get-last-paid-rent', 'AjaxController@getLast5PaidRental');
	Route::post('/remove-rents', 'AjaxController@removeRents');
	Route::get('/get-guests', 'AjaxController@getGuests');
	Route::post('/get-guest-income', 'AjaxController@getGuestIncomeBetweenDate');
	Route::get('/{guest_id}/get-guest-details', 'AjaxController@getGuestDetails');
	Route::post('/update-settlement', 'AjaxController@updateSettlement');
	Route::post('/get-settlement-amount', 'AjaxController@calculateSettlement');
	Route::post('/update-electricity-rent', 'AjaxController@updateElectricityBillToRent');
	Route::post('/get-settled-guest-details-for-room', 'AjaxController@getSettledGuestDetailsForRoom');
	Route::post('/save-old-rent', 'AjaxController@saveOldRent');
});