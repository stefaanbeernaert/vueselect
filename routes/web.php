<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/test','App\Http\Controllers\usercontroller@index')->name('test');
Route::get('/users','App\Http\Controllers\usercontroller@users')->name('users');
Route::get('/address','App\Http\Controllers\addresscontroller@addresses')->name('address');

