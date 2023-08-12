<?php

use App\Http\Controllers\Admin\Doctors\DoctorsController;
use Illuminate\Support\Facades\Route;

$controller = DoctorsController::class;

Route::get('/', [$controller, 'index'])->name('list doctors');
