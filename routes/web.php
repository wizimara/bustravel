<?php

Route::get('test-backend','TestController@index')->name('bustravel.testdefault');
Route::get('test-frontend','TestController@front')->name('bustravel.testfront');
Route::get('stations','StationsController@index')->name('bustravel.stations');
Route::get('stations/create','StationsController@show')->name('bustravel.stations.create');
Route::post('stations/create','StationsController@store')->name('bustravel.stations.store');
Route::get('stations/view/{id}','StationsController@show')->name('bustravel.stations.edit');
Route::post('stations/view/{id}','StationsController@store')->name('bustravel.stations.update');
Route::get('stations/delete/{id}','StationsController@destroy')->name('bustravel.stations.delete');
Route::get('general_settings','SettingsController@general')->name('bustravel.general_settings');
Route::get('company_settings','SettingsController@company')->name('bustravel.company_settings');
//operators routes
Route::get('operators','OperatorsController@index')->name('bustravel.operators');
Route::get('operators/create','OperatorsController@create')->name('bustravel.operators.create');
Route::post('operators','OperatorsController@store')->name('bustravel.operators.store');
Route::get('operators/{id}/edit','OperatorsController@edit')->name('bustravel.operators.edit');
Route::any('operators/{id}/update','OperatorsController@update')->name('bustravel.operators.update');
Route::any('operators/{id}/delete','OperatorsController@delete')->name('bustravel.operators.delete');
//Buses
Route::get('buses','BusesController@index')->name('bustravel.buses');
Route::get('buses/create','BusesController@create')->name('bustravel.buses.create');
Route::post('buses','BusesController@store')->name('bustravel.buses.store');
Route::get('buses/{id}/edit','BusesController@edit')->name('bustravel.buses.edit');
Route::any('buses/{id}/update','BusesController@update')->name('bustravel.buses.update');
Route::any('buses/{id}/delete','BusesController@delete')->name('bustravel.buses.delete');
//Routes
Route::get('routes','RouteController@index')->name('bustravel.routes');
Route::get('routes/create','RouteController@create')->name('bustravel.routes.create');
Route::post('routes','RouteController@store')->name('bustravel.routes.store');
Route::get('routes/{id}/edit','RouteController@edit')->name('bustravel.routes.edit');
Route::any('routes/{id}/update','RouteController@update')->name('bustravel.routes.update');
Route::any('routes/{id}/delete','RouteController@delete')->name('bustravel.routes.delete');


Route::get('drivers','DriversController@index')->name('bustravel.drivers');
Route::get('bookings','BookingsController@index')->name('bustravel.bookings');
Route::get('report_sales','ReportsController@sales')->name('bustravel.reports.sales');
Route::get('report_routes','ReportsController@routes')->name('bustravel.reports.profitroute');
Route::get('report_traffic','ReportsController@traffic')->name('bustravel.reports.traffic');
Route::get('report_booking','ReportsController@booking')->name('bustravel.reports.bookings');
Route::get('report_locations','ReportsController@locations')->name('bustravel.reports.locations');
