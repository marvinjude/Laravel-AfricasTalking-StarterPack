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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*
 * This Is The Route That Gets Hit By Your Cron Provider
 * ie : your-domain/crony or whatever to change it to
 */
Route::get('/crony', 'CronEndPointController@toUsersThatMatchRule');

/*
 * Hit This Route If You Need To Send To All Users Regardless Of Reg Date
 * ie : your-domain/crony/all or whatever to change it to
 */

Route::get('/crony/all', 'CronEndPointController@toAllUsers');

/**
 * This Is The Web Hook URL For Delivery Reports so add This To Your
 * AT Dashboard > Delivery Report i.e your-domain.com/smsdeliveryreport
 */
Route::post('smsdeliveryreport', 'SMSDeliveryReport@index');
