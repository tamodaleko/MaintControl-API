<?php

/*
|--------------------------------------------------------------------------
| Auth Endpoints
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'auth'], function () {

    // Authentication
    Route::post('/', 'Auth\ApiAuthController@handle');

    // Initiate
    Route::get('initiate', 'Auth\ApiAuthController@initiate');

});

/*
|--------------------------------------------------------------------------
| Admin Endpoints
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin'], function () {

    // User
    Route::get('user/invite_code', 'Admin\UserController@inviteCode');
    Route::get('user/archived', 'Admin\UserController@archived');
    Route::post('user/{user}/status', 'Admin\UserController@status');
    Route::post('user/{user}/password_reset', 'Admin\UserController@passwordReset');
    Route::post('user/{user}/password_reset/token', 'Admin\UserController@passwordResetToken');
    
    Route::apiResource('user', 'Admin\UserController');

    // Area
    Route::post('area/{area}/status', 'Admin\AreaController@status');
    Route::get('area/archived', 'Admin\AreaController@archived');
    
    Route::apiResource('area', 'Admin\AreaController');

    // Workgroup
    Route::post('workgroup/{workgroup}/status', 'Admin\WorkgroupController@status');
    Route::get('workgroup/archived', 'Admin\WorkgroupController@archived');

    Route::apiResource('workgroup', 'Admin\WorkgroupController');

    // Asset
    Route::post('asset/{asset}/status', 'Admin\AssetController@status');
    Route::get('asset/archived', 'Admin\AssetController@archived');

    Route::apiResource('asset', 'Admin\AssetController');

    // Spare
    Route::post('spare/{spare}/status', 'Admin\SpareController@status');
    Route::get('spare/archived', 'Admin\SpareController@archived');

    Route::apiResource('spare', 'Admin\SpareController');

    // Company
    Route::get('company/info', 'Admin\CompanyController@info');
    Route::get('company/predefined_fields', 'Admin\CompanyController@predefinedFields');
    
    Route::post('company/info', 'Admin\CompanyController@updateInfo');
    Route::post('company/predefined_fields', 'Admin\CompanyController@updatePredefinedFields');

    // Location
    Route::post('location', 'Admin\LocationController@store');
    Route::put('location/{location}', 'Admin\LocationController@update');
    Route::delete('location/{location}', 'Admin\LocationController@destroy');

    // Currency
    Route::apiResource('currency', 'Admin\CurrencyController');

});

/*
|--------------------------------------------------------------------------
| User Endpoints
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'user'], function () {

    // User
    Route::get('/', 'User\UserController@index');
    Route::get('/info', 'User\UserController@info');
    Route::get('invite_code/{invite_code}', 'User\UserController@showByInviteCode');
    Route::post('invite_code/{invite_code}', 'User\UserController@updateByInviteCode');
    Route::post('display_information', 'User\UserController@displayInformation');
    Route::post('contact_information', 'User\UserController@contactInformation');
    Route::post('avatar', 'User\UserController@avatar');
    
    // Password
    Route::post('password', 'User\UserController@password');
    Route::post('password_reset', 'User\UserController@passwordReset');
    Route::post('password_reset/token', 'User\UserController@passwordResetToken');

    // User Preference
    Route::get('preference', 'User\UserPreferenceController@show');
    Route::post('preference', 'User\UserPreferenceController@store');

});

/*
|--------------------------------------------------------------------------
| Company Endpoints
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'company'], function () {

    // Predefined Data
    Route::get('/predefined_data', 'CompanyController@predefinedData');

});

/*
|--------------------------------------------------------------------------
| Area Endpoints
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'area'], function () {

    // Area
    Route::get('/', 'AreaController@index');

});

/*
|--------------------------------------------------------------------------
| Workgroup Endpoints
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'workgroup'], function () {

    // Workgroup
    Route::get('/', 'WorkgroupController@index');

});

/*
|--------------------------------------------------------------------------
| Asset Endpoints
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'asset'], function () {

    // Asset
    Route::get('/', 'AssetController@index');
    Route::get('/{asset}', 'AssetController@show');
    Route::post('/{asset}/meter_readings', 'AssetController@updateMeterReadings');

});

/*
|--------------------------------------------------------------------------
| Location Endpoints
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'location'], function () {

    // Location
    Route::get('/', 'LocationController@index');
    Route::get('/{location}', 'LocationController@show');

});

/*
|--------------------------------------------------------------------------
| Spare Endpoints
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'spare'], function () {

    // Spare
    Route::get('/', 'SpareController@index');

});

/*
|--------------------------------------------------------------------------
| File Endpoints
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'file'], function () {

    // File
    Route::post('upload/image', 'FileController@uploadImage');

});
