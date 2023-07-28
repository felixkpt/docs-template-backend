<?php

use App\Http\Controllers\Admin\Settings\Configs\ConfigsController;
use Illuminate\Support\Facades\Route;

$controller = ConfigsController::class;

Route::get('/',[$controller,'index']);
