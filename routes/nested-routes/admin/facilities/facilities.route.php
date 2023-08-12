<?php

use App\Http\Controllers\Admin\Facilities\FacilitiesController;
use Illuminate\Support\Facades\Route;

$controller = FacilitiesController::class;

Route::get('/', [$controller, 'index'])->name('list facilities');
