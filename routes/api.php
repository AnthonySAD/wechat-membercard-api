<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'WechatController@login');
Route::post('/user/update', 'WechatController@userInfoUpdate');

Route::get('/card', 'CardController@getMyCard');
Route::get('/card/type', 'CardController@getCardType');

Route::post('/card', 'CardController@addCard');
Route::post('/card/change', 'CardController@changeCard');
Route::put('/card', 'CardController@changeCard');
Route::delete('/card', 'CardController@deleteCard');


Route::post('/card/click', 'CardController@clickCard');
Route::post('/card/share', 'CardController@shareCard');
Route::get('/card/share', 'CardController@getShareCard');

Route::post('/card/global', 'CardController@shareCardGlobal');
Route::get('/card/global', 'CardController@getGlobalCard');
