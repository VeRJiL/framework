<?php

use Core\Route\Route;

Route::get('', 'PagesController@index');
Route::get('about', 'PagesController@about');
Route::get('contact', 'ContactController@index');
Route::get('users', 'UserController@index');
Route::post('users/store', 'UserController@store');