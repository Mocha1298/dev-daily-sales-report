<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;

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

Route::get('/','App\Http\Controllers\ReportController@home');
Route::get('/unit/borobudur/{start}/{end}','App\Http\Controllers\BorobudurController@index');
Route::get('/unit/prambanan/{start}/{end}','App\Http\Controllers\PrambananController@index');
Route::get('/unit/ratuboko/{start}/{end}','App\Http\Controllers\RatubokoController@index');
Route::get('/unit/tamanmini/{start}/{end}','App\Http\Controllers\TamanminiController@index');
Route::get('/unit/manohara/{start}/{end}','App\Http\Controllers\ManoharaController@index');
Route::get('/unit/teapen/{start}/{end}','App\Http\Controllers\TeapenController@index');

Route::get('/pelataran','App\Http\Controllers\ReportController@pelataran');

Route::get('/clear-cache', function(){
    Artisan::call('clear:cache');
    Artisan::call('clear:view');
    Artisan::call('clear:config');
});

Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom'); 
Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom'); 
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');

Route::middleware('auth')->group(function (){
    Route::get('/admin',function () {
        return view('admin.dashboard');
    });
    // ROUTE VOLUME
        Route::get('/admin/borobudur','App\Http\Controllers\AdminBdrController@index');
        Route::post('/admin/borobudur','App\Http\Controllers\AdminBdrController@new');
        Route::get('/admin/borobudur/edit/{id}','App\Http\Controllers\AdminBdrController@edit');
        Route::post('/admin/borobudur/edit/{id}','App\Http\Controllers\AdminBdrController@post_edit');
        Route::get('/admin/borobudur/delete/{id}','App\Http\Controllers\AdminBdrController@delete');

        Route::get('/admin/prambanan','App\Http\Controllers\AdminPrbController@index');
        Route::post('/admin/prambanan','App\Http\Controllers\AdminPrbController@new');
        Route::get('/admin/prambanan/edit/{id}','App\Http\Controllers\AdminPrbController@edit');
        Route::post('/admin/prambanan/edit/{id}','App\Http\Controllers\AdminPrbController@post_edit');
        Route::get('/admin/prambanan/delete/{id}','App\Http\Controllers\AdminPrbController@delete');

        Route::get('/admin/ratuboko','App\Http\Controllers\AdminRbbController@index');
        Route::post('/admin/ratuboko','App\Http\Controllers\AdminRbbController@new');
        Route::get('/admin/ratuboko/edit/{id}','App\Http\Controllers\AdminRbbController@edit');
        Route::post('/admin/ratuboko/edit/{id}','App\Http\Controllers\AdminRbbController@post_edit');
        Route::get('/admin/ratuboko/delete/{id}','App\Http\Controllers\AdminRbbController@delete');

        Route::get('/admin/teapen','App\Http\Controllers\AdminTnpController@index');
        Route::post('/admin/teapen','App\Http\Controllers\AdminTnpController@new');
        Route::get('/admin/teapen/edit/{id}','App\Http\Controllers\AdminTnpController@edit');
        Route::post('/admin/teapen/edit/{id}','App\Http\Controllers\AdminTnpController@post_edit');
        Route::get('/admin/teapen/delete/{id}','App\Http\Controllers\AdminTnpController@delete');

        Route::get('/admin/tamanmini','App\Http\Controllers\AdminTmiController@index');
        Route::post('/admin/tamanmini','App\Http\Controllers\AdminTmiController@new');
        Route::get('/admin/tamanmini/edit/{id}','App\Http\Controllers\AdminTmiController@edit');
        Route::post('/admin/tamanmini/edit/{id}','App\Http\Controllers\AdminTmiController@post_edit');
        Route::get('/admin/tamanmini/delete/{id}','App\Http\Controllers\AdminTmiController@delete');

        Route::get('/admin/manohara','App\Http\Controllers\AdminMhyController@index');
        Route::post('/admin/manohara','App\Http\Controllers\AdminMhyController@new');
        Route::get('/admin/manohara/edit/{id}','App\Http\Controllers\AdminMhyController@edit');
        Route::post('/admin/manohara/edit/{id}','App\Http\Controllers\AdminMhyController@post_edit');
        Route::get('/admin/manohara/delete/{id}','App\Http\Controllers\AdminMhyController@delete');
    // ROUTE VOLUME
});

