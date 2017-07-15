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
Route::get('account/{account}', 'BankController@show');
Route::post('account', 'BankController@create');
Route::post('account/deposit', 'BankController@deposit');
Route::post('account/withdrawal', 'BankController@withdraw');
Route::delete('account', 'BankController@close');
