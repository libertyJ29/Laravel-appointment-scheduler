<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/



/**
 * Home
 */

Route::get('dentist/home', [
    'as' => 'home',
    'uses' => 'PagesController@home'
]);

Route::get('dentist/index', [
    'as' => 'index',
    'uses' => 'PagesController@index'
]);







/**
 * Authentication
 */
Route::get('dentist/auth/login', [
    'as' => 'get.login',
    'uses' => 'Auth\AuthController@getLogin'
]);

Route::post('dentist/auth/login', [
    'as' => 'post.login',
    'uses' => 'Auth\AuthController@postLogin'
]);

Route::get('dentist/loginsuccess', [
    'as' => 'login.success',
    'uses' => 'PagesController@loginsuccess'
]);

Route::get('dentist/auth/logout', [
    'as' => 'get.logout',
    'uses' => 'Auth\AuthController@getLogout'
]);

Route::get('dentist/auth/logout/success', [
    'as' => 'success.logout',
    'uses' => 'Auth\AuthController@successLogout'
]);


//Registration routes
Route::get('dentist/auth/register', [
    'as' => 'get.register',
    'uses' => 'Auth\AuthController@getRegister'
]);

Route::post('dentist/auth/register', [
    'as' => 'post.register',
    'uses' => 'Auth\AuthController@postRegister'
]);







/**
 * Appointments
 */
Route::get('dentist/appointments/getTreatmentPrice{treatment_type}', [
    'as' => 'appointments.getTreatmentArray',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@getTreatmentPrice'
]);

Route::get('dentist/appointments/paymentstatus{appointment_id}', [
    'as' => 'appointments.paymentstatus',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@payment_status_flag'
]);

Route::get('dentist/appointments/getappointmentsfordate{date}', [
    'as' => 'get.appointmentsfordate',
    'middleware' => 'auth',
    'uses' => 'PagesController@getAppointmentsForDate'
]);

Route::get('dentist/appointments/index{appointment_id},{patient_id},{type},{searchdate},{loadfrom1}', [
    'as' => 'appointments',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@index'
]);

Route::get('dentist/appointments/checkin{id},{appointmentid},{type}', [
    'as' => 'appointments.checkin',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@checkin'
]);

Route::get('dentist/appointments/create{id},{newtime},{newdate}', [
    'as' => 'appointments.create',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@create'
]);

Route::post('dentist/appointments/store', [
    'as' => 'appointments.store',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@store'
]);

Route::get('dentist/appointments/all', [
    'as' => 'appointments.all',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@all'
]);

Route::get('dentist/appointments/edit{id},{newtime},{patient_id},{newdate}', [
    'as' => 'appointments.edit',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@edit'
]);

Route::patch('dentist/appointments/update{id}', [
    'as' => 'appointments.update',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@update'
]);

Route::delete('dentist/appointments/destroy{id}', [
    'as' => 'appointments.destroy',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@destroy'
]);

Route::get('dentist/appointments/showrecord{id},{appointment_id},{type}', [
    'as' => 'appointments.showrecord',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@showrecord'
]);

Route::get('dentist/appointments/show{id},{newtime},{appointment_id},{type}', [
    'as' => 'appointments.show',
    'middleware' => 'auth',
    'uses' => 'AppointmentsController@show'
]);







/**
 * Dentists
 */
Route::get('dentist/dentists/index', [
    'as' => 'dentists.index',
    'middleware' => 'auth',
    'uses' => 'DentistsController@index'
]);

Route::get('dentist/dentists/create', [
    'as' => 'dentists.create',
    'middleware' => 'auth',
    'uses' => 'DentistsController@create'
]);

Route::post('dentist/dentists/store', [
    'as' => 'dentists.store',
    'middleware' => 'auth',
    'uses' => 'DentistsController@store'
]);

Route::get('dentist/dentists/created', [
    'as' => 'dentists.created',
    'middleware' => 'auth',
    'uses' => 'DentistsController@store'
]);

