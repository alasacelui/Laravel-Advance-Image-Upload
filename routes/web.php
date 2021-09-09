<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::group(['middleware' => 'auth'], function() {

    Route::delete('profile/{media}',[ProfileController::class, 'destroy_profile'])->name('profile.remove');
    Route::put('profile/{user}',[ProfileController::class, 'change_profile'])->name('profile.change');
    Route::resource('profile', ProfileController::class)->only(['index','store']);

    Route::post('profile/tmp_upload', [ProfileController::class, 'temporary_upload']);
    Route::delete('profile/tmp_upload/revert', [ProfileController::class,'revert']);



});

