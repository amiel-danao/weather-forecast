<?php

use App\Http\Controllers\CSVController;
use App\Models\Weather;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
	$threeYearsAgo = Carbon::now();//->subYears(3);
    $sameDateToday = Carbon::now();//->setYear($threeYearsAgo->year);

	$getPastDate = $threeYearsAgo->format('l, F j, Y h:i:s A');

	$formattedDate = $sameDateToday->format('Y-m-d');
	$explodedDate = explode('-', $formattedDate);

	$getDataForToday = Weather::where('year', intval($explodedDate[0]))->where('month', intval($explodedDate[1]))->where('day', intval($explodedDate[2]))->first();

	$getSevenDaysWeatherForecast = Weather::where('year', intval($explodedDate[0]))->where('month', intval($explodedDate[1]))->whereBetween('day', [intval($explodedDate[2]), intval($explodedDate[2]) + 6])->get();

    return view('auth.login', compact('getPastDate', 'getDataForToday', 'getSevenDaysWeatherForecast'));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::get('/subscribe', function () {
	return view('auth.subscribe');
})->name('subscribe');

Route::group(['middleware' => 'auth'], function () {
		Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
		Route::get('maps', ['as' => 'pages.maps', 'uses' => 'App\Http\Controllers\PageController@maps']);
		Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'App\Http\Controllers\PageController@notifications']);
		Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'App\Http\Controllers\PageController@rtl']);
		Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
		Route::get('typography', ['as' => 'pages.typography', 'uses' => 'App\Http\Controllers\PageController@typography']);
		Route::get('upgrade', ['as' => 'pages.upgrade', 'uses' => 'App\Http\Controllers\PageController@upgrade']);
		Route::get('accounts', ['as' => 'pages.accounts', 'uses' => 'App\Http\Controllers\PageController@accounts']);
		Route::get('weather-updates', ['as' => 'pages.weather-updates', 'uses' => 'App\Http\Controllers\PageController@weatherUpdates']);
		
		Route::post('/csv/upload', [CSVController::class, 'upload'])->name('csv.upload');
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

