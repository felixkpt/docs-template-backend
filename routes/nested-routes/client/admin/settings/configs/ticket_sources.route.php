<?php
use App\Http\Controllers\Admin\Settings\Configs\TicketSourceController;
use Illuminate\Support\Facades\Route;

$controller = TicketSourceController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeTicketSource']);
Route::get('/list',[$controller,'listTicketSources']);
Route::delete('/delete/{ticketsource}',[$controller,'destroyTicketSource']);
