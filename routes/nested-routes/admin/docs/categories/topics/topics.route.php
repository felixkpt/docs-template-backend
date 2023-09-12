<?php

use App\Http\Controllers\Admin\DocumentationPages\Categories\Topics\TopicsController;
use Illuminate\Support\Facades\Route;

$controller = TopicsController::class;
Route::get('/', [$controller, 'index'])->name('Topics List')->hidden();
Route::get('/create', [$controller, 'create'])->name('Create topic')->hidden();
Route::post('/', [$controller, 'store'])->name('Store topic');
