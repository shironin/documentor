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

Route::get('/', function () {
    return view('home');
});

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::auth();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', 'HomeController@index');
    Route::get('/messages', 'HomeController@messages');
    Route::get('/statistics', 'HomeController@statistics');
    Route::get('/faq', 'HomeController@faq');
    Route::get('/report', 'HomeController@report');
    Route::resource('doctypes', 'DoctypesController');
    Route::resource('documents', 'DocumentsController');
    Route::resource('departments', 'DepartmentsController');

    Route::post('storeDepartment', 'DepartmentsController@storeDepartment');
    Route::post('editDepartment', 'DepartmentsController@editDepartment');

    Route::post('storeDoctype', 'DoctypesController@storeDoctype');
    Route::post('editDoctype', 'DoctypesController@editDoctype');

    Route::post('storeDocument', 'DocumentsController@storeDocument');
    Route::post('editDocument', 'DocumentsController@editDocument');

    Route::post('/getDoctypeFields', 'AjaxController@getDoctypeFields');
    Route::post('/initiateSearchTool', 'AjaxController@initiateSearchTool');
    Route::post('/getAllDepartments', 'AjaxController@getAllDepartments');
    Route::post('/getDepartmentDoctypes', 'AjaxController@getDepartmentDoctypes');
    Route::post('/getSearchResult', 'AjaxController@getSearchResult');
    Route::get('/getDocumentFile', 'AjaxController@getDocumentFile');
    Route::post('/addPageToDocument', 'AjaxController@addPageToDocument');
    Route::post('/deleteDocumentFile', 'AjaxController@deleteDocumentFile');
    Route::post('/makeOcrforDocument', 'AjaxController@makeOcrforDocument');
    Route::post('/getDocumentActivity', 'AjaxController@getDocumentActivity');
    Route::post('/sendDocument', 'AjaxController@sendDocument');
    Route::post('/readMessage', 'AjaxController@readMessage');
    //Route::post('/setLanguage', 'AjaxController@setLocale');
    Route::post('/setLanguage', array(
        'Middleware' => 'LanguageSwitcher',
        'uses' => 'HomeController@setLanguage'
    ));
});

