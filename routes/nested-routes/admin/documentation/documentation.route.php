<?php

use App\Http\Controllers\Admin\Documentation\DocumentationController;
use Illuminate\Support\Facades\Route;

$controller = DocumentationController::class;
Route::get('/', [$controller, 'index'])->name('Documentation List')->everyone(true);
Route::get('/create', [$controller, 'create'])->name('documentation.create');
Route::post('/', [$controller, 'store'])->name('documentation.store');
