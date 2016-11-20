<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('generate/{documentName}',[ 'uses' => 'PdfGeneratorController@index']);
Route::get('listdirs',[ 'uses' => 'PdfGeneratorController@listDirs']);

     
        
View::addExtension('html', 'php');
Route::get('/', function () {
    return view('index');
});

Route::get('/about', function () {
    return view('index');
});
