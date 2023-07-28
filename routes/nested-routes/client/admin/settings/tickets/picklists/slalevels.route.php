<?php
use App\Http\Controllers\Admin\Settings\Tickets\Picklists\SlaLevelController;
use Illuminate\Support\Facades\Route;

$controller = SlaLevelController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeSlaLevel']);

Route::get('/list',[$controller,'listSlaLevels']);
Route::delete('/delete/{slalevel}',[$controller,'destroySlaLevel']);
