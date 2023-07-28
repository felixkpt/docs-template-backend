<?php

use App\Http\Controllers\Admin\Settings\Tickets\Picklists\TicketStatusController;
use Illuminate\Support\Facades\Route;

$controller = TicketStatusController::class;

Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeTicketStatus']);
Route::get('/list',[$controller,'listTicketStatuses']);
Route::delete('/delete/{ticketstatus}',[$controller,'destroyTicketStatus']);
Route::post('/activate/{id}', [$controller, 'activate']);
Route::post('/deactivate/{id}', [$controller, 'deactivate']);
