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

Route::any('/', ["as" => "admin_index", "uses" => "LoginController@login"]);
Route::any('/logout', ["as" => "admin_logout", "uses" => 'LoginController@logout']);

Route::group(['prefix' => 'auth_users'], function (){
    Route::get('/','AuthUserController@index')->middleware('verify_permissions')->name('auth_users');
    Route::get('/get_types','AuthUserController@load')->name('get_user');
    Route::post('change_status','AuthUserController@change_status')->name('change_status_user');
    Route::post('/save','AuthUserController@save')->name('user_save');
    Route::get('/detail/{id?}','AuthUserController@detail')->middleware('verify_permissions')->name('detail_user');
});

Route::group(['prefix' => 'auth_role'], function (){
    Route::get('/','AuthRoleController@index')->middleware('verify_permissions')->name('auth_role');
    Route::get('/get_types','AuthRoleController@load')->name('get_role');
    Route::post('change_status','AuthRoleController@change_status')->name('change_status_role');
    Route::post('/save','AuthRoleController@save')->name('role_save');
    Route::post('/perms_save','AuthRoleController@permissionsSave')->name('perms_save');
    Route::get('/detail/{id?}','AuthRoleController@detail')->middleware('verify_permissions')->name('role_user');
    Route::get('/perms/{id}','AuthRoleController@perms')->middleware('verify_permissions')->name('perms');
});

Route::group(['prefix' => 'slider'], function (){
    Route::get('/','SliderController@index')->middleware('verify_permissions')->name('slider');
    Route::get('/get_types','SliderController@load')->name('get_slider');
    Route::post('change_status','SliderController@change_status')->name('change_status_slider');
    Route::post('/save','SliderController@save')->name('slider_save');
    Route::get('/detail/{id?}','SliderController@detail')->middleware('verify_permissions')->name('slider_detail');
});

Route::group(['prefix' => 'services'], function (){
    Route::get('/','ServicesController@index')->middleware('verify_permissions')->name('services');
    Route::get('/get_types','ServicesController@load')->name('get_services');
    Route::post('change_status','ServicesController@change_status')->name('change_status_services');
    Route::post('/save','ServicesController@save')->name('services_save');
    Route::get('/detail/{id?}','ServicesController@detail')->middleware('verify_permissions')->name('services_detail');
});

Route::group(['prefix' => 'services'], function (){
    Route::get('/','ServicesController@index')->middleware('verify_permissions')->name('services');
    Route::get('/get_types','ServicesController@load')->name('get_services');
    Route::post('change_status','ServicesController@change_status')->name('change_status_services');
    Route::post('/save','ServicesController@save')->name('services_save');
    Route::get('/detail/{id?}','ServicesController@detail')->middleware('verify_permissions')->name('services_detail');
});

Route::group(['prefix' => 'social_media'], function (){
    Route::get('/','SocialMediaController@index')->middleware('verify_permissions')->name('social_media');
    Route::get('/get_types','SocialMediaController@load')->name('get_social_media');
    Route::post('change_status','SocialMediaController@change_status')->name('change_status_social_media');
    Route::post('/save','SocialMediaController@save')->name('social_media_save');
    Route::get('/detail/{id?}','SocialMediaController@detail')->middleware('verify_permissions')->name('social_media_detail');
});
