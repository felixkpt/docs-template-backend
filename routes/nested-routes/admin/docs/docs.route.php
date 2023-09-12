<?php

use App\Http\Controllers\Admin\DocumentationPages\DocumentationPagesController;
use Illuminate\Support\Facades\Route;

$controller = DocumentationPagesController::class;
Route::get('/', [$controller, 'index'])->name('Docs List')->everyone(true);
Route::get('/create', [$controller, 'create'])->name('documentation.create');
Route::post('/', [$controller, 'store'])->name('documentation.store');
