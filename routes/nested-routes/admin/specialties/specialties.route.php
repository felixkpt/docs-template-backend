<?php

use App\Http\Controllers\Admin\Specialities\SpecialtiesController;
use Illuminate\Support\Facades\Route;

$controller = SpecialtiesController::class;

Route::get('/', [$controller, 'index'])->name('list specialties');
