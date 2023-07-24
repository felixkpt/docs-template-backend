<?php

use App\Http\Controllers\Admin\Posts\PostsController;
use Illuminate\Support\Facades\Route;

$controller = PostsController::class;
Route::get('/', [$controller, 'index'])->name('index');
Route::get('/list', [$controller, 'list'])->name('list');
Route::get('create', [$controller, 'create'])->name('create');
Route::post('/', [$controller, 'store'])->name('store');
Route::put('/', [$controller, 'update'])->name('update');
Route::get('/{id}', [$controller, 'show'])->name('show');
Route::delete('/{id}', [$controller, 'destroy'])->name('destroy');
