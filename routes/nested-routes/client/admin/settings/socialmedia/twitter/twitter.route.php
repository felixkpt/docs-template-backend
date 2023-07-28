<?php

use App\Http\Controllers\Admin\Settings\SocialMedia\Twitter\TwitterConfigController;

Route::get('/',[TwitterConfigController::class,'index']);
Route::post('/savepage',[TwitterConfigController::class,'storeTwitterPage']);
Route::post('/',[TwitterConfigController::class,'store']);
Route::get('/list-options',[TwitterConfigController::class, 'listSelectOptions']);
Route::get('/pages/list',[TwitterConfigController::class, 'listTwitterPages']);
Route::get('/queues',[TwitterConfigController::class,'listQueueUsers']);


Route::post('/queues/user/remove/{id}',[TwitterConfigController::class,'removeQueueUser']);
Route::post('/queues', [TwitterConfigController::class, 'saveQueueUser']);