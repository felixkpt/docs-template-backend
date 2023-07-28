<?php

use App\Http\Controllers\Admin\Settings\SocialMedia\Instagram\InstagramConfigController;

Route::get('/',[InstagramConfigController::class,'index']);
Route::post('/savepage',[InstagramConfigController::class,'storeInstagramPage']);
Route::post('/',[InstagramConfigController::class,'store']);
Route::get('/list-options',[InstagramConfigController::class, 'listSelectOptions']);
Route::get('/pages/list',[InstagramConfigController::class, 'listInstagramPages']);
Route::get('/queues',[InstagramConfigController::class,'listQueueUsers']);


Route::post('/queues/user/remove/{id}',[InstagramConfigController::class,'removeQueueUser']);
Route::post('/queues', [InstagramConfigController::class, 'saveQueueUser']);