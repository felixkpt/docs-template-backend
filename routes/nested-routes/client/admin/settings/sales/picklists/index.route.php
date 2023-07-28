<?php
use App\Http\Controllers\Admin\Settings\Sales\Picklists\PicklistsController;
use Illuminate\Support\Facades\Route;
$controller = PicklistsController::class;
Route::get('/', [$controller,'index']);