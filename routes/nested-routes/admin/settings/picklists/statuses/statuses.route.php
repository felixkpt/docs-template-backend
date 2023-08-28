<?php

use App\Http\Controllers\Admin\Settings\Picklists\Statuses\StatusesController;
use Illuminate\Support\Facades\Route;

$controller = StatusesController::class;
Route::get('/', [$controller, 'index']);
Route::post('/', [$controller, 'store'])->hidden();
Route::put('/{id}', [$controller, 'update'])->hidden();
