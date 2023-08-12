<?php

use App\Http\Controllers\Admin\documentation\Documentation\DocumentationController;
use Illuminate\Support\Facades\Route;

$controller = DocumentationController::class;
Route::put('/', [$controller, 'update'])->name('update');
Route::get('/{id}', [$controller, 'show'])->name('show');
Route::delete('/{id}', [$controller, 'destroy'])->name('destroy');
