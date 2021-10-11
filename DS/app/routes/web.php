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

# Spreadsheets
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware(\App\Http\Middleware\ForceJson::class)->group(function () {

    Route::prefix('/account/')->middleware('role:' . UserRole::Root)->group(function () {
        Route::post('/delete', 'AccountController@delete');
        Route::post('/update', 'AccountController@update');
        Route::post('/create', 'AccountController@create');
    });


    Route::prefix('/presentation/')->middleware('role:' . UserRole::Screen)->group(function () {
        Route::post('/save', 'PresentationController@savePresentation');

        Route::prefix('slide')->group(function () {
            Route::get('/', 'PresentationController@getSlides');
            Route::middleware('role:' . UserRole::Statistics)->group(function () {
                Route::get('/create', 'PresentationController@createSlide');
                Route::get('/indicators', 'PresentationController@setIndicators');
                Route::delete('/', 'PresentationController@deleteSlide');
                Route::get('/order', 'PresentationController@setOrder');
            });

        });

    });
    Route::prefix('/indicators/')->group(function () {
        Route::get('/values', 'IndicatorsController@getLastValues')->middleware('role:' . UserRole::Screen);
        Route::post('/update', 'IndicatorsController@update')->middleware('role:' . UserRole::Admin);
        Route::get('/units/{all?}', 'IndicatorsController@getUnits');
        Route::get('/', 'IndicatorsController@getIndicators');
    });

});


Route::middleware(\App\Http\Middleware\RedirectIfAuthenticated::class)->group(function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');


});
Route::get('logout', 'Auth\LoginController@logout')
    ->name('logout')
    ->middleware(\App\Http\Middleware\Authenticate::class);


// Front

Route::middleware('role:' . UserRole::Screen)->group(function () {
    Route::get('/', "IndicatorsController@index");
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('panel', ['as' => 'panel.index', 'uses' => 'PanelController@index']);
    Route::get('slider', ['as' => 'slider.index', 'uses' => 'SliderController@index']);
});
Route::middleware('role:' . UserRole::Admin)->group(function () {
    Route::get('settings', ['as' => 'settings.index', 'uses' => 'SettingsController@index']);
    Route::prefix('/planilhas')->group(function () {
        Route::get('/', 'SpreadsheetController@index');
        Route::get('/gcallback', 'SpreadsheetController@googleCallback');
        Route::get('/login', 'SpreadsheetController@googleLogin');
        Route::get('/logout', 'SpreadsheetController@googleLogout');
        Route::post('/pickFile', 'SpreadsheetController@pickFile');
        Route::get('/downloadFromDrive', 'SpreadsheetController@downloadFromDriveWithRedirects');
        Route::get('/download', 'SpreadsheetController@downloadLast');
    });
});
Route::middleware('role:' . UserRole::Statistics)->group(function () {
    Route::get('report', ['as' => 'report.index', 'uses' => 'ReportController@index']);
    Route::get('report/export','ReportController@generatePDF');
});
Route::middleware('role:' . UserRole::Root)->group(function () {
    Route::get('maintenance', ['as' => 'maintenance.index', 'uses' => 'MaintenanceController@index']);
});
