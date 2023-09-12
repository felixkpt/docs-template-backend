<?php

use App\Http\Controllers\Admin\DocumentationPages\Categories\CategoriesController;
use Illuminate\Support\Facades\Route;

$controller = CategoriesController::class;
Route::get('/', [$controller, 'index'])->name('Categories List')->everyone(true);
Route::get('/create', [$controller, 'create'])->name('Create section')->hidden();
Route::post('/', [$controller, 'store'])->name('Store section');
Route::get('/{slug}', [$controller, 'show'])->name('Show Category')->everyone(true);
Route::get('/{slug}/topics', [$controller, 'listCatTopics'])->name('List Cat Topics')->everyone(true);
