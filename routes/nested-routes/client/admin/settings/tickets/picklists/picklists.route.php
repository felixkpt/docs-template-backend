<?php

use App\Http\Controllers\Admin\Settings\Tickets\Picklists\PicklistsController;
use Illuminate\Support\Facades\Route;

$controller = PicklistsController::class;
Route::resource('/',PicklistsController::class);

