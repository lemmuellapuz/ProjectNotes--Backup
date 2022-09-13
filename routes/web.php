<?php

use App\Http\Controllers\Authentication\GoogleLoginController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\LogoutController;
use App\Http\Controllers\Authentication\RegistrationController;
use App\Http\Controllers\NoteFileController;
use App\Http\Controllers\Notes\ImportController;
use App\Http\Controllers\Notes\NotesController;
use App\Http\Controllers\TemporaryFileController;
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

Route::controller(LoginController::class)->group(function(){

    Route::get('/', 'index')->name('login');
    Route::post('/', 'signIn')->name('login.attempt');

});

Route::controller(GoogleLoginController::class)->group(function(){
   
    Route::get('/auth/google/redirect', 'googleRedirect')->name('login.google');
    Route::get('/auth/google/callback', 'googleCallback');

});

Route::controller(RegistrationController::class)->group(function(){
    
    Route::get('/sign-up', 'index')->name('signup');
    Route::post('/sign-up', 'signUp')->name('signup.attempt');

});

Route::middleware(['auth', 'auth.session'])->group(function(){

    Route::post('signout', [LogoutController::class, 'logout'])->name('logout');

    Route::controller(NotesController::class)->group(function(){

        Route::get('/notes/list', 'table')->name('notes.table');
        Route::post('notes/qr', 'showViaQr')->name('notes.show.via-qr');

    });
    Route::resource('/notes', NotesController::class);
    
    Route::prefix('/notes/file')->group(function(){

        Route::controller(TemporaryFileController::class)->group(function(){
            Route::post('/upload', 'store')->name('temp.store');
            Route::delete('/revert', 'revert')->name('temp.revert');
        });
    
        Route::controller(NoteFileController::class)->group(function(){
            Route::get('/get/{notefile}', 'get')->name('file.get');
            Route::get('/download/{notefile}', 'download')->name('file.download');
            Route::delete('/destroy/{notefile}', 'destroy')->name('file.destroy');
        });
    
        Route::controller(ImportController::class)->group(function(){
            Route::get('/import', 'index')->name('file.index');
            Route::post('/import', 'import')->name('file.import');
        });

    });

});