Route::delete('dentist/dentists/destroy{id}', [
    'as' => 'dentists.destroy',
    'middleware' => 'auth',
    'uses' => 'DentistsController@destroy'
]);

Route::get('dentist/dentists/edit{id}', [
    'as' => 'dentists.edit',
    'middleware' => 'auth',
    'uses' => 'DentistsController@edit'
]);

Route::get('dentist/dentists/edited', [
    'as' => 'dentists.edited',
    'middleware' => 'auth',
    'uses' => 'DentistsController@edit'
]);

Route::patch('dentist/dentists/update{id}', [
    'as' => 'dentists.update',
    'middleware' => 'auth',
    'uses' => 'DentistsController@update'
]);

Route::get('dentist/dentists/treatments', [
    'as' => 'dentists.treatments',
    'middleware' => 'auth',
    'uses' => 'DentistsController@treatments'
]);

Route::get('dentist/dentists/inactive', [
    'as' => 'dentists.inactive',
    'middleware' => 'auth',
    'uses' => 'DentistsController@inactive'
]);

Route::get('dentist/dentists/getalltreatments', [
    'as' => 'dentists.getalltreatments',
    'middleware' => 'auth',
    'uses' => 'DentistsController@getAllTreatments'
]);

Route::get('dentist/dentists/addnewtreatment{tname}', [
    'as' => 'dentists.addnewtreatment',
    'middleware' => 'auth',
    'uses' => 'DentistsController@addnewtreatment'
]);

Route::get('dentist/dentists/updatetreatments{tid},{newprice}', [
    'as' => 'dentists.updatetreatments',
    'middleware' => 'auth',
    'uses' => 'DentistsController@updatetreatments'
]);

Route::get('dentist/dentists/deletethistreatment{tid}', [
    'as' => 'dentists.deleteatreatment',
    'middleware' => 'auth',
    'uses' => 'DentistsController@deletethistreatment'
]);







/**
 * Patients
 */
Route::get('dentist/patients/index', [
    'as' => 'patients',
    'middleware' => 'auth',
    'uses' => 'PatientsController@index'
]);

Route::post('dentist/patients/show{appointment_id},{type}', [
    'as' => 'patients.show',
    'middleware' => 'auth',
    'uses' => 'PatientsController@show'
]);

Route::get('dentist/patients/showrecord{id},{appointment_id},{type}', [
    'as' => 'patients.showrecord',
    'middleware' => 'auth',
    'uses' => 'PatientsController@showrecord'
]);

Route::get('dentist/patients/search{appointment_id},{type}', [
    'as' => 'patients.search',
    'middleware' => 'auth',
    'uses' => 'PatientsController@search'
]);

Route::get('dentist/patients/create', [
    'as' => 'patients.create',
    'middleware' => 'auth',
    'uses' => 'PatientsController@create'
]);

Route::post('dentist/patients/store', [
    'as' => 'patients.store',
    'middleware' => 'auth',
    'uses' => 'PatientsController@store'
]);

Route::get('dentist/patients/created', [
    'as' => 'patients.created',
    'middleware' => 'auth',
    'uses' => 'PatientsController@store'
]);

Route::get('dentist/patients/edit{id}', [
    'as' => 'patients.edit',
    'middleware' => 'auth',
    'uses' => 'PatientsController@edit'
]);

Route::get('dentist/patients/edited', [
    'as' => 'patients.edited',
    'middleware' => 'auth',
    'uses' => 'PatientsController@edit'
]);

Route::delete('dentist/patients/destroy{id}', [
    'as' => 'patients.destroy',
    'middleware' => 'auth',
    'uses' => 'PatientsController@destroy'
]);

Route::patch('dentist/patients/update{id}', [
    'as' => 'patients.update',
    'middleware' => 'auth',
    'uses' => 'PatientsController@update'
]);

Route::get('dentist/patients/all', [
    'as' => 'patients.all',
    'middleware' => 'auth',
    'uses' => 'PatientsController@all'
]);


