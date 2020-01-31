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

Route::get('/threads', 'ThreadController@index')->name('threads');

Route::get('/threads/create', 'ThreadController@create');

Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('thread');

Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->middleware('auth');

Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->middleware('auth');

Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');

Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');

Route::post('/threads', 'ThreadController@store')->middleware('confirm_email');

Route::get('/threads/{channel}', 'ThreadController@index');

Route::post('/replies/{reply}/favorites', 'FavoriteController@store');

Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy');

Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');

Route::get('/profiles/{user}/notifications', 'UserNotifications@index');

Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotifications@destroy');

Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('delete_reply');

Route::patch('/replies/{reply}', 'ReplyController@update');

Route::get('register/confirm', 'Api\RegisterConfirmationController@index')->name('register.confirm');

Route::get('api/users', 'Api\UsersController@index');

Route::post('/api/users/{user}/avatar', 'Api\AvatarsController@store')->name('avatar');