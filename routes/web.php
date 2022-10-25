<?php

use App\Http\Controllers\Account\UserController;
use App\Http\Controllers\Account\UserFileController;
use App\Http\Controllers\Admin\Configuration\RolesController;
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

//AUTHENTICATION
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

    //USER AUTH
    Route::post('/signout', [LogoutController::class, 'logout'])->name('logout');
    Route::put('/user/update-password/{user}', [UserController::class, 'updatePassword'])->name('user.update-password');

    //NOTES
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

    //USER FILES
    Route::controller(UserFileController::class)->prefix('/user/file')->group(function(){
        Route::get('/get/{user}', 'get')->name('user.profile.get');
        Route::get('/download/{user}', 'download')->name('user.profile.download');

        Route::post('/upload', 'store')->name('user.temp.store');
        Route::delete('/revert', 'revert')->name('user.temp.revert');
    });

    //ADMIN
    Route::middleware('role:admin')->group(function(){

        //USER
        Route::get('/user/table', [UserController::class, 'table'])->name('user.table');
        Route::resource('/user', UserController::class);

        //ROLES
        Route::prefix('/role/permission')->controller(RolesController::class)->group(function(){
            Route::put('/update/{role}/{group}', 'updateRolePermission')->name('role.permission.update');
            Route::get('/table/{role}/{group}', 'tableRolePermission')->name('role.permission.table');
        });

        Route::get('/role/table', [RolesController::class, 'table'])->name('role.table');
        Route::resource('/role', RolesController::class);

    });

});
