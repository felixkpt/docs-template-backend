<?php

use App\Http\Controllers\Admin\Settings\SocialMedia\Facebook\FacebookConfigController;

Route::get('/',[FacebookConfigController::class,'index']);
Route::post('/savepage',[FacebookConfigController::class,'storeFacebookPage']);
Route::post('/',[FacebookConfigController::class,'store']);
Route::get('/list-options',[FacebookConfigController::class, 'listSelectOptions']);
Route::get('/pages/list',[FacebookConfigController::class, 'listFacebookPages']);
Route::post('/pages/togglestatus/{id}',[FacebookConfigController::class, 'togglePageStatus']);
