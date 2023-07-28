<?php

use App\Http\Controllers\Admin\Settings\Debts\Picklists\PickListsController;
use Illuminate\Support\Facades\Route;


$controller = PickListsController::class;
Route::get('/',[$controller,'index']);
