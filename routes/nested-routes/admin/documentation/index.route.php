<?php

use App\Http\Controllers\Admin\Documentation\DocumentationController;
use Illuminate\Support\Facades\Route;

$controller = DocumentationController::class;
Route::get('/', [$controller, 'index'])->name('documentation.index');
Route::get('/create', [$controller, 'create'])->name('documentation.create');
Route::post('/', [$controller, 'store'])->name('documentation.store');
Route::put('/{id}', [$controller, 'update'])->name('documentation.update');
Route::get('/{slug}', [$controller, 'show'])->name('documentation.show');
